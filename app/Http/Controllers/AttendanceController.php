<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventDate;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Mark attendance by scanning student QR.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'student_uuid' => 'required|exists:students,uuid',
            'event_date_id' => 'required|exists:event_dates,id',
        ]);

        $student = Student::where('uuid', $request->student_uuid)->firstOrFail();
        $eventDate = EventDate::with('event')->findOrFail($request->event_date_id);

        // Check if student is registered for the event
        if (!$eventDate->event->students()->where('student_id', $student->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Student is not registered for this event.',
            ], 422);
        }

        // Check if already marked present for this date
        $attendance = Attendance::where('student_id', $student->id)
            ->where('event_date_id', $eventDate->id)
            ->first();

        if ($attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already recorded for today.',
                'student' => $student->first_name . ' ' . $student->last_name,
            ], 200); // 200 but success false is fine for some UI
        }

        Attendance::create([
            'student_id' => $student->id,
            'event_date_id' => $eventDate->id,
            'status' => 'present',
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully!',
            'student' => $student->first_name . ' ' . $student->last_name,
        ]);
    }
}
