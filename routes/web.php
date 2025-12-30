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

    $events = \App\Models\Event::with('speakers')->get()->filter(function ($event) use ($currentMonth, $currentYear) {
        foreach ($event->dates as $date) {
            $carbonDate = \Carbon\Carbon::parse($date);
            if ($carbonDate->month == $currentMonth && $carbonDate->year == $currentYear) {
                return true;
            }
        }

        return false;
    });

    $announcements = \App\Models\Announcement::whereYear('start_date', $currentYear)
        ->whereMonth('start_date', $currentMonth)
        ->where('is_active', true)
        ->where('is_draft', false)
        ->latest()
        ->get();

    return view('welcome', compact('events', 'announcements'));
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth Routes
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('auth')->name('dashboard');

    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::resource('events', \App\Http\Controllers\EventController::class);
    Route::resource('speakers', \App\Http\Controllers\SpeakerController::class);
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
    })->middleware('auth:student')->name('dashboard');
});
