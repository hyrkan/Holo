<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\DB;

$events = Event::with('eventDates', 'certificates')->get();
$count = 0;
foreach ($events as $event) {
    if ($event->eventDates->isEmpty()) continue;
    
    $registrations = EventRegistration::where('event_id', $event->id)->get();
    foreach ($registrations as $reg) {
        $student = Student::find($reg->student_id);
        if (!$student) continue;
        
        $totalDates = $event->eventDates->count();
        $attendedDates = $student->attendances()
            ->whereIn('event_date_id', $event->eventDates->pluck('id'))
            ->count();
            
        // If they attended all dates, or if they were already marked as eligible
        if ($attendedDates >= $totalDates || $reg->is_eligible_for_certificate) {
            foreach ($event->certificates as $cert) {
                $exists = DB::table('certificate_student')
                    ->where('certificate_id', $cert->id)
                    ->where('student_id', $student->id)
                    ->exists();
                    
                if (!$exists) {
                    DB::table('certificate_student')->insert([
                        'certificate_id' => $cert->id,
                        'student_id' => $student->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $count++;
                }
            }
            
            // Also ensure the general eligibility flag is true
            if (!$reg->is_eligible_for_certificate) {
                $reg->update(['is_eligible_for_certificate' => true]);
            }
        }
    }
}
echo "Recovered $count certificates based on attendance and registration status.\n";
