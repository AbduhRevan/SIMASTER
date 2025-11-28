<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PemeliharaanController;

// Controllers untuk Panduan
use App\Http\Controllers\superadmin\PanduanController;
use App\Http\Controllers\superadmin\PanduanManagementController;

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

    // Profil, Password
    Route::get('/profil-saya', [ProfilController::class, 'profilSaya'])->name('profil.saya');
    Route::get('/ganti-password', [ProfilController::class, 'gantiPassword'])->name('ganti.password');
    Route::post('/ganti-password', [ProfilController::class, 'updatePassword'])->name('ganti.password.post');

    // Panduan Pengguna (Read-Only untuk semua user)
    Route::get('/panduan-pengguna/{slug?}', [PanduanController::class, 'index'])->name('panduan.pengguna');

    // ====================================================================
    // SUPERADMIN ROLE
    // ====================================================================
    Route::middleware(['role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperadminDashboard::class, 'index'])->name('dashboard');

        // ====================================================================
        // DATA MASTER (Hard Delete - Hapus Permanen Langsung)
        // ====================================================================

        // Bidang (Hard Delete)
        Route::get('/bidang', [BidangController::class, 'index'])->name('bidang');
        Route::post('/bidang/store', [BidangController::class, 'store'])->name('bidang.store');
        Route::put('/bidang/update/{id}', [BidangController::class, 'update'])->name('bidang.update');
        Route::delete('/bidang/delete/{id}', [BidangController::class, 'destroy'])->name('bidang.delete');

        // Satuan Kerja (Hard Delete)
        Route::get('/satuankerja', [SatkerController::class, 'index'])->name('satuankerja');
        Route::get('/satuankerja/search', [SatkerController::class, 'search'])->name('satker.search');
        Route::post('/satuankerja/store', [SatkerController::class, 'store'])->name('satker.store');
        Route::put('/satker/update/{id}', [SatkerController::class, 'update'])->name('satker.update');
        Route::delete('/satker/delete/{id}', [SatkerController::class, 'destroy'])->name('satker.delete');

        // Rak Server (Hard Delete)
        Route::get('/rakserver', [RakController::class, 'index'])->name('rakserver');
        Route::get('/rakserver/search', [RakController::class, 'search'])->name('rakserver.search');
        Route::post('/rakserver/store', [RakController::class, 'store'])->name('rakserver.store');
        Route::put('/rakserver/update/{id}', [RakController::class, 'update'])->name('rakserver.update');
        Route::delete('/rakserver/delete/{id}', [RakController::class, 'destroy'])->name('rakserver.delete');

        // ====================================================================
        // MANAJEMEN ASET - SERVER
        // ====================================================================
        Route::get('/server', [SuperadminServer::class, 'index'])->name('server.index');
        Route::get('/server/rak/{rakId}/available-slots', [SuperadminServer::class, 'getAvailableSlots'])->name('server.availableSlots');
        Route::post('/server/store', [SuperadminServer::class, 'store'])->name('server.store');
        Route::get('/server/{id}/detail', [SuperadminServer::class, 'detail'])->name('server.detail');
        Route::get('/server/{id}/edit', [SuperadminServer::class, 'edit'])->name('server.edit');
        Route::put('/server/update/{id}', [SuperadminServer::class, 'update'])->name('server.update');
        Route::delete('/server/{id}', [SuperadminServer::class, 'destroy'])->name('server.destroy');

        // ====================================================================
        // MANAJEMEN ASET - WEBSITE
        // ====================================================================
        Route::get('/website', [SuperadminWebsite::class, 'index'])->name('website.index');
        Route::post('/website/store', [SuperadminWebsite::class, 'store'])->name('website.store');
        Route::get('/website/{id}/detail', [SuperadminWebsite::class, 'detail'])->name('website.detail');
        Route::put('/website/update/{id}', [SuperadminWebsite::class, 'update'])->name('website.update');
        Route::delete('/website/delete/{id}', [SuperadminWebsite::class, 'destroy'])->name('website.destroy');

        // ====================================================================
        // PEMELIHARAAN
        // ====================================================================
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('pemeliharaan.cancel');

        // ====================================================================
        // SISTEM
        // ====================================================================

        // Pengguna
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/pengguna/bidang', [PenggunaController::class, 'getBidang'])->name('pengguna.bidang');
        Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
        Route::post('/pengguna/{id}/toggle-status', [PenggunaController::class, 'toggleStatus'])->name('pengguna.toggle-status');

        // Log Aktivitas
        Route::get('/logAktivitas', [LogAktivitasController::class, 'index'])->name('logAktivitas');

        // ====================================================================
        // PANDUAN MANAGEMENT (CRUD - Khusus Superadmin)
        // ====================================================================

        // Kelola Kategori Panduan
        Route::prefix('panduan')->name('panduan.')->group(function () {
            // Kategori
            Route::get('/kategori', [PanduanManagementController::class, 'indexKategori'])->name('kategori.index');
            Route::post('/kategori/store', [PanduanManagementController::class, 'storeKategori'])->name('kategori.store');
            Route::put('/kategori/update/{id}', [PanduanManagementController::class, 'updateKategori'])->name('kategori.update');
            Route::delete('/kategori/delete/{id}', [PanduanManagementController::class, 'destroyKategori'])->name('kategori.delete');
            Route::post('/kategori/toggle/{id}', [PanduanManagementController::class, 'toggleStatusKategori'])->name('kategori.toggle');

            // Item Panduan
            Route::get('/item', [PanduanManagementController::class, 'indexItem'])->name('item.index');
            Route::post('/item/store', [PanduanManagementController::class, 'storeItem'])->name('item.store');
            Route::put('/item/update/{id}', [PanduanManagementController::class, 'updateItem'])->name('item.update');
            Route::delete('/item/delete/{id}', [PanduanManagementController::class, 'destroyItem'])->name('item.delete');
            Route::post('/item/toggle/{id}', [PanduanManagementController::class, 'toggleStatusItem'])->name('item.toggle');
        });
    });

    // ====================================================================
    // BANGLOLA ROLE
    // ====================================================================
    Route::middleware(['role:banglola'])->prefix('banglola')->name('banglola.')->group(function () {
        Route::get('/dashboard', [BanglolaDashboard::class, 'index'])->name('dashboard');

        // Banglola - Server
        Route::get('/server', [BanglolaServer::class, 'index'])->name('server.index');
        Route::get('/server/rak/{rakId}/available-slots', [BanglolaServer::class, 'getAvailableSlots'])->name('server.availableSlots');
        Route::post('/server/store', [BanglolaServer::class, 'store'])->name('server.store');
        Route::get('/server/{id}/detail', [BanglolaServer::class, 'detail'])->name('server.detail');
        Route::get('/server/{id}/edit', [BanglolaServer::class, 'edit'])->name('server.edit');
        Route::put('/server/update/{id}', [BanglolaServer::class, 'update'])->name('server.update');
        Route::delete('/server/{id}', [BanglolaServer::class, 'destroy'])->name('server.destroy');

        // Banglola - Website
        Route::get('/website', [BanglolaWebsite::class, 'index'])->name('website.index');
        Route::post('/website/store', [BanglolaWebsite::class, 'store'])->name('website.store');
        Route::get('/website/{id}/detail', [BanglolaWebsite::class, 'detail'])->name('website.detail');
        Route::put('/website/update/{id}', [BanglolaWebsite::class, 'update'])->name('website.update');
        Route::delete('/website/delete/{id}', [BanglolaWebsite::class, 'destroy'])->name('website.destroy');

        // Banglola - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('pemeliharaan.cancel');

        // Banglola - Log Aktivitas
        Route::get('/log-aktivitas', [BanglolaLogAktivitas::class, 'index'])->name('logAktivitas');
    });

    // ====================================================================
    // PAMSIS ROLE
    // ====================================================================
    Route::middleware(['role:pamsis'])->prefix('pamsis')->name('pamsis.')->group(function () {
        Route::get('/dashboard', [PamsisDashboard::class, 'index'])->name('dashboard');

        // Pamsis - Server
        Route::get('/server', [PamsisServer::class, 'index'])->name('server.index');
        Route::get('/server/rak/{rakId}/available-slots', [PamsisServer::class, 'getAvailableSlots'])->name('server.availableSlots');
        Route::post('/server/store', [PamsisServer::class, 'store'])->name('server.store');
        Route::get('/server/{id}/detail', [PamsisServer::class, 'detail'])->name('server.detail');
        Route::get('/server/{id}/edit', [PamsisServer::class, 'edit'])->name('server.edit');
        Route::put('/server/update/{id}', [PamsisServer::class, 'update'])->name('server.update');
        Route::delete('/server/{id}', [PamsisServer::class, 'destroy'])->name('server.destroy');

        // Pamsis - Website
        Route::get('/website', [PamsisWebsite::class, 'index'])->name('website.index');
        Route::post('/website/store', [PamsisWebsite::class, 'store'])->name('website.store');
        Route::get('/website/{id}/detail', [PamsisWebsite::class, 'detail'])->name('website.detail');
        Route::put('/website/update/{id}', [PamsisWebsite::class, 'update'])->name('website.update');
        Route::delete('/website/delete/{id}', [PamsisWebsite::class, 'destroy'])->name('website.destroy');

        // Pamsis - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('pemeliharaan.cancel');

        // Pamsis - Log Aktivitas
        Route::get('/log-aktivitas', [PamsisLogAktivitas::class, 'index'])->name('logAktivitas');
    });

    // ====================================================================
    // INFRATIK ROLE
    // ====================================================================
    Route::middleware(['role:infratik'])->prefix('infratik')->name('infratik.')->group(function () {
        Route::get('/dashboard', [InfratikDashboard::class, 'index'])->name('dashboard');

        // Infratik - Server
        Route::get('/server', [InfratikServer::class, 'index'])->name('server.index');
        Route::get('/server/rak/{rakId}/available-slots', [InfratikServer::class, 'getAvailableSlots'])->name('server.availableSlots');
        Route::post('/server/store', [InfratikServer::class, 'store'])->name('server.store');
        Route::get('/server/{id}/detail', [InfratikServer::class, 'detail'])->name('server.detail');
        Route::get('/server/{id}/edit', [InfratikServer::class, 'edit'])->name('server.edit');
        Route::put('/server/update/{id}', [InfratikServer::class, 'update'])->name('server.update');
        Route::delete('/server/{id}', [InfratikServer::class, 'destroy'])->name('server.destroy');

        // Infratik - Website
        Route::get('/website', [InfratikWebsite::class, 'index'])->name('website.index');
        Route::post('/website/store', [InfratikWebsite::class, 'store'])->name('website.store');
        Route::get('/website/{id}/detail', [InfratikWebsite::class, 'detail'])->name('website.detail');
        Route::put('/website/update/{id}', [InfratikWebsite::class, 'update'])->name('website.update');
        Route::delete('/website/delete/{id}', [InfratikWebsite::class, 'destroy'])->name('website.destroy');

        // Infratik - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('pemeliharaan.cancel');

        // Infratik - Log Aktivitas
        Route::get('/log-aktivitas', [InfratikLogAktivitas::class, 'index'])->name('logAktivitas');
    });

    // ====================================================================
    // TATAUSAHA ROLE
    // ====================================================================
    Route::middleware(['role:tatausaha'])->prefix('tatausaha')->name('tatausaha.')->group(function () {
        Route::get('/dashboard', [TataUsahaDashboard::class, 'index'])->name('dashboard');

        // tatausaha - Server
        Route::get('/server', [TataUsahaServer::class, 'index'])->name('server.index');
        Route::get('/server/rak/{rakId}/available-slots', [TataUsahaServer::class, 'getAvailableSlots'])->name('server.availableSlots');
        Route::post('/server/store', [TataUsahaServer::class, 'store'])->name('server.store');
        Route::get('/server/{id}/detail', [TataUsahaServer::class, 'detail'])->name('server.detail');
        Route::get('/server/{id}/edit', [TataUsahaServer::class, 'edit'])->name('server.edit');
        Route::put('/server/update/{id}', [TataUsahaServer::class, 'update'])->name('server.update');
        Route::delete('/server/{id}', [TataUsahaServer::class, 'destroy'])->name('server.destroy');

        // tatausaha - Website
        Route::get('/website', [TataUsahaWebsite::class, 'index'])->name('website.index');
        Route::post('/website/store', [TataUsahaWebsite::class, 'store'])->name('website.store');
        Route::get('/website/{id}/detail', [TataUsahaWebsite::class, 'detail'])->name('website.detail');
        Route::put('/website/update/{id}', [TataUsahaWebsite::class, 'update'])->name('website.update');
        Route::delete('/website/delete/{id}', [TataUsahaWebsite::class, 'destroy'])->name('website.destroy');

        // tatausaha - Pemeliharaan
        Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan');
        Route::post('/pemeliharaan/store', [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
        Route::put('/pemeliharaan/update/{id}', [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/delete/{id}', [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
        Route::post('/pemeliharaan/{id}/start', [PemeliharaanController::class, 'start'])->name('pemeliharaan.start');
        Route::post('/pemeliharaan/{id}/finish', [PemeliharaanController::class, 'finish'])->name('pemeliharaan.finish');
        Route::post('/pemeliharaan/{id}/cancel', [PemeliharaanController::class, 'cancel'])->name('pemeliharaan.cancel');

        // tatausaha - Log Aktivitas
        Route::get('/log-aktivitas', [TataUsahaLogAktivitas::class, 'index'])->name('logAktivitas');
    });

    // ====================================================================
    // PIMPINAN ROLE
    // ====================================================================
    Route::middleware(['role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pimpinan.dashboard');
        })->name('dashboard');
    });
});
