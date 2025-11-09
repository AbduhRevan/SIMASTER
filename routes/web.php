<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\RakController;

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

 // Data Master
        Route::get('/superadmin/bidang', [BidangController::class, 'index'])->name('superadmin.bidang');
        Route::post('/bidang/store', [BidangController::class, 'store'])->name('bidang.store');

        Route::get('/superadmin/satuankerja', [SatkerController::class, 'index'])->name('superadmin.satuankerja');
        Route::post('/satuankerja/store', [SatkerController::class, 'store'])->name('satker.store');

        Route::get('/superadmin/rakserver', [RakController::class, 'index'])->name('superadmin.rakserver');
        Route::post('/rakserver/store', [RakController::class, 'store'])->name('rak.store');
       
    //Manajemen Aset
    Route::get('/server', function () {
        return view('superadmin.server');
    })->name('superadmin.server');
    
    Route::get('/website', function () {
        return view('superadmin.website');
    })->name('superadmin.website');
    
    Route::get('/pemeliharaan', function () {
        return view('superadmin.pemeliharaan');
    })->name('superadmin.pemeliharaan');
    
    //Sistem
    Route::get('/kelola-pengguna', function () {
        return view('superadmin.kelola-pengguna');
    })->name('superadmin.kelola-pengguna');

    Route::get('/logAktivitas', function () {
        return view('superadmin.logAktivitas');
    })->name('superadmin.logAktivitas');

    //Pengaturan
    Route::get('/pengaturan', function () {
        return view('pengaturan');
    })->name('pengaturan');
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