<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\LaporanKasasiController;
use App\Http\Controllers\LaporanPerkaraController;
use App\Http\Controllers\RekapEksekusiController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\LaporanBandingController;
use App\Http\Controllers\SisaPanjarController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| SISTEM INFORMASI PERKARA - PTA BANDUNG
|--------------------------------------------------------------------------
*/

// 1. OTENTIKASI
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. AREA TERPROTEKSI (MEMERLUKAN LOGIN)
Route::middleware(['auth'])->group(function () {

    // LANDING & NAVIGATION
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    Route::get('/monitoring', function () {
        return view('monitoring');
    })->name('monitoring');
    Route::get('/laporan-utama', function () {
        return view('laporan-utama');
    })->name('laporan-utama');
    Route::get('/administrasi', function () {
        return view('administrasi');
    })->name('administrasi');
    Route::get('/sisa-panjar', function () {
        return view('sisa_panjar_menu');
    })->name('sisa.panjar.menu');
    Route::get('/under', function () {
        return view('errors.under_construction');
    })->name('errors.under_construction');

    // MODUL: MONITORING JADWAL SIDANG
    Route::controller(SidangController::class)->prefix('jadwal-sidang')->name('sidang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/public', 'index_public')->name('index_public');
    });

    // MODUL: LAPORAN KASASI
    Route::controller(LaporanKasasiController::class)->prefix('kasasi')->name('kasasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
        Route::get('/export', 'export')->name('export');
    });

    // MODUL: SURAT MASUK
    Route::controller(SuratMasukController::class)->prefix('surat-masuk')->name('surat.')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/download/{id}', 'download')->name('download');
        Route::get('/cetak', 'printPDF')->name('cetak');
        Route::get('/export-excel', 'exportExcel')->name('exportExcel');
    });

    // MODUL: LAPORAN PERKARA
    Route::controller(LaporanPerkaraController::class)->group(function () {
        Route::prefix('laporan-perkara')->name('laporan.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
        });
        Route::prefix('laporan-perkara-putus')->name('laporan-putus.')->group(function () {
            Route::get('/', 'putus')->name('index');
            Route::get('/export', 'exportPutus')->name('export');
            Route::get('/putusan-sela', 'PutusanSela')->name('putusan.sela');
            Route::get('/putusan-sela/export', 'exportPutusanSela')->name('putusan.sela.export');
        });
    });

    // MODUL: REKAP EKSEKUSI
    Route::controller(RekapEksekusiController::class)->prefix('eksekusi')->name('laporan.eksekusi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::get('/detail', 'detail')->name('detail');
    });

    // MODUL: LAPORAN BANDING
    Route::prefix('laporan/banding')->name('laporan.banding.')->group(function () {
        Route::get('/diterima', [LaporanBandingController::class, 'diterima'])->name('diterima');
        Route::get('/detail', [LaporanBandingController::class, 'detail'])->name('detail');
        Route::get('/diterima/export', [LaporanBandingController::class, 'exportRK1'])->name('diterima.export');
        Route::get('/putus', [LaporanBandingController::class, 'diputus'])->name('putus');
        Route::get('/putus/detail', [LaporanBandingController::class, 'detailPutus'])->name('putus.detail');
        Route::get('/putus/export', [LaporanBandingController::class, 'exportRK2'])->name('putus.export');
        Route::get('/jenis-perkara', [LaporanBandingController::class, 'perJenis'])->name('jenis');
        Route::get('/jenis-perkara/export', [LaporanBandingController::class, 'exportJenis'])->name('jenis.export');
    });

    // MODUL: SISA PANJAR
    Route::prefix('sisa-panjar')->group(function () {
        Route::get('/pertama', [SisaPanjarController::class, 'SisaPanjarPertama'])->name('sisa.pertama');
        Route::get('/banding', [SisaPanjarController::class, 'SisaPanjarBanding'])->name('sisa.banding');
        Route::get('/kasasi', [SisaPanjarController::class, 'SisaPanjarKasasi'])->name('sisa.kasasi');
        Route::get('/pk', [SisaPanjarController::class, 'SisaPanjarPK'])->name('sisa.pk');
    });

    // =========================================================================
    // MODUL KHUSUS ADMINISTRATOR (Sesuai Aturan Bapak)
    // =========================================================================
    // PERBAIKAN: Hapus baris 'resource' ganda dan gunakan controller tunggal yang bersih
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}/update', 'update')->name('update');
        Route::delete('/{id}/delete', 'destroy')->name('destroy');
    });
});
