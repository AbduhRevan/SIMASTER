<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PemeliharaanController;

// Super Admin
use App\Http\Controllers\superadmin\ProfilController;
use App\Http\Controllers\superadmin\BidangController;
use App\Http\Controllers\superadmin\SatkerController;
use App\Http\Controllers\superadmin\RakController;
use App\Http\Controllers\superadmin\WebsiteController as SuperAdminWebsite;
use App\Http\Controllers\superadmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\superadmin\ServerController as SuperAdminServer;
use App\Http\Controllers\superadmin\LogAktivitasController;

// Banglola
use App\Http\Controllers\banglola\WebsiteController as BanglolaWebsite;
use App\Http\Controllers\banglola\DashboardController as BanglolaDashboard;
use App\Http\Controllers\banglola\ServerController as BanglolaServer;

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
        Route::get('/dashboard', [SuperadminDashboard::class, 'index'])->name('superadmin.dashboard');

        // ====================================================================
        // DATA MASTER (Hard Delete - Hapus Permanen Langsung)
        // ====================================================================

        // Bidang (Hard Delete)
        Route::get('/bidang', [BidangController::class, 'index'])->name('superadmin.bidang');
        Route::post('/bidang/store', [BidangController::class, 'store'])->name('superadmin.bidang.store');
        Route::put('/bidang/update/{id}', [BidangController::class, 'update'])->name('superadmin.bidang.update');
        Route::delete('/bidang/delete/{id}', [BidangController::class, 'destroy'])->name('superadmin.bidang.delete');

        // Satuan Kerja (Hard Delete)
        Route::get('/satuankerja', [SatkerController::class, 'index'])->name('superadmin.satuankerja');
        Route::post('/satuankerja/store', [SatkerController::class, 'store'])->name('superadmin.satker.store');
        Route::put('/satker/update/{id}', [SatkerController::class, 'update'])->name('superadmin.satker.update');
        Route::delete('/satker/delete/{id}', [SatkerController::class, 'destroy'])->name('superadmin.satker.delete');

        // Rak Server (Hard Delete)
        Route::get('/rakserver', [RakController::class, 'index'])->name('superadmin.rakserver');
        Route::post('/rakserver/store', [RakController::class, 'store'])->name('superadmin.rakserver.store');
        Route::put('/rakserver/update/{id}', [RakController::class, 'update'])->name('superadmin.rakserver.update');
        Route::delete('/rakserver/delete/{id}', [RakController::class, 'destroy'])->name('superadmin.rakserver.delete');

        // ====================================================================
        // MANAJEMEN ASET
        // ====================================================================

        // Server
        Route::get('/server/rak/{rakId}/available-slots', [SuperadminServer::class, 'getAvailableSlots'])->name('superadmin.server.availableSlots');
        Route::get('/server', [SuperadminServer::class, 'index'])->name('superadmin.server.index');
        Route::post('/server/store', [SuperadminServer::class, 'store'])->name('superadmin.server.store');
        Route::get('/server/{id}/detail', [SuperadminServer::class, 'detail'])->name('superadmin.server.detail');
        Route::get('/server/{id}/edit', [SuperadminServer::class, 'edit'])->name('superadmin.server.edit');
        Route::put('/server/update/{id}', [SuperadminServer::class, 'update'])->name('superadmin.server.update');
        Route::delete('/server/{id}', [SuperadminServer::class, 'destroy'])->name('superadmin.server.destroy');

        // Website
        Route::get('/website', [SuperadminWebsite::class, 'index'])->name('superadmin.website.index');
        Route::post('/website/store', [SuperadminWebsite::class, 'store'])->name('superadmin.website.store');
        Route::put('/website/update/{id}', [SuperadminWebsite::class, 'update'])->name('superadmin.website.update');
        Route::delete('/website/delete/{id}', [SuperadminWebsite::class, 'destroy'])->name('superadmin.website.destroy');
        Route::get('/website/{id}/detail', [SuperadminWebsite::class, 'detail'])->name('superadmin.website.detail');
        
        // ====================================================================
        // PEMELIHARAAN
        // ====================================================================

        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('superadmin.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('superadmin.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('superadmin.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('superadmin.pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('superadmin.pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('superadmin.pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('superadmin.pemeliharaan.cancel');

        // ====================================================================
        // SISTEM
        // ====================================================================

        // Pengguna
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

    // ====================================================================
    // BANGLOLA ROLE
    // ====================================================================

    Route::middleware(['role:banglola'])->prefix('banglola')->group(function () {
        Route::get('/dashboard', [BanglolaDashboard::class, 'index'])->name('banglola.dashboard');

        // Banglola - Server
        Route::get('/server/rak/{rakId}/available-slots', [BanglolaServer::class, 'getAvailableSlots'])->name('banglola.server.availableSlots');
        Route::get('/server', [BanglolaServer::class, 'index'])->name('banglola.server.index');
        Route::post('/server/store', [BanglolaServer::class, 'store'])->name('banglola.server.store');
        Route::get('/server/{id}/detail', [BanglolaServer::class, 'detail'])->name('banglola.server.detail');
        Route::get('/server/{id}/edit', [BanglolaServer::class, 'edit'])->name('banglola.server.edit');
        Route::put('/server/update/{id}', [BanglolaServer::class, 'update'])->name('banglola.server.update');
        Route::delete('/server/{id}', [BanglolaServer::class, 'destroy'])->name('banglola.server.destroy');

        // Banglola - Website
        Route::get('/website', [BanglolaWebsite::class, 'index'])->name('banglola.website.index');
        Route::post('/website/store', [BanglolaWebsite::class, 'store'])->name('banglola.website.store');
        Route::put('/website/update/{id}', [BanglolaWebsite::class, 'update'])->name('banglola.website.update');
        Route::delete('/website/delete/{id}', [BanglolaWebsite::class, 'destroy'])->name('banglola.website.destroy');
        Route::get('/website/{id}/detail', [BanglolaWebsite::class, 'detail'])->name('banglola.website.detail');
        
        // Banglola - Log Aktivitas
        Route::get('/log-aktivitas', function () {
            return view('banglola.logAktivitas');})->name('banglola.logAktivitas');
    
        // Banglola - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('banglola.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('banglola.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('banglola.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('banglola.pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('banglola.pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('banglola.pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('.pemeliharaan.cancel');
        });


    // ====================================================================
    // PAMSIS ROLE
    // ====================================================================

    Route::middleware(['role:pamsis'])->prefix('pamsis')->group(function () {
        Route::get('/dashboard', function () {
            return view('pamsis.dashboard');
        })->name('pamsis.dashboard');
    });

    // ====================================================================
    // INFRATIK ROLE
    // ====================================================================

    Route::middleware(['role:infratik'])->prefix('infratik')->group(function () {
        Route::get('/dashboard', function () {
            return view('infratik.dashboard');
        })->name('infratik.dashboard');
    });

    // ====================================================================
    // TATAUSAHA ROLE
    // ====================================================================

    Route::middleware(['role:tatausaha'])->prefix('tatausaha')->group(function () {
        Route::get('/dashboard', function () {
            return view('tatausaha.dashboard');
        })->name('tatausaha.dashboard');
    });

    // ====================================================================
    // PIMPINAN ROLE
    // ====================================================================

    Route::middleware(['role:pimpinan'])->prefix('pimpinan')->group(function () {
        Route::get('/dashboard', function () {
            return view('pimpinan.dashboard');
        })->name('pimpinan.dashboard');
    });
});
