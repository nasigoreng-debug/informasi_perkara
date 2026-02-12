<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\LaporanKasasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. JADWAL SIDANG (Halaman Utama)
// Standar: Gunakan format nama 'resource.action' (sidang.index)
// Agar konsisten dengan resource controller lainnya.
Route::get('/', [SidangController::class, 'index'])->name('sidang.index');


// 2. LAPORAN KASASI (Grouping)
// Standar: Gunakan 'Route::controller' (Laravel 9+) agar tidak mengulang nama class.
Route::controller(LaporanKasasiController::class)
    ->prefix('kasasi')          // URL: /kasasi
    ->name('kasasi.')           // Route Name: kasasi.
    ->group(function () {

        // URL: /kasasi
        // Name: kasasi.index
        Route::get('/', 'index')->name('index');

        // Contoh jika nanti ada fitur export/cetak (Sangat mudah ditambahkan)
        // Route::get('/export', 'exportExcel')->name('export');
        // Route::get('/{id}', 'show')->name('show');
    });
