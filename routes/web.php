<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\superadmin\ProfilController;
use App\Http\Controllers\superadmin\BidangController;
use App\Http\Controllers\superadmin\SatkerController;
use App\Http\Controllers\superadmin\RakController;
use App\Http\Controllers\superadmin\WebsiteController;
use App\Http\Controllers\superadmin\DashboardController;
use App\Http\Controllers\superadmin\ServerController;
use App\Http\Controllers\superadmin\LogAktivitasController;
use App\Http\Controllers\PemeliharaanController;

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

    // Profil, Password, Panduan Pengguna
    Route::get('/profil-saya', [ProfilController::class, 'profilSaya'])->name('profil.saya');
    Route::get('/ganti-password', [ProfilController::class, 'gantiPassword'])->name('ganti.password');
    Route::post('/ganti-password', [ProfilController::class, 'updatePassword'])->name('ganti.password.post');
    Route::get('/panduan-pengguna', [ProfilController::class, 'panduanPengguna'])->name('panduan.pengguna');

    // Superadmin
    Route::middleware(['role:superadmin'])->prefix('superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');

        // Data Master
        // Bidang
        Route::get('/bidang', [BidangController::class, 'index'])->name('superadmin.bidang');
        Route::post('/bidang/store', [BidangController::class, 'store'])->name('superadmin.bidang.store');
        Route::put('/bidang/update/{id}', [BidangController::class, 'update'])->name('superadmin.bidang.update');
        Route::delete('/bidang/soft-delete/{id}', [BidangController::class, 'softDelete'])->name('superadmin.bidang.softdelete');

        // Satuan Kerja 
        Route::get('/satuankerja', [SatkerController::class, 'index'])->name('superadmin.satuankerja');
        Route::post('/satuankerja/store', [SatkerController::class, 'store'])->name('superadmin.satker.store');
        Route::put('/satker/update/{id}', [SatkerController::class, 'update'])->name('superadmin.satker.update');
        Route::delete('/satker/soft-delete/{id}', [SatkerController::class, 'softDelete'])->name('superadmin.satker.softdelete');

        // Rak Server
        Route::get('/rakserver', [RakController::class, 'index'])->name('superadmin.rakserver');
        Route::post('/rakserver/store', [RakController::class, 'store'])->name('superadmin.rakserver.store');
        Route::put('/rakserver/update/{id}', [RakController::class, 'update'])->name('superadmin.rakserver.update');
        Route::delete('/rakserver/delete/{id}', [RakController::class, 'destroy'])->name('superadmin.rakserver.delete');

        // Arsip Sementara
        Route::get('/arsip', [BidangController::class, 'arsip'])->name('superadmin.arsip');
        Route::post('/bidang/restore/{id}', [BidangController::class, 'restore'])->name('superadmin.bidang.restore');
        Route::delete('/bidang/force-delete/{id}', [BidangController::class, 'forceDelete'])->name('superadmin.bidang.forceDelete');

        // Manajemen Aset - Server
        // ⚠️ PENTING: Route dengan parameter spesifik HARUS di atas route dengan parameter dinamis
        Route::get('/server/rak/{rakId}/available-slots', [ServerController::class, 'getAvailableSlots'])->name('superadmin.server.availableSlots');

        Route::get('/server', [ServerController::class, 'index'])->name('superadmin.server.index');
        Route::post('/server/store', [ServerController::class, 'store'])->name('superadmin.server.store');
        Route::get('/server/{id}/detail', [ServerController::class, 'detail'])->name('superadmin.server.detail');
        Route::get('/server/{id}/edit', [ServerController::class, 'edit'])->name('superadmin.server.edit');
        Route::put('/server/update/{id}', [ServerController::class, 'update'])->name('superadmin.server.update');
        Route::delete('/server/{id}', [ServerController::class, 'destroy'])->name('superadmin.server.destroy');

        // Website
        Route::get('/website', [WebsiteController::class, 'index'])->name('superadmin.website.index');
        Route::post('/website/store', [WebsiteController::class, 'store'])->name('superadmin.website.store');
        Route::put('/website/update/{id}', [WebsiteController::class, 'update'])->name('superadmin.website.update');
        Route::delete('/website/delete/{id}', [WebsiteController::class, 'destroy'])->name('superadmin.website.destroy');
        Route::get('/website/{id}/detail', [WebsiteController::class, 'detail'])->name('superadmin.website.detail');

        // Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('superadmin.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('superadmin.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('superadmin.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('superadmin.pemeliharaan.destroy');

        // Sistem - Pengguna
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('superadmin.pengguna.index');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('superadmin.pengguna.store');
        Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('superadmin.pengguna.edit');
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('superadmin.pengguna.update');
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('superadmin.pengguna.destroy');
        Route::post('/pengguna/{id}/toggle-status', [PenggunaController::class, 'toggleStatus'])->name('superadmin.pengguna.toggle-status');
        Route::get('/pengguna/bidang', [PenggunaController::class, 'getBidang'])->name('superadmin.pengguna.bidang');

        // Log Aktivitas
        Route::get('/logAktivitas', [LogAktivitasController::class, 'index'])->name('superadmin.logAktivitas');
    });

    // Banglola
    Route::middleware(['role:banglola'])->prefix('banglola')->group(function () {
        Route::get('/dashboard', function () {
            return view('banglola.dashboard');
        })->name('banglola.dashboard');

        // Banglola - Server
        Route::get('/server', [\App\Http\Controllers\Banglola\ServerController::class, 'index'])->name('banglola.server.index');

        // Banglola - Website
        Route::get('/website', [\App\Http\Controllers\Banglola\WebsiteController::class, 'index'])->name('banglola.website.index');

        // Banglola - Log Aktivitas
        Route::get('/log-aktivitas', function () {
            return view('banglola.logAktivitas');})->name('banglola.logAktivitas');
    
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('superadmin.pemeliharaan');
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
