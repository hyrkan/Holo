<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $currentMonth = now()->month;
    $currentYear = now()->year;

    $events = \App\Models\Event::with(['speakers', 'eventDates'])->latest()->get()->filter(function ($event) use ($currentMonth, $currentYear) {
        // Date filter
        $onThisMonth = $event->eventDates->contains(function ($eventDate) use ($currentMonth, $currentYear) {
            $carbonDate = \Carbon\Carbon::parse($eventDate->date);

            return $carbonDate->month == $currentMonth && $carbonDate->year == $currentYear;
        });

        if (! $onThisMonth) {
            return false;
        }

        // Department filter for logged-in students
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user()->student;
            $departments = $event->departments ?? ['All'];

            if (in_array('All', $departments)) {
                return true;
            }

            return in_array($student->program, $departments);
        }

        return true;
    });

    $announcements = \App\Models\Announcement::whereYear('start_date', $currentYear)
        ->whereMonth('start_date', $currentMonth)
        ->where('is_active', true)
        ->where('is_draft', false)
        ->latest()
        ->get();

    return view('welcome', compact('events', 'announcements'));
});

Route::post('/student/events/{event}/join', [\App\Http\Controllers\EventController::class, 'join'])
    ->name('student.events.join')
    ->middleware(['auth:student', 'role:student']);

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth:web,student', 'role_redirect']);

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth Routes
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:admin|employee'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Resources accessible by both admin and employee?
        // Based on seeder: employee can manage students.
        // Let's assume announcements and events are admin only for now.
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->middleware('role:admin');
        Route::resource('events', \App\Http\Controllers\EventController::class)->middleware('role:admin');
        Route::get('/events/{event}/participants', [\App\Http\Controllers\EventController::class, 'participants'])->name('events.participants')->middleware('role:admin');
        Route::resource('speakers', \App\Http\Controllers\SpeakerController::class)->middleware('role:admin');
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->middleware('role:admin');
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware('role:admin');
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class)->middleware('role:admin');

        Route::post('/attendance/scan', [\App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
    });
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\StudentAuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\StudentAuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [\App\Http\Controllers\StudentAuthController::class, 'register'])->name('register');
    Route::post('/register', [\App\Http\Controllers\StudentAuthController::class, 'store'])->name('register.post');
    Route::post('/logout', [\App\Http\Controllers\StudentAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->middleware(['auth:student', 'role:student'])->name('dashboard');

    Route::middleware(['auth:student', 'role:student'])->group(function () {
        Route::get('/profile', [\App\Http\Controllers\StudentController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\StudentController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [\App\Http\Controllers\StudentController::class, 'updatePassword'])->name('password.update');
        Route::get('/events/joined', [\App\Http\Controllers\StudentController::class, 'joinedEvents'])->name('events.joined');
    });
});
