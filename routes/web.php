<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\superadmin\PanduanController;

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
use App\Http\Controllers\banglola\LogAktivitasController as BanglolaLogAktivitas;

// Infratik
use App\Http\Controllers\infratik\WebsiteController as InfratikWebsite;
use App\Http\Controllers\infratik\DashboardController as InfratikDashboard;
use App\Http\Controllers\infratik\ServerController as InfratikServer;
use App\Http\Controllers\infratik\LogAktivitasController as InfratikLogAktivitas;

// Pamsis
use App\Http\Controllers\pamsis\WebsiteController as PamsisWebsite;
use App\Http\Controllers\pamsis\DashboardController as PamsisDashboard;
use App\Http\Controllers\pamsis\ServerController as PamsisServer;
use App\Http\Controllers\pamsis\LogAktivitasController as PamsisLogAktivitas;

// Tata Usaha
use App\Http\Controllers\tatausaha\WebsiteController as TataUsahaWebsite;
use App\Http\Controllers\tatausaha\DashboardController as TataUsahaDashboard;
use App\Http\Controllers\tatausaha\ServerController as TataUsahaServer;
use App\Http\Controllers\tatausaha\PemeliharaanController as TataUsahaPemeliharaan;
use App\Http\Controllers\tatausaha\LogAktivitasController as TataUsahaLogAktivitas;

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
    Route::get('/panduan-pengguna/{category?}', [PanduanController::class, 'index'])->name('panduan.pengguna');

    // ====================================================================
    // SUPERADMIN ROLE
    // ====================================================================
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
        // MANAJEMEN ASET - SERVER
        // ====================================================================
        // PENTING: Route spesifik (rak/available-slots) HARUS DI ATAS route dengan parameter {id}
        Route::get('/server', [SuperadminServer::class, 'index'])->name('superadmin.server.index');
        Route::get('/server/rak/{rakId}/available-slots', [SuperadminServer::class, 'getAvailableSlots'])->name('superadmin.server.availableSlots');
        Route::post('/server/store', [SuperadminServer::class, 'store'])->name('superadmin.server.store');
        Route::get('/server/{id}/detail', [SuperadminServer::class, 'detail'])->name('superadmin.server.detail');
        Route::get('/server/{id}/edit', [SuperadminServer::class, 'edit'])->name('superadmin.server.edit');
        Route::put('/server/update/{id}', [SuperadminServer::class, 'update'])->name('superadmin.server.update');
        Route::delete('/server/{id}', [SuperadminServer::class, 'destroy'])->name('superadmin.server.destroy');

        // ====================================================================
        // MANAJEMEN ASET - WEBSITE
        // ====================================================================
        Route::get('/website', [SuperadminWebsite::class, 'index'])->name('superadmin.website.index');
        Route::post('/website/store', [SuperadminWebsite::class, 'store'])->name('superadmin.website.store');
        Route::get('/website/{id}/detail', [SuperadminWebsite::class, 'detail'])->name('superadmin.website.detail');
        Route::put('/website/update/{id}', [SuperadminWebsite::class, 'update'])->name('superadmin.website.update');
        Route::delete('/website/delete/{id}', [SuperadminWebsite::class, 'destroy'])->name('superadmin.website.destroy');

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
        Route::get('/pengguna/bidang', [PenggunaController::class, 'getBidang'])->name('superadmin.pengguna.bidang');
        Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('superadmin.pengguna.edit');
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('superadmin.pengguna.update');
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('superadmin.pengguna.destroy');
        Route::post('/pengguna/{id}/toggle-status', [PenggunaController::class, 'toggleStatus'])->name('superadmin.pengguna.toggle-status');

        // Log Aktivitas
        Route::get('/logAktivitas', [LogAktivitasController::class, 'index'])->name('superadmin.logAktivitas');
    });

    // ====================================================================
    // BANGLOLA ROLE
    // ====================================================================
    Route::middleware(['role:banglola'])->prefix('banglola')->group(function () {
        Route::get('/dashboard', [BanglolaDashboard::class, 'index'])->name('banglola.dashboard');

        // Banglola - Server
        Route::get('/server', [BanglolaServer::class, 'index'])->name('banglola.server.index');
        Route::get('/server/rak/{rakId}/available-slots', [BanglolaServer::class, 'getAvailableSlots'])->name('banglola.server.availableSlots');
        Route::post('/server/store', [BanglolaServer::class, 'store'])->name('banglola.server.store');
        Route::get('/server/{id}/detail', [BanglolaServer::class, 'detail'])->name('banglola.server.detail');
        Route::get('/server/{id}/edit', [BanglolaServer::class, 'edit'])->name('banglola.server.edit');
        Route::put('/server/update/{id}', [BanglolaServer::class, 'update'])->name('banglola.server.update');
        Route::delete('/server/{id}', [BanglolaServer::class, 'destroy'])->name('banglola.server.destroy');

        // Banglola - Website
        Route::get('/website', [BanglolaWebsite::class, 'index'])->name('banglola.website.index');
        Route::post('/website/store', [BanglolaWebsite::class, 'store'])->name('banglola.website.store');
        Route::get('/website/{id}/detail', [BanglolaWebsite::class, 'detail'])->name('banglola.website.detail');
        Route::put('/website/update/{id}', [BanglolaWebsite::class, 'update'])->name('banglola.website.update');
        Route::delete('/website/delete/{id}', [BanglolaWebsite::class, 'destroy'])->name('banglola.website.destroy');

        // Banglola - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('banglola.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('banglola.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('banglola.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('banglola.pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('banglola.pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('banglola.pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('banglola.pemeliharaan.cancel');

        // Banglola - Log Aktivitas
        Route::get('/banglola/log-aktivitas', [\App\Http\Controllers\banglola\LogAktivitasController::class, 'index'])->name('banglola.logAktivitas');
    });

    // ====================================================================
    // PAMSIS ROLE
    // ====================================================================
    Route::middleware(['role:pamsis'])->prefix('pamsis')->group(function () {
        Route::get('/dashboard', [PamsisDashboard::class, 'index'])->name('pamsis.dashboard');

        // Pamsis - Server
        Route::get('/server', [PamsisServer::class, 'index'])->name('pamsis.server.index');
        Route::get('/server/rak/{rakId}/available-slots', [PamsisServer::class, 'getAvailableSlots'])->name('pamsis.server.availableSlots');
        Route::post('/server/store', [PamsisServer::class, 'store'])->name('pamsis.server.store');
        Route::get('/server/{id}/detail', [PamsisServer::class, 'detail'])->name('pamsis.server.detail');
        Route::get('/server/{id}/edit', [PamsisServer::class, 'edit'])->name('pamsis.server.edit');
        Route::put('/server/update/{id}', [PamsisServer::class, 'update'])->name('pamsis.server.update');
        Route::delete('/server/{id}', [PamsisServer::class, 'destroy'])->name('pamsis.server.destroy');

        // Pamsis - Website
        Route::get('/website', [PamsisWebsite::class, 'index'])->name('pamsis.website.index');
        Route::post('/website/store', [PamsisWebsite::class, 'store'])->name('pamsis.website.store');
        Route::get('/website/{id}/detail', [PamsisWebsite::class, 'detail'])->name('pamsis.website.detail');
        Route::put('/website/update/{id}', [PamsisWebsite::class, 'update'])->name('pamsis.website.update');
        Route::delete('/website/delete/{id}', [PamsisWebsite::class, 'destroy'])->name('pamsis.website.destroy');

        // Pamsis - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pamsis.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pamsis.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('pamsis.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('pamsis.pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('pamsis.pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('pamsis.pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('pamsis.pemeliharaan.cancel');

        // Pamsis - Log Aktivitas
        Route::get('/pamsis/log-aktivitas', [\App\Http\Controllers\pamsis\LogAktivitasController::class, 'index'])->name('pamsis.logAktivitas');
    });

    // ====================================================================
    // INFRATIK ROLE
    // ====================================================================
    Route::middleware(['role:infratik'])->prefix('infratik')->group(function () {
        Route::get('/dashboard', [InfratikDashboard::class, 'index'])->name('infratik.dashboard');

        // Infratik - Server
        Route::get('/server', [InfratikServer::class, 'index'])->name('infratik.server.index');
        Route::get('/server/rak/{rakId}/available-slots', [InfratikServer::class, 'getAvailableSlots'])->name('infratik.server.availableSlots');
        Route::post('/server/store', [InfratikServer::class, 'store'])->name('infratik.server.store');
        Route::get('/server/{id}/detail', [InfratikServer::class, 'detail'])->name('infratik.server.detail');
        Route::get('/server/{id}/edit', [InfratikServer::class, 'edit'])->name('infratik.server.edit');
        Route::put('/server/update/{id}', [InfratikServer::class, 'update'])->name('infratik.server.update');
        Route::delete('/server/{id}', [InfratikServer::class, 'destroy'])->name('infratik.server.destroy');

        // Infratik - Website
        Route::get('/website', [InfratikWebsite::class, 'index'])->name('infratik.website.index');
        Route::post('/website/store', [InfratikWebsite::class, 'store'])->name('infratik.website.store');
        Route::get('/website/{id}/detail', [InfratikWebsite::class, 'detail'])->name('infratik.website.detail');
        Route::put('/website/update/{id}', [InfratikWebsite::class, 'update'])->name('infratik.website.update');
        Route::delete('/website/delete/{id}', [InfratikWebsite::class, 'destroy'])->name('infratik.website.destroy');

        // Infratik - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('infratik.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('infratik.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('infratik.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('infratik.pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('infratik.pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('infratik.pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('infratik.pemeliharaan.cancel');

        // Infratik - Log Aktivitas
        Route::get('/infratik/log-aktivitas', [\App\Http\Controllers\infratik\LogAktivitasController::class, 'index'])->name('infratik.logAktivitas');
    });

    // ====================================================================
    // TATAUSAHA ROLE
    // ====================================================================
     Route::middleware(['role:tatausaha'])->prefix('tatausaha')->group(function () {
        Route::get('/dashboard', [tatausahaDashboard::class, 'index'])->name('tatausaha.dashboard');

        // tatausaha - Server
        Route::get('/server', [tatausahaServer::class, 'index'])->name('tatausaha.server.index');
        Route::get('/server/rak/{rakId}/available-slots', [tatausahaServer::class, 'getAvailableSlots'])->name('tatausaha.server.availableSlots');
        Route::post('/server/store', [tatausahaServer::class, 'store'])->name('tatausaha.server.store');
        Route::get('/server/{id}/detail', [tatausahaServer::class, 'detail'])->name('tatausaha.server.detail');
        Route::get('/server/{id}/edit', [tatausahaServer::class, 'edit'])->name('tatausaha.server.edit');
        Route::put('/server/update/{id}', [tatausahaServer::class, 'update'])->name('tatausaha.server.update');
        Route::delete('/server/{id}', [tatausahaServer::class, 'destroy'])->name('tatausaha.server.destroy');

        // tatausaha - Website
        Route::get('/website', [tatausahaWebsite::class, 'index'])->name('tatausaha.website.index');
        Route::post('/website/store', [tatausahaWebsite::class, 'store'])->name('tatausaha.website.store');
        Route::get('/website/{id}/detail', [tatausahaWebsite::class, 'detail'])->name('tatausaha.website.detail');
        Route::put('/website/update/{id}', [tatausahaWebsite::class, 'update'])->name('tatausaha.website.update');
        Route::delete('/website/delete/{id}', [tatausahaWebsite::class, 'destroy'])->name('tatausaha.website.destroy');

        // tatausaha - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('tatausaha.pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('tatausaha.pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('tatausaha.pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('tatausaha.pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('tatausaha.pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('tatausaha.pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('tatausaha.pemeliharaan.cancel');

        // tatausaha - Log Aktivitas
        Route::get('/tatausaha/log-aktivitas', [\App\Http\Controllers\tatausaha\LogAktivitasController::class, 'index'])->name('tatausaha.logAktivitas');
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
