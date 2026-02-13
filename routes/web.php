<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\LaporanKasasiController;

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


// 3. LAPORAN KASASI (Grouping)
Route::controller(LaporanKasasiController::class)
    ->prefix('kasasi')
    ->name('kasasi.')
    ->group(function () {

        Route::get('/', 'index')->name('index'); // URL: /kasasi, Name: kasasi.index

    });
