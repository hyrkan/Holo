<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    /**
     * Publicly verify a certificate using its unique token.
     */
    public function verify($token)
    {
        // Special case for demonstration/preview
        if ($token === 'PREVIEW_ONLY') {
            $student = (object)[
                'full_name' => 'JUAN DELA CRUZ',
                'program' => 'BS Information Technology',
                'face_photo_url' => 'https://ui-avatars.com/api/?name=Juan+Dela+Cruz&background=4700c8&color=fff&size=128'
            ];
            $certificate = (object)[
                'title' => 'Certificate of Achievement',
                'event' => (object)['name' => 'Sample Conference 2026']
            ];
            $awardedAt = now();
            
            return view('certificates.verify', compact('student', 'certificate', 'awardedAt', 'token'));
        }

        $award = DB::table('certificate_student')
            ->where('verification_token', $token)
            ->first();

        if (!$award) {
            return view('certificates.verification_failed');
        }

        $student = Student::findOrFail($award->student_id);
        $certificate = Certificate::with('event')->findOrFail($award->certificate_id);
        $awardedAt = \Carbon\Carbon::parse($award->created_at);

        return view('certificates.verify', compact('student', 'certificate', 'awardedAt', 'token'));
    }
}
