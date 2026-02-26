<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\LaporanKasasiController;
use App\Http\Controllers\LaporanPerkaraController;
use App\Http\Controllers\RekapEksekusiController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\LaporanBandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. LANDING & MAIN HUB ---
// Ini adalah halaman awal (pilihan Monitoring atau Laporan)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Halaman khusus daftar menu Monitoring
Route::get('/monitoring', function () {
    return view('monitoring');
})->name('monitoring');

// Halaman khusus daftar menu Laporan
Route::get('/laporan-utama', function () {
    return view('laporan-utama');
})->name('laporan-utama');

// Halaman khusus daftar menu Laporan
Route::get('/administrasi', function () {
    return view('administrasi');
})->name('administrasi');

// Halaman khusus error atau under construction untuk fitur Administrasi
Route::get('/under', function () {
    return view('errors.under_construction');
})->name('errors.under_construction');



// --- 2. MONITORING GROUP (Detail Fitur) ---

// Jadwal Sidang
Route::controller(SidangController::class)->prefix('jadwal-sidang')->name('sidang.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/visual', 'index_visual')->name('index_visual');
});

// Laporan Kasasi
Route::controller(LaporanKasasiController::class)->prefix('kasasi')->name('kasasi.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/detail', 'detail')->name('detail');
    Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
    Route::get('/export', 'export')->name('export');
});

// Surat Masuk
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
});


// --- 3. LAPORAN GROUP (Detail Fitur) ---

Route::controller(LaporanPerkaraController::class)->group(function () {
    // Perkara Diterima
    Route::prefix('laporan-perkara')->name('laporan.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
    });

    // Perkara Putus
    Route::prefix('laporan-perkara-putus')->name('laporan-putus.')->group(function () {
        Route::get('/', 'putus')->name('index');
        Route::get('/export', 'exportPutus')->name('export');
    });
});

// Rekap Eksekusi (Masuk dalam kategori monitoring/laporan sesuai kebutuhan)
Route::controller(RekapEksekusiController::class)->prefix('eksekusi')->name('laporan.eksekusi.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/export', 'export')->name('export');
    Route::get('/detail', 'detail')->name('detail');
});


// Group Laporan Banding
Route::prefix('laporan/banding')->name('laporan.banding.')->group(function () {

    // --- LAPORAN RK1 (PENERIMAAN) ---
    Route::get('/diterima', [LaporanBandingController::class, 'diterima'])->name('diterima');
    Route::get('/detail', [LaporanBandingController::class, 'detail'])->name('detail');
    Route::get('/diterima/export', [LaporanBandingController::class, 'exportRK1'])->name('diterima.export'); // Tambahan Export RK1

    // --- LAPORAN RK2 (KEADAAN PERKARA / PUTUS) ---
    Route::get('/putus', [LaporanBandingController::class, 'diputus'])->name('putus');
    Route::get('/putus/detail', [LaporanBandingController::class, 'detailPutus'])->name('putus.detail');
    Route::get('/putus/export', [LaporanBandingController::class, 'exportRK2'])->name('putus.export');

    Route::get('/jenis-perkara', [LaporanBandingController::class, 'perJenis'])->name('jenis');
    Route::get('/jenis-perkara/export', [LaporanBandingController::class, 'exportJenis'])->name('jenis.export');
});
