<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Student;
use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
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
            'student_uuid' => 'required|exists:students,uuid',
            'event_id' => 'required|exists:events,id',
            'photo' => 'nullable|string', // Base64 image
        ]);

        $student = Student::with('user')->where('uuid', $request->student_uuid)->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'type' => 'not_found',
                'message' => 'Student not found with this ID code.',
            ], 200);
        }

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

        // Context is an event attendance scan
        $event = Event::findOrFail($request->event_id);
        
        // 1. Check if student is registered for this event
        $registration = $event->registrations()
            ->where('student_id', $student->id)
            ->where('status', 'registered')
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'type' => 'event_context',
                'message' => 'Student is found but NOT registered for this event.',
                'student' => $studentData,
            ], 200);
        }

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
}
