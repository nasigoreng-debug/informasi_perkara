<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\LaporanKasasiController;
use App\Http\Controllers\LaporanPerkaraController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. LANDING PAGE / PORTAL
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// 2. JADWAL SIDANG
Route::get('/jadwal-sidang', [SidangController::class, 'index'])->name('sidang.index');
Route::get('/jadwal-sidang-visual', [SidangController::class, 'index_visual'])->name('sidang.index_visual');


// 3. LAPORAN KASASI
Route::controller(LaporanKasasiController::class)
    ->prefix('kasasi')
    ->name('kasasi.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
    });

// 4. LAPORAN PERKARA DITERIMA (sesuai yang sudah ada)
Route::prefix('laporan-perkara')->name('laporan.')->group(function () {
    Route::get('/', [LaporanPerkaraController::class, 'index'])->name('index');
    Route::get('/export', [LaporanPerkaraController::class, 'export'])->name('export');
});

// 5. LAPORAN PERKARA PUTUS
Route::prefix('laporan-perkara-putus')->name('laporan-putus.')->group(function () {
    Route::get('/', [LaporanPerkaraController::class, 'putus'])->name('index');
    Route::get('/export', [LaporanPerkaraController::class, 'exportPutus'])->name('export');
});
