<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\superadmin\BidangController;
use App\Http\Controllers\superadmin\SatkerController;
use App\Http\Controllers\superadmin\RakController;
use App\Http\Controllers\superadmin\WebsiteController;
use App\Http\Controllers\superadmin\DashboardController;
use App\Http\Controllers\superadmin\ServerController;
use App\Http\Controllers\superadmin\LogAktivitasController;

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

    // Dashboard
    Route::get('/superadmin/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');
 // Data Master
 // Bidang
        Route::get('/superadmin/bidang', [BidangController::class, 'index'])->name('superadmin.bidang');
        Route::post('/superadmin/bidang/store', [BidangController::class, 'store'])->name('superadmin.bidang.store');
        Route::put('/superadmin/bidang/update/{id}', [BidangController::class, 'update'])->name('superadmin.bidang.update');
        Route::delete('/bidang/soft-delete/{id}', [BidangController::class, 'softDelete'])->name('superadmin.bidang.softdelete');
 // Satuan Kerja 
        Route::get('/superadmin/satuankerja', [SatkerController::class, 'index'])->name('superadmin.satuankerja');
        Route::post('/superadmin/satuankerja/store', [SatkerController::class, 'store'])->name('superadmin.satker.store');
        Route::put('/superadmin/satker/update/{id}', [SatkerController::class, 'update'])->name('superadmin.satker.update');
        Route::delete('/satker/soft-delete/{id}', [SatkerController::class, 'softDelete'])->name('superadmin.satker.softdelete');


 // Rak Server
        Route::get('/superadmin/rakserver', [RakController::class, 'index'])->name('superadmin.rakserver');
        Route::post('/superadmin/rakserver/store', [RakController::class, 'store'])->name('superadmin.rakserver.store');
        Route::put('/rakserver/update/{id}', [RakController::class, 'update'])->name('superadmin.rakserver.update');
        Route::delete('/rakserver/{id}', [RakController::class, 'destroy'])->name('rakserver.destroy');

// Arsip Sementara
        Route::get('/arsip', [BidangController::class, 'arsip'])->name('superadmin.arsip');
        Route::post('/bidang/restore/{id}', [BidangController::class, 'restore'])->name('superadmin.bidang.restore');
        Route::delete('/bidang/force-delete/{id}', [BidangController::class, 'forceDelete'])->name('superadmin.bidang.forceDelete');

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
    // pengguna
Route::prefix('superadmin')->name('superadmin.')->group(function() {
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
    Route::post('/pengguna/toggle-status/{id}', [PenggunaController::class, 'toggleStatus'])->name('pengguna.toggle-status');
    Route::get('/pengguna/bidang', [PenggunaController::class, 'getBidang'])->name('pengguna.bidang');
});


    Route::get('/logAktivitas', [LogAktivitasController::class, 'index'])->name('superadmin.logAktivitas');

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