<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\LostAndFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentStatusMail;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // For admin usage to list students
        $query = Student::with('user');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function approve(Request $request, Student $student)
    {
        $request->validate([
            'program' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:255'],
        ]);

        $student->update([
            'program' => $request->program,
            'year_level' => $request->year_level,
            'status' => Student::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        Mail::to($student->user->email)->send(new StudentStatusMail($student, Student::STATUS_APPROVED));

        return back()->with('success', "Student {$student->full_name} has been approved and assigned to {$student->program}.");
    }

    public function deny(Request $request, Student $student)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $student->update([
            'status' => Student::STATUS_DENIED,
        ]);

        Mail::to($student->user->email)->send(new StudentStatusMail($student, Student::STATUS_DENIED, $request->reason));

        return back()->with('success', "Student {$student->full_name} has been denied.");
    }

    public function dashboard()
    {
        $user = Auth::guard('student')->user();
        $student = $user->student;
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Fetch events relevant to the student (similar to landing page logic)
        $events = Event::with(['speakers', 'eventDates'])
            ->latest()
            ->get()
            ->filter(function ($event) use ($currentMonth, $currentYear, $student) {
                $onThisMonth = $event->eventDates->contains(function ($eventDate) use ($currentMonth, $currentYear) {
                    $carbonDate = \Carbon\Carbon::parse($eventDate->date);
                    return $carbonDate->month == $currentMonth && $carbonDate->year == $currentYear;
                });

                if (!$onThisMonth) return false;

                $departments = $event->departments ?? ['All'];
                if (in_array('All', $departments)) return true;

                return in_array($student->program, $departments);
            });

        $announcements = Announcement::whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $currentMonth)
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        $lostAndFoundItems = LostAndFound::where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $analytics = [
            'total_events_joined' => $student->events()->count(),
            'total_attendances' => $student->attendances()->count(),
            'total_certificates' => $student->certificates()->count(),
            'total_reports' => LostAndFound::where('user_id', $user->id)->count(),
            'resolved_reports' => LostAndFound::where('user_id', $user->id)->where('status', 'resolved')->count(),
        ];

        return view('student.dashboard', compact('events', 'announcements', 'lostAndFoundItems', 'analytics'));
    }

    public function myLostAndFoundReports()
    {
        $items = LostAndFound::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('student.lost-and-found.my-reports', compact('items'));
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
            'year_level' => ['required', 'string', 'max:255'],
        ]);

        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'year_level' => $request->year_level,
        ]);

        // Sync updates to users table
        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
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
