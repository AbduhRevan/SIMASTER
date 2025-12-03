<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PemeliharaanController;

// Controllers untuk Panduan
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\PanduanManagementController;

// Super Admin
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\GantiPasswordController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\LogAktivitasController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil, Password
    Route::get('/profil-saya', [ProfilController::class, 'profilSaya'])->name('profil.saya');
    Route::post('/profil-saya/upload-foto', [ProfilController::class, 'uploadFoto'])->name('profil.upload.foto');
    Route::delete('/profil-saya/hapus-foto', [ProfilController::class, 'hapusFoto'])->name('profil.hapus.foto');
    Route::get('/ganti-password', [GantiPasswordController::class, 'index'])->name('ganti.password');
    Route::post('/ganti-password', [GantiPasswordController::class, 'update'])->name('ganti.password.update');

    // ====================================================================
    // MANAJEMEN ASET - SERVER
    // ====================================================================
    Route::get('/server', [ServerController::class, 'index'])->name('server.index');
    Route::get('/server/rak/{rakId}/available-slots', [ServerController::class, 'getAvailableSlots'])->name('server.availableSlots');
    Route::post('/server/store', [ServerController::class, 'store'])->name('server.store');
    Route::get('/server/{id}/detail', [ServerController::class, 'detail'])->name('server.detail');
    Route::get('/server/{id}/edit', [ServerController::class, 'edit'])->name('server.edit');
    Route::put('/server/update/{id}', [ServerController::class, 'update'])->name('server.update');
    Route::delete('/server/{id}', [ServerController::class, 'destroy'])->name('server.destroy');
    Route::get('/server/export-pdf', [ServerController::class, 'exportPDF'])->name('server.export.pdf');


    // ====================================================================
    // MANAJEMEN ASET - WEBSITE
    // ====================================================================
    Route::get('/website', [WebsiteController::class, 'index'])->name('website.index');
    Route::post('/website/store', [WebsiteController::class, 'store'])->name('website.store');
    Route::get('/website/{id}/detail', [WebsiteController::class, 'detail'])->name('website.detail');
    Route::put('/website/update/{id}', [WebsiteController::class, 'update'])->name('website.update');
    Route::delete('/website/delete/{id}', [WebsiteController::class, 'destroy'])->name('website.destroy');
    Route::get('/website/export-pdf', [WebsiteController::class, 'exportPDF'])->name('website.exportPDF');

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

    // Panduan Pengguna (Read-Only untuk semua user)
    Route::get('/panduan-pengguna/{slug?}', [PanduanController::class, 'index'])->name('panduan.pengguna');

    // Log Aktivitas
    Route::get('/logAktivitas', [LogAktivitasController::class, 'index'])->name('logAktivitas');

    // ====================================================================
    // SUPERADMIN ROLE
    // ====================================================================
    Route::middleware(['role:superadmin'])->name('superadmin.')->group(function () {


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
});
