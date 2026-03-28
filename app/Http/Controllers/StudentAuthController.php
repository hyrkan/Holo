<?php

namespace App\Http\Controllers;

use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // Check if user has the student role
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
}
