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

// 1. LANDING PAGE / PORTAL (Halaman Utama saat buka website)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// 2. JADWAL SIDANG (Pindah ke URL /jadwal-sidang)
Route::get('/jadwal-sidang', [SidangController::class, 'index'])->name('sidang.index');
Route::get('/jadwal-sidang-visual', [SidangController::class, 'index_visual'])->name('sidang.index_visual');


// 3. LAPORAN KASASI (Grouping)
Route::controller(LaporanKasasiController::class)
    ->prefix('kasasi')
    ->name('kasasi.')
    ->group(function () {

        Route::get('/', 'index')->name('index'); // URL: /kasasi, Name: kasasi.index

    });

// 4. LAPORAN PERKARA
Route::prefix('laporan-perkara')->name('laporan.')->group(function () {
    Route::get('/', [LaporanPerkaraController::class, 'index'])->name('index');
    Route::get('/export', [LaporanPerkaraController::class, 'export'])->name('export');
});
