<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\SuperAdmin\WebsiteController;

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
        Route::post('/superadmin/bidang/store', [BidangController::class, 'store'])->name('superadmin.bidang.store');
        Route::put('/superadmin/bidang/update/{id}', [BidangController::class, 'update'])->name('superadmin.bidang.update');
        Route::delete('/superadmin/bidang/delete/{id}', [BidangController::class, 'destroy'])->name('superadmin.bidang.destroy');


        Route::get('/superadmin/satuankerja', [SatkerController::class, 'index'])->name('superadmin.satuankerja');
        Route::post('/satuankerja/store', [SatkerController::class, 'store'])->name('satker.store');

        Route::get('/superadmin/rakserver', [RakController::class, 'index'])->name('superadmin.rakserver');
        Route::post('/rakserver/store', [RakController::class, 'store'])->name('rak.store');
       
    //Manajemen Aset
    Route::middleware(['role:superadmin'])->prefix('superadmin')->group(function () {

        // Server
        Route::get('/superadmin/server', [\App\Http\Controllers\Superadmin\ServerController::class, 'index'])
        ->name('superadmin.server.index');

         Route::get('/superadmin/server/{id}', [\App\Http\Controllers\Superadmin\ServerController::class, 'show'])
        ->name('superadmin.server.detail');

         Route::post('/superadmin/server/store', [\App\Http\Controllers\Superadmin\ServerController::class, 'store'])
        ->name('superadmin.server.store');
        });
    
        // Website
        // Routes untuk Website
    Route::prefix('superadmin')->name('superadmin.')->middleware(['auth'])->group(function () {
    Route::get('/website', [WebsiteController::class, 'index'])->name('website.index');
    Route::post('/website/store', [WebsiteController::class, 'store'])->name('website.store');
    Route::put('/website/update/{id}', [WebsiteController::class, 'update'])->name('website.update');
    Route::delete('/website/delete/{id}', [WebsiteController::class, 'destroy'])->name('website.destroy');
    Route::get('/website/{id}/detail', [WebsiteController::class, 'detail'])->name('website.detail');
});
    Route::get('/website', function () {
        return view('superadmin.website');
    })->name('superadmin.website');
    
    Route::get('/pemeliharaan', function () {
        return view('superadmin.pemeliharaan');
    })->name('superadmin.pemeliharaan');
    
    //Sistem
    Route::prefix('superadmin')->name('superadmin.')->middleware(['auth'])->group(function () {
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna/store', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/bidang', [PenggunaController::class, 'getBidang'])->name('pengguna.bidang');
    Route::put('/pengguna/update/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/delete/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
    Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::post('/pengguna/{id}/toggle-status', [PenggunaController::class, 'toggleStatus'])->name('pengguna.toggleStatus');
});


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