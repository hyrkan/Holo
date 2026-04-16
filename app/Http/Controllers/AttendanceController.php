<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Student;
use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Show the universal scanner UI.
     * Can be event-specific or general.
     */
    public function showScanner(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id'
        ]);

        $event = Event::with('eventDates')->findOrFail($request->event_id);
        
        return view('admin.attendance.scanner', compact('event'));
    }

    /**
     * Mark attendance or look up student by scanning student QR.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'registration_uuid' => 'required|exists:event_registrations,uuid',
            'event_id' => 'required|exists:events,id',
            'photo' => 'nullable|string', // Base64 image
        ]);

        $registration = \App\Models\EventRegistration::with(['student.user', 'event'])
            ->where('uuid', $request->registration_uuid)
            ->where('status', 'registered')
            ->first();
        
        if (!$registration || $registration->event_id != $request->event_id) {
            return response()->json([
                'success' => false,
                'type' => 'not_found',
                'message' => 'Registration not found for this event.',
            ], 200);
        }

        $student = $registration->student;
        $event = $registration->event;
        $today = Carbon::today()->toDateString();
        
        // Response data for lookup
        $studentData = [
            'name' => $student->first_name . ' ' . $student->last_name,
            'student_number' => $student->student_number,
            'email' => $student->user->email,
            'program' => $student->program,
            'year_level' => $student->year_level,
            'profile_url' => route('admin.students.show', $student->id),
        ];

        // 2. Find if today is an active date for this event
        $eventDate = EventDate::where('event_id', $event->id)
            ->whereDate('date', $today)
            ->first();

        if (!$eventDate) {
            return response()->json([
                'success' => false,
                'type' => 'event_context',
                'message' => 'No session scheduled for today (' . Carbon::today()->format('M d, Y') . ') for this event.',
                'student' => $studentData,
            ], 200);
        }

        // 2.5 Check if attendance is already open
        if ($eventDate->start_time) {
            $buffer = $event->attendance_start_buffer ?? 0;
            $startTime = Carbon::parse($eventDate->date . ' ' . $eventDate->start_time);
            $openingTime = $startTime->copy()->subMinutes($buffer);

            if (now()->lt($openingTime)) {
                return response()->json([
                    'success' => false,
                    'type' => 'event_context',
                    'message' => 'Attendance for this session is not yet open. It will open at ' . $openingTime->format('h:i A') . '.',
                    'student' => $studentData,
                ], 200);
            }
        }

        // 3. Mark attendance
        $attendance = Attendance::where('student_id', $student->id)
            ->where('event_date_id', $eventDate->id)
            ->first();

        // Handle Photo Upload
        $photoPath = null;
        if ($request->photo) {
            $photoPath = ImageStorage::uploadBase64($request->photo, 'attendance');
        }

        if ($attendance) {
            // Case A: Missing Clock-in (e.g. from manual entry or old scan)
            if (!$attendance->clock_in) {
                if (!$request->photo) {
                    return response()->json([
                        'success' => true,
                        'type' => 'needs_photo',
                        'student' => $studentData
                    ]);
                }

                $attendance->update([
                    'clock_in' => now(),
                    'photo' => $photoPath,
                    'scanned_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'type' => 'clock_in_recorded',
                    'message' => 'Clock-in recorded successfully!',
                    'student' => $studentData,
                ]);
            }

            // Case B: Already Clocked In, now Clocking Out
            if (!$attendance->clock_out) {
                $attendance->update([
                    'clock_out' => now(),
                    'scanned_at' => now(),
                ]);

                // Automated certification check
                $this->awardCertificateIfEligible($student, $event, $eventDate);

                return response()->json([
                    'success' => true,
                    'type' => 'clock_out_recorded',
                    'message' => 'Clock-out recorded successfully!',
                    'student' => $studentData,
                ]);
            }

            // Case C: Attendance already completed
            return response()->json([
                'success' => false,
                'type' => 'attendance_exists',
                'message' => 'Attendance already completed.',
                'student' => $studentData,
            ], 200);
        }

        // Case D: Completely New Attendance Record - Needs Photo First
        if (!$request->photo) {
            return response()->json([
                'success' => true,
                'type' => 'needs_photo',
                'student' => $studentData
            ]);
        }

        Attendance::create([
            'student_id' => $student->id,
            'event_date_id' => $eventDate->id,
            'status' => 'present',
            'scanned_at' => now(),
            'clock_in' => now(),
            'photo' => $photoPath,
        ]);

        return response()->json([
            'success' => true,
            'type' => 'clock_in_recorded',
            'message' => 'Clock-in recorded successfully!',
            'student' => $studentData,
        ]);
    }

    /**
     * Automatically awards the selected default certificate if the student qualifies.
     */
    protected function awardCertificateIfEligible(Student $student, Event $event, EventDate $currentDate)
    {
        // 1. Check if this is the last day of the event
        $lastDate = EventDate::where('event_id', $event->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->first();

        if ($lastDate && $lastDate->id != $currentDate->id) {
            return; // Not the last day yet
        }

        // 2. Check if student attended all days
        if (!$student->hasAttendedAllEventDates($event)) {
            return;
        }

        // 3. Get the active default certificate template
        $template = \App\Models\DefaultCertificate::where('is_selected', true)->with('signatories')->first();
        if (!$template) {
            return; // No default certificate selected
        }

        // 4. Check if a certificate for this event already exists from this template
        $certificate = \App\Models\Certificate::where('event_id', $event->id)
            ->where('source_template_id', $template->id)
            ->first();

        if (!$certificate) {
            // Create event-specific certificate from template
            $certificate = \App\Models\Certificate::create([
                'event_id' => $event->id,
                'source_template_id' => $template->id,
                'name' => 'Auto-Generated Attendance Certificate',
                'title' => $template->title,
                'sub_title' => $template->sub_title,
                'body' => $template->body,
                'background_image' => $template->background_image,
                'is_active' => true,
            ]);

            // Copy signatories
            foreach ($template->signatories as $sig) {
                $certificate->signatories()->create([
                    'name' => $sig->name,
                    'label' => $sig->label,
                    'signature_image' => $sig->signature_image,
                    'order' => $sig->order,
                ]);
            }
        }

        // 5. Award to student if not already awarded
        if (!$student->certificates()->where('certificate_id', $certificate->id)->exists()) {
            $token = Str::random(32);
            $student->certificates()->attach($certificate->id, [
                'verification_token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 6. Send notification if student has email
            if ($student->user && $student->user->email) {
                \App\Helpers\Messenger::send(
                    $student->user->email, 
                    new \App\Mail\CertificateAwardedMail($student, $event, [$certificate])
                );
            }
        }
    }
}
