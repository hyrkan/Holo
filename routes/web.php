<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingPageController;
use Gemini\Laravel\Facades\Gemini;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('welcome');

// Default login route alias to satisfy auth middleware redirects
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

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

    // Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\AdminPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\AdminPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\AdminPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\AdminPasswordController::class, 'reset'])->name('password.store');

    Route::middleware(['auth', 'role:admin|employee'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

        // Resources accessible by both admin and employee?
        // Based on seeder: employee can manage students.
        // Let's assume announcements and events are admin only for now.
        Route::get('students/export', [\App\Http\Controllers\StudentController::class, 'exportCsv'])->name('students.export');
        Route::post('students/{student}/approve', [\App\Http\Controllers\StudentController::class, 'approve'])->name('students.approve');
        Route::post('students/{student}/deny', [\App\Http\Controllers\StudentController::class, 'deny'])->name('students.deny');
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::get('announcements/export', [\App\Http\Controllers\AnnouncementController::class, 'exportCsv'])->name('announcements.export')->middleware('role:admin');
        Route::get('announcements/archived', [\App\Http\Controllers\AnnouncementController::class, 'archived'])->name('announcements.archived')->middleware('role:admin');
        Route::post('announcements/{announcement}/restore', [\App\Http\Controllers\AnnouncementController::class, 'restore'])->name('announcements.restore')->middleware('permission:manage announcements');
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->middleware('permission:manage announcements');
        Route::delete('announcements/attachments/{attachment}', [\App\Http\Controllers\AnnouncementController::class, 'deleteAttachment'])->name('announcements.attachments.destroy')->middleware('permission:manage announcements');
        Route::get('events/export', [\App\Http\Controllers\EventController::class, 'exportCsv'])->name('events.export')->middleware('permission:manage events');
        Route::resource('events', \App\Http\Controllers\EventController::class)->middleware('permission:manage events');
        Route::get('/events/{event}/participants', [\App\Http\Controllers\EventController::class, 'participants'])->name('events.participants')->middleware('permission:manage events');
        Route::get('/events/{event}/attendance', [\App\Http\Controllers\EventController::class, 'attendance'])->name('events.attendance')->middleware('permission:manage events');
        Route::get('/events/{event}/certificates', [\App\Http\Controllers\CertificateController::class, 'index'])->name('events.certificates.index')->middleware('permission:manage events');
        Route::get('/events/{event}/certificates/create', [\App\Http\Controllers\CertificateController::class, 'create'])->name('events.certificates.create')->middleware('permission:manage events');
        Route::post('/events/{event}/certificates/store', [\App\Http\Controllers\CertificateController::class, 'store'])->name('events.certificates.store')->middleware('permission:manage events');
        Route::get('/events/{event}/certificates/{certificate}/edit', [\App\Http\Controllers\CertificateController::class, 'edit'])->name('events.certificates.edit')->middleware('permission:manage events');
        Route::post('/events/{event}/certificates/{certificate}/update', [\App\Http\Controllers\CertificateController::class, 'update'])->name('events.certificates.update')->middleware('permission:manage events');
        Route::delete('/events/{event}/certificates/{certificate}', [\App\Http\Controllers\CertificateController::class, 'destroy'])->name('events.certificates.destroy')->middleware('permission:manage events');
        Route::get('/certificates/{certificate}/preview', [\App\Http\Controllers\CertificateController::class, 'preview'])->name('events.certificate.preview')->middleware('permission:manage events');
        Route::post('/events/{event}/certificate/update-eligibility/{student}', [\App\Http\Controllers\CertificateController::class, 'updateEligibility'])->name('events.certificate.update-eligibility')->middleware('permission:manage events');
        Route::post('/events/{event}/certificate/bulk', [\App\Http\Controllers\CertificateController::class, 'bulkEligibility'])->name('events.certificate.bulk')->middleware('permission:manage events');
        Route::get('/speakers/{speaker}/events', [\App\Http\Controllers\SpeakerController::class, 'events'])->name('speakers.events')->middleware('permission:manage speakers');
        Route::resource('speakers', \App\Http\Controllers\SpeakerController::class)->middleware('permission:manage speakers');
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->middleware('permission:manage employees');
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware('role:admin');
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class)->middleware('role:admin');

        Route::post('/attendance/scan', [\App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::get('/attendance/scanner', [\App\Http\Controllers\AttendanceController::class, 'showScanner'])->name('attendance.scanner');

        Route::get('/programs', [\App\Http\Controllers\ProgramController::class, 'index'])->name('programs.index');
        Route::post('/programs', [\App\Http\Controllers\ProgramController::class, 'store'])->name('programs.store');
        Route::post('/programs/{program}', [\App\Http\Controllers\ProgramController::class, 'update'])->name('programs.update');
        Route::post('/programs/{program}/archive', [\App\Http\Controllers\ProgramController::class, 'archive'])->name('programs.archive');
        Route::post('/programs/{program}/restore', [\App\Http\Controllers\ProgramController::class, 'restore'])->name('programs.restore');

        Route::get('/enrollment-statuses', [\App\Http\Controllers\EnrollmentStatusController::class, 'index'])->name('enrollment-statuses.index');
        Route::post('/enrollment-statuses', [\App\Http\Controllers\EnrollmentStatusController::class, 'store'])->name('enrollment-statuses.store');
        Route::post('/enrollment-statuses/{enrollmentStatus}', [\App\Http\Controllers\EnrollmentStatusController::class, 'update'])->name('enrollment-statuses.update');
        Route::post('/enrollment-statuses/{enrollmentStatus}/archive', [\App\Http\Controllers\EnrollmentStatusController::class, 'archive'])->name('enrollment-statuses.archive');
        Route::post('/enrollment-statuses/{enrollmentStatus}/restore', [\App\Http\Controllers\EnrollmentStatusController::class, 'restore'])->name('enrollment-statuses.restore');

        Route::get('/classifications', [\App\Http\Controllers\ClassificationController::class, 'index'])->name('classifications.index');
        Route::post('/classifications', [\App\Http\Controllers\ClassificationController::class, 'store'])->name('classifications.store');
        Route::post('/classifications/{classification}', [\App\Http\Controllers\ClassificationController::class, 'update'])->name('classifications.update');
        Route::post('/classifications/{classification}/archive', [\App\Http\Controllers\ClassificationController::class, 'archive'])->name('classifications.archive');
        Route::post('/classifications/{classification}/restore', [\App\Http\Controllers\ClassificationController::class, 'restore'])->name('classifications.restore');

        // Admin Lost and Found
        Route::get('/lost-and-found', [\App\Http\Controllers\LostAndFoundController::class, 'adminIndex'])->name('lost-and-found.index');
        Route::get('/lost-and-found/create', [\App\Http\Controllers\LostAndFoundController::class, 'adminCreate'])->name('lost-and-found.create');
        Route::post('/lost-and-found/create', [\App\Http\Controllers\LostAndFoundController::class, 'adminStore'])->name('lost-and-found.admin-store');
        Route::get('/lost-and-found/{lost_and_found}', [\App\Http\Controllers\LostAndFoundController::class, 'adminShow'])->name('lost-and-found.show');
        Route::post('/lost-and-found/{lost_and_found}/approve', [\App\Http\Controllers\LostAndFoundController::class, 'adminApprove'])->name('lost-and-found.approve');
        Route::get('/lost-and-found/{lost_and_found}/resolve', [\App\Http\Controllers\LostAndFoundController::class, 'resolve'])->name('lost-and-found.resolve');
        Route::post('/lost-and-found/{lost_and_found}/resolve', [\App\Http\Controllers\LostAndFoundController::class, 'storeResolution'])->name('lost-and-found.store-resolution');
        Route::get('/lost-and-found/{lost_and_found}/edit', [\App\Http\Controllers\LostAndFoundController::class, 'adminEdit'])->name('lost-and-found.edit');
        Route::put('/lost-and-found/{lost_and_found}', [\App\Http\Controllers\LostAndFoundController::class, 'adminUpdate'])->name('lost-and-found.update');
        Route::delete('/lost-and-found/{lost_and_found}', [\App\Http\Controllers\LostAndFoundController::class, 'destroy'])->name('lost-and-found.destroy');


        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

        // Default Certificate Settings
        Route::prefix('settings/certificates')->name('settings.certificates.')->group(function () {
            Route::get('/', [\App\Http\Controllers\DefaultCertificateController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\DefaultCertificateController::class, 'create'])->name('create');
            Route::post('/store', [\App\Http\Controllers\DefaultCertificateController::class, 'store'])->name('store');
            Route::get('/{certificate}/edit', [\App\Http\Controllers\DefaultCertificateController::class, 'edit'])->name('edit');
            Route::post('/{certificate}/update', [\App\Http\Controllers\DefaultCertificateController::class, 'update'])->name('update');
            Route::delete('/{certificate}', [\App\Http\Controllers\DefaultCertificateController::class, 'destroy'])->name('destroy');
            Route::post('/{certificate}/select', [\App\Http\Controllers\DefaultCertificateController::class, 'select'])->name('select');
            Route::get('/{certificate}/preview', [\App\Http\Controllers\DefaultCertificateController::class, 'preview'])->name('preview');
        });
    });
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\StudentAuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\StudentAuthController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [\App\Http\Controllers\StudentAuthController::class, 'register'])->name('register');
    Route::post('/register', [\App\Http\Controllers\StudentAuthController::class, 'store'])->name('register.post');
    Route::post('/logout', [\App\Http\Controllers\StudentAuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [\App\Http\Controllers\StudentPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\StudentPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\StudentPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\StudentPasswordController::class, 'reset'])->name('password.store');

    Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->middleware(['auth:student', 'role:student'])->name('dashboard');

    Route::middleware(['auth:student', 'role:student'])->group(function () {
        Route::get('/profile', [\App\Http\Controllers\StudentController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\StudentController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [\App\Http\Controllers\StudentController::class, 'updatePassword'])->name('password.update');
        Route::get('/events/joined', [\App\Http\Controllers\StudentController::class, 'joinedEvents'])->name('events.joined');
        Route::get('/lost-and-found/my-reports', [\App\Http\Controllers\StudentController::class, 'myLostAndFoundReports'])->name('lost-and-found.my-reports');
        Route::get('/lost-and-found/{lost_and_found}/edit', [\App\Http\Controllers\LostAndFoundController::class, 'studentEdit'])->name('lost-and-found.edit');
        Route::put('/lost-and-found/{lost_and_found}', [\App\Http\Controllers\LostAndFoundController::class, 'studentUpdate'])->name('lost-and-found.update');
        Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('events.certificate.download');
        Route::get('/qr/{uuid}', [\App\Http\Controllers\StudentController::class, 'generateQr'])->name('qr.generate');
    });
});

Route::post('/student/verify-id', [\App\Http\Controllers\StudentAuthController::class, 'verifyId'])->name('student.verify-id');

Route::get('/test-gemini', function () {
    // These showed up in your specific list!
    $modelsToTry = ['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash'];
    $errors = [];

    foreach ($modelsToTry as $modelName) {
        try {
            $result = Gemini::generativeModel(model: $modelName)
                ->generateContent('Hello! Give me a fun fact about Laravel.');

            return "Success with model [$modelName]: " . $result->text();
        } catch (\Exception $e) {
            $errors[] = "Model [$modelName] failed: " . $e->getMessage();
        }
    }

    return "All models failed: " . implode(' | ', $errors);
});

// Public Certificate Verification
Route::get('/verify/certificate/{token}', [\App\Http\Controllers\CertificateVerificationController::class, 'verify'])
    ->name('certificate.verify');
