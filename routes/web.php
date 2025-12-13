<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
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

    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\StudentAuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\StudentAuthController::class, 'authenticate'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\StudentAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->middleware('auth:student')->name('dashboard');
});
