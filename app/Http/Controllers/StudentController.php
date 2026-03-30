<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use App\Models\EnrollmentStatus;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\LostAndFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Messenger;
use App\Mail\StudentStatusMail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use App\Models\Classification;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->get();
        $statuses = EnrollmentStatus::orderBy('label')->get();
        $classifications = Classification::orderBy('label')->get();
        $statusMap = $statuses->mapWithKeys(function($s){
            return [strtolower($s->name) => $s->label];
        })->toArray();
        $classificationMap = $classifications->mapWithKeys(function($c){
            return [strtolower($c->name) => $c->label];
        })->toArray();
        return view('admin.students.index', compact('students', 'statusMap', 'classificationMap'));
    }

    public function exportCsv(Request $request)
    {
        $query = Student::with('user');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $students = $query->latest()->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students.csv"',
        ];
        $callback = function () use ($students) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student Number', 'First Name', 'Last Name', 'Program', 'Year Level', 'Type', 'Status', 'Email', 'Joined At']);
            foreach ($students as $s) {
                fputcsv($handle, [
                    $s->student_number,
                    $s->first_name,
                    $s->last_name,
                    $s->program,
                    $s->year_level,
                    $s->student_type,
                    $s->status,
                    optional($s->user)->email,
                    optional($s->created_at)->toDateTimeString(),
                ]);
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'students.csv', $headers);
    }

    public function approve(Request $request, Student $student)
    {
        $request->validate([
            'program'           => [
                'required',
                'string',
                Rule::exists('programs', 'name')->where('is_active', true),
            ],
            'year_level'        => ['required', 'string', 'in:1st Year,2nd Year,3rd Year,4th Year,N/A'],
            'enrollment_status' => [
                'required',
                'string',
                Rule::exists('enrollment_statuses', 'name')->where('is_active', true),
            ],
            'classification'    => [
                'required',
                'string',
                Rule::exists('classifications', 'name')->where('is_active', true),
            ],
        ]);

        // Auto-override classification: 1st Year students are always Freshies
        $classification = $request->year_level === '1st Year'
            ? Student::CLASSIFICATION_FRESHIE
            : $request->classification;

        $student->update([
            'program'           => $request->program,
            'year_level'        => $request->year_level,
            'enrollment_status' => $request->enrollment_status,
            'classification'    => $classification,
            'status'            => Student::STATUS_APPROVED,
            'approved_at'       => now(),
        ]);

        Messenger::send($student->user->email, new StudentStatusMail($student, Student::STATUS_APPROVED));

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

        Messenger::send($student->user->email, new StudentStatusMail($student, Student::STATUS_DENIED, $request->reason));

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

        $announcements = Announcement::with('attachments')
            ->where('is_archived', false)
            ->whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $currentMonth)
            ->where('is_active', true)
            ->latest()
            ->get()
            ->filter(function ($announcement) use ($student) {
                // Target Audience filtering
                if ($announcement->target_audience === 'all') {
                    return true;
                }

                if ($announcement->target_audience === 'guests') {
                    return $student->student_type === 'guest';
                }

                if ($announcement->target_audience === 'students') {
                    if ($student->student_type !== 'regular') {
                        return false;
                    }

                    // If target year levels are specified, check if student matches
                    if ($announcement->target_year_levels && count($announcement->target_year_levels) > 0) {
                        return in_array($student->year_level, $announcement->target_year_levels);
                    }

                    return true;
                }

                return false;
            })
            ->take(5);

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
            ->get();
        
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
        $programs = Program::orderBy('name')->get();
        $statuses = EnrollmentStatus::orderBy('label')->get();
        $classifications = Classification::orderBy('label')->get();
        return view('admin.students.show', compact('student', 'programs', 'statuses', 'classifications'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $programs = Program::active()->orderBy('name')->get();
        $statuses = EnrollmentStatus::active()->orderBy('label')->get();
        $classifications = Classification::active()->orderBy('label')->get();
        return view('admin.students.edit', compact('student', 'programs', 'statuses', 'classifications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
            $validated = $request->validate([
                'program'           => [
                    'nullable',
                    'string',
                    Rule::exists('programs', 'name')->where('is_active', true),
                ],
                'year_level'        => ['nullable', 'string', 'in:1st Year,2nd Year,3rd Year,4th Year,N/A'],
                'enrollment_status' => [
                    'nullable',
                    'string',
                    Rule::exists('enrollment_statuses', 'name')->where('is_active', true),
                ],
                'classification'    => [
                    'nullable',
                    'string',
                    Rule::exists('classifications', 'name')->where('is_active', true),
                ],
                'status'            => ['required', 'in:pending,approved,denied,expired,inactive'],
            ]);

            $student->update($validated);

            return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->status !== Student::STATUS_INACTIVE) {
            $student->status = Student::STATUS_INACTIVE;
            $student->save();
        }
        return redirect()->route('admin.students.index')->with('success', 'Student account set to inactive.');
    }
}
