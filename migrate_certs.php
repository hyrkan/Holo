<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EventRegistration;
use App\Models\Student;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

$registrations = EventRegistration::where('is_eligible_for_certificate', true)->get();
$count = 0;
foreach ($registrations as $reg) {
    $student = Student::find($reg->student_id);
    if (!$student) continue;
    
    $certificates = Certificate::where('event_id', $reg->event_id)->get();
    foreach ($certificates as $cert) {
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
}
echo "Awarded $count certificates based on legacy eligibility.\n";
