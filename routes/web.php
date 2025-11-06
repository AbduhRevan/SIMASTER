<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes dengan middleware auth dan role
Route::middleware(['auth'])->group(function () {
    
    // Superadmin
    Route::middleware(['role:superadmin'])->prefix('superadmin')->group(function () {
        Route::get('/dashboard', function () {
            return view('superadmin.dashboard');
        })->name('superadmin.dashboard');
    });

    // Banglola
    Route::middleware(['role:banglola'])->prefix('banglola')->group(function () {
        Route::get('/dashboard', function () {
            return view('banglola.dashboard');
        })->name('banglola.dashboard');
    });

    // Pamsis
    Route::middleware(['role:pamsis'])->prefix('pamsis')->group(function () {
        Route::get('/dashboard', function () {
            return view('pamsis.dashboard');
        })->name('pamsis.dashboard');
    });

    // Infratik
    Route::middleware(['role:infratik'])->prefix('infratik')->group(function () {
        Route::get('/dashboard', function () {
            return view('infratik.dashboard');
        })->name('infratik.dashboard');
    });

    // Tatausaha
    Route::middleware(['role:tatausaha'])->prefix('tatausaha')->group(function () {
        Route::get('/dashboard', function () {
            return view('tatausaha.dashboard');
        })->name('tatausaha.dashboard');
    });

    // Pimpinan
    Route::middleware(['role:pimpinan'])->prefix('pimpinan')->group(function () {
        Route::get('/dashboard', function () {
            return view('pimpinan.dashboard');
        })->name('pimpinan.dashboard');
    });
});