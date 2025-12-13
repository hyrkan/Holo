<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Dashboard Route
// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
});

// Login Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
