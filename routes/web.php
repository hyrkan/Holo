<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::guard('student')->check()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('student.login');
});

Route::post('/student/events/{event}/join', [\App\Http\Controllers\EventController::class, 'join'])
    ->name('student.events.join')
    ->middleware(['auth:student', 'role:student']);

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth:web,student', 'role_redirect']);

// Lost and Found Routes
Route::get('/lost-and-found', [\App\Http\Controllers\LostAndFoundController::class, 'index'])->name('lost-and-found.index');
Route::get('/lost-and-found/create', function () {
    return view('lost-and-found.create');
})->name('lost-and-found.create');
Route::post('/lost-and-found', [\App\Http\Controllers\LostAndFoundController::class, 'store'])->name('lost-and-found.store');
Route::get('/lost-and-found/{lostAndFound}', [\App\Http\Controllers\LostAndFoundController::class, 'show'])->name('lost-and-found.show');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth Routes
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:admin|employee'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

        // Resources accessible by both admin and employee?
        // Based on seeder: employee can manage students.
        // Let's assume announcements and events are admin only for now.
        Route::post('students/{student}/approve', [\App\Http\Controllers\StudentController::class, 'approve'])->name('students.approve');
        Route::post('students/{student}/deny', [\App\Http\Controllers\StudentController::class, 'deny'])->name('students.deny');
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->middleware('role:admin');
        Route::resource('events', \App\Http\Controllers\EventController::class)->middleware('role:admin');
        Route::get('/events/{event}/participants', [\App\Http\Controllers\EventController::class, 'participants'])->name('events.participants')->middleware('role:admin');
        Route::get('/events/{event}/attendance', [\App\Http\Controllers\EventController::class, 'attendance'])->name('events.attendance')->middleware('role:admin');
        Route::get('/events/{event}/certificates', [\App\Http\Controllers\CertificateController::class, 'index'])->name('events.certificates.index')->middleware('role:admin');
        Route::get('/events/{event}/certificates/create', [\App\Http\Controllers\CertificateController::class, 'create'])->name('events.certificates.create')->middleware('role:admin');
        Route::post('/events/{event}/certificates/store', [\App\Http\Controllers\CertificateController::class, 'store'])->name('events.certificates.store')->middleware('role:admin');
        Route::get('/events/{event}/certificates/{certificate}/edit', [\App\Http\Controllers\CertificateController::class, 'edit'])->name('events.certificates.edit')->middleware('role:admin');
        Route::post('/events/{event}/certificates/{certificate}/update', [\App\Http\Controllers\CertificateController::class, 'update'])->name('events.certificates.update')->middleware('role:admin');
        Route::delete('/events/{event}/certificates/{certificate}', [\App\Http\Controllers\CertificateController::class, 'destroy'])->name('events.certificates.destroy')->middleware('role:admin');
        Route::get('/certificates/{certificate}/preview', [\App\Http\Controllers\CertificateController::class, 'preview'])->name('events.certificate.preview')->middleware('role:admin');
        Route::post('/events/{event}/certificate/update-eligibility/{student}', [\App\Http\Controllers\CertificateController::class, 'updateEligibility'])->name('events.certificate.update-eligibility')->middleware('role:admin');
        Route::post('/events/{event}/certificate/bulk', [\App\Http\Controllers\CertificateController::class, 'bulkEligibility'])->name('events.certificate.bulk')->middleware('role:admin');
        Route::get('/speakers/{speaker}/events', [\App\Http\Controllers\SpeakerController::class, 'events'])->name('speakers.events')->middleware('role:admin');
        Route::resource('speakers', \App\Http\Controllers\SpeakerController::class)->middleware('role:admin');
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->middleware('role:admin');
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware('role:admin');
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class)->middleware('role:admin');

        Route::post('/attendance/scan', [\App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::get('/attendance/scanner', [\App\Http\Controllers\AttendanceController::class, 'showScanner'])->name('attendance.scanner');

        // Admin Lost and Found
        Route::get('/lost-and-found', [\App\Http\Controllers\LostAndFoundController::class, 'adminIndex'])->name('lost-and-found.index');
        Route::get('/lost-and-found/create', [\App\Http\Controllers\LostAndFoundController::class, 'adminCreate'])->name('lost-and-found.create');
        Route::post('/lost-and-found/create', [\App\Http\Controllers\LostAndFoundController::class, 'adminStore'])->name('lost-and-found.admin-store');
        Route::get('/lost-and-found/{lost_and_found}', [\App\Http\Controllers\LostAndFoundController::class, 'adminShow'])->name('lost-and-found.show');
        Route::get('/lost-and-found/{lost_and_found}/resolve', [\App\Http\Controllers\LostAndFoundController::class, 'resolve'])->name('lost-and-found.resolve');
        Route::post('/lost-and-found/{lost_and_found}/resolve', [\App\Http\Controllers\LostAndFoundController::class, 'storeResolution'])->name('lost-and-found.store-resolution');
        Route::delete('/lost-and-found/{lost_and_found}', [\App\Http\Controllers\LostAndFoundController::class, 'destroy'])->name('lost-and-found.destroy');
        

        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\StudentAuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\StudentAuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [\App\Http\Controllers\StudentAuthController::class, 'register'])->name('register');
    Route::post('/register', [\App\Http\Controllers\StudentAuthController::class, 'store'])->name('register.post');
    Route::post('/logout', [\App\Http\Controllers\StudentAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->middleware(['auth:student', 'role:student'])->name('dashboard');

    Route::middleware(['auth:student', 'role:student'])->group(function () {
        Route::get('/profile', [\App\Http\Controllers\StudentController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\StudentController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [\App\Http\Controllers\StudentController::class, 'updatePassword'])->name('password.update');
        Route::get('/events/joined', [\App\Http\Controllers\StudentController::class, 'joinedEvents'])->name('events.joined');
        Route::get('/lost-and-found/my-reports', [\App\Http\Controllers\StudentController::class, 'myLostAndFoundReports'])->name('lost-and-found.my-reports');
        Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('events.certificate.download');
    });
});
