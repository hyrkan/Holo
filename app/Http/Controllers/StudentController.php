<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For admin usage to list students
        $students = Student::with('user')->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the profile edit form for the authenticated student.
     */
    public function profile()
    {
        $user = Auth::guard('student')->user();
        $student = $user->student;
        return view('student.profile', compact('user', 'student'));
    }

    /**
     * Update the authenticated student's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('student')->user();
        $student = $user->student;

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:255'],
        ]);

        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'program' => $request->program,
            'year_level' => $request->year_level,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the authenticated student's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Display the events joined by the authenticated student.
     */
    public function joinedEvents()
    {
        $user = Auth::guard('student')->user();
        $student = $user->student;
        
        // Load events with their specific data
        $events = $student->events()
            ->with(['eventDates', 'speakers', 'certificates'])
            ->latest('event_registrations.created_at')
            ->paginate(10);
            
        // Eager load awarded certificates for this student and these specific events
        $awardedCertificateIds = $student->certificates()->pluck('certificates.id')->toArray();

        return view('student.events.joined', compact('events', 'awardedCertificateIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['user', 'events', 'attendances.eventDate.event']);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
