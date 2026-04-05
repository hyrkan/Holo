<?php

namespace App\Http\Controllers;

use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;

class StudentAuthController extends Controller
{
    public function login()
    {
        return view('student.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $user = Auth::guard('student')->user();

            if (!$user->hasRole('student')) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'These credentials do not match our student records.',
                ])->onlyInput('email');
            }

            $student = $user->student;

            if (!$student) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Student record not found.',
                ])->onlyInput('email');
            }

            if ($student->isExpired()) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Your guest account has expired.',
                ])->onlyInput('email');
            }

            if ($student->status === \App\Models\Student::STATUS_INACTIVE) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ])->onlyInput('email');
            }

            if ($student->status === \App\Models\Student::STATUS_DENIED) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Your account registration has been denied.',
                ])->onlyInput('email');
            }

            if ($student->status === \App\Models\Student::STATUS_PENDING) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval by the admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('student.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'in:1st year,2nd year,3rd year,4th year'],
            'section' => ['required', 'string', 'max:255'],
            'student_number' => ['required', 'string', 'max:255', 'unique:students,student_number'],
            'student_type' => ['required', 'string', 'in:regular,guest'],
            'id_front' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'id_back' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'face_image' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[^@\\s]+@usa\\.edu\\.ph$/i'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.regex' => 'Email address must end with @usa.edu.ph.',
        ]);


        try {
            $idFront = $request->file('id_front');

            $prompt = "Act as an identity verification expert. 
            Extract the 'Student ID Number', 'First Name', and 'Last Name' from this ID card image. 
            Return ONLY a JSON object with these keys: student_number, first_name, last_name.
            If names are joined (e.g. FERNANDEZ, Kris Dane), split them.
            If you cannot find a value, set it to null.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent([
                    $prompt,
                    new Blob(
                        mimeType: MimeType::IMAGE_JPEG,
                        data: base64_encode(file_get_contents($idFront->path()))
                    )
                ]);

            $aiData = json_decode(str_replace(['```json', '```'], '', $result->text()), true);


            if (empty($aiData['student_number']) && (empty($aiData['last_name']) || empty($aiData['first_name']))) {
                return back()->withErrors([
                    'id_front' => 'We could not clearly read your ID. Please upload a clearer photo with better lighting and no glare.',
                ])->withInput();
            }


            $extractedNumber = str_replace(['-', ' '], '', $aiData['student_number'] ?? '');
            $providedNumber = str_replace(['-', ' '], '', $request->student_number);

            if ($extractedNumber !== $providedNumber) {
                return back()->withErrors([
                    'id_front' => "Verification failed: The Student Number on the ID ($extractedNumber) does not match the provided number ($providedNumber).",
                ])->withInput();
            }

            $aiFirstName = strtolower($aiData['first_name'] ?? '');
            $aiLastName = strtolower($aiData['last_name'] ?? '');
            $providedFirstName = strtolower($request->first_name);
            $providedLastName = strtolower($request->last_name);


            $firstNameValid = !empty($aiFirstName) && (stripos($aiFirstName, $providedFirstName) !== false || stripos($providedFirstName, $aiFirstName) !== false);
            $lastNameValid = !empty($aiLastName) && (stripos($aiLastName, $providedLastName) !== false || stripos($providedLastName, $aiLastName) !== false);

            if (!$firstNameValid || !$lastNameValid) {
                return back()->withErrors([
                    'id_front' => "Verification failed: The name on the ID ($aiData[first_name] $aiData[last_name]) does not match your registration name ($request->first_name $request->last_name).",
                ])->withInput();
            }

        } catch (\Exception $e) {
            // Handle Gemini failure (either skip or log)
            // \Log::error('Gemini ID Verification failed: ' . $e->getMessage());
        }

        $user = \App\Models\User::create([
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        $user->assignRole('student');

        $expiredAt = null;
        if ($request->student_type === \App\Models\Student::TYPE_GUEST) {
            $expiredAt = now()->addDays(30);
        }

        $studentNumber = $request->student_number;

        $student = $user->student()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'year_level' => $request->year,
            'section' => $request->section,
            'student_number' => $studentNumber,
            'student_type' => $request->student_type,
            'status' => \App\Models\Student::STATUS_PENDING,
            'expired_at' => $expiredAt,
        ]);

        $idDir = 'students/ids/' . $student->uuid;
        $faceDir = 'students/faces/' . $student->uuid;
        $idFrontPath = null;
        $idBackPath = null;
        $facePhotoPath = null;

        if ($request->file('id_front')) {
            $idFrontPath = ImageStorage::upload($request->file('id_front'), $idDir);
        }
        if ($request->file('id_back')) {
            $idBackPath = ImageStorage::upload($request->file('id_back'), $idDir);
        }
        if ($request->face_image) {
            $facePhotoPath = ImageStorage::uploadBase64($request->face_image, $faceDir);
        }

        $student->update([
            'id_front_path' => $idFrontPath,
            'id_back_path' => $idBackPath,
            'face_photo_path' => $facePhotoPath,
        ]);

        return redirect()->route('student.login')->with('success', 'Registration successful! Your account is pending approval by the admin.');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function verifyId(Request $request)
    {
        $request->validate([
            'id_front' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'student_number' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
        ]);

        try {
            $idFront = $request->file('id_front');

            $prompt = "Act as an identity verification expert. 
            Extract the 'Student ID Number', 'First Name', and 'Last Name' from this ID card image. 
            Return ONLY a JSON object with these keys: student_number, first_name, last_name.
            If names are joined (e.g. FERNANDEZ, Kris Dane), split them.
            If you cannot find a value, set it to null.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent([
                    $prompt,
                    new Blob(
                        mimeType: MimeType::IMAGE_JPEG,
                        data: base64_encode(file_get_contents($idFront->path()))
                    )
                ]);

            $aiData = json_decode(str_replace(['```json', '```'], '', $result->text()), true);

            if (empty($aiData['student_number']) && (empty($aiData['last_name']) || empty($aiData['first_name']))) {
                return response()->json(['success' => false, 'message' => 'We could not clearly read your ID. Please upload a clearer photo.']);
            }

            $extractedNumber = str_replace(['-', ' '], '', $aiData['student_number'] ?? '');
            $providedNumber = str_replace(['-', ' '], '', $request->student_number);

            if ($extractedNumber !== $providedNumber) {
                return response()->json(['success' => false, 'message' => "The Student Number on the ID ($extractedNumber) does not match your input ($providedNumber)."]);
            }

            $aiFirstName = strtolower($aiData['first_name'] ?? '');
            $aiLastName = strtolower($aiData['last_name'] ?? '');
            $providedFirstName = strtolower($request->first_name);
            $providedLastName = strtolower($request->last_name);

            $firstNameValid = !empty($aiFirstName) && (stripos($aiFirstName, $providedFirstName) !== false || stripos($providedFirstName, $aiFirstName) !== false);
            $lastNameValid = !empty($aiLastName) && (stripos($aiLastName, $providedLastName) !== false || stripos($providedLastName, $aiLastName) !== false);

            if (!$firstNameValid || !$lastNameValid) {
                return response()->json(['success' => false, 'message' => "The name on the ID ($aiData[first_name] $aiData[last_name]) does not match your registration name."]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'AI Verification service busy. Please try again in a moment.']);
        }
    }
}
