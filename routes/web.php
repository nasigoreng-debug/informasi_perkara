<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    SidangController,
    LaporanKasasiController,
    LaporanPerkaraController,
    RekapEksekusiController,
    SuratMasukController,
    LaporanBandingController,
    SisaPanjarController,
    UserController,
    CourtCalendarController,
    AktaCeraiController
};

/*
|--------------------------------------------------------------------------
| SISTEM INFORMASI PERKARA - PTA BANDUNG
|--------------------------------------------------------------------------
| Pengelolaan Route Aplikasi PH-Connection.
| Semua route dikelompokkan berdasarkan fungsinya untuk memudahkan maintenance.
*/

// ==========================================
// 1. OTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 2. AREA PRIVAT (Hanya User Terautentikasi)
// ==========================================
Route::middleware(['auth'])->group(function () {

    /**
     * LANDING PAGES & NAVIGASI UTAMA
     * Menu utama untuk mengarahkan pengguna ke sub-sub modul.
     */
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    Route::get('/monitoring', function () {
        return view('monitoring');
    })->name('monitoring');
    Route::get('/laporan-utama', function () {
        return view('laporan-utama');
    })->name('laporan-utama');
    // Route::get('/administrasi', function () {
    //     return view('administrasi');
    // })->name('administrasi');
    Route::get('/sisa-panjar', function () {
        return view('sisa_panjar_menu');
    })->name('sisa.panjar.menu');
    Route::get('/under', function () {
        return view('errors.under_construction');
    })->name('errors.under_construction');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * MODUL: MONITORING JADWAL SIDANG
     * Menampilkan jadwal sidang harian se-wilayah PTA Bandung.
     */
    Route::controller(SidangController::class)->prefix('jadwal-sidang')->name('sidang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/public', 'index_public')->name('index_public');
    });

    /**
     * MODUL: MONITORING COURT CALENDAR
     * Memantau kepatuhan input rencana sidang (Court Calendar) pada aplikasi SIPP.
     */
    Route::controller(CourtCalendarController::class)->prefix('court-calendar')->name('court-calendar')->group(function () {
        Route::get('/', 'index');
        Route::get('/detail/{satker}', 'detail')->name('.detail');
        Route::get('/export', 'export')->name('.export');
        Route::get('/export-detail/{satker}', 'exportDetail')->name('.export-detail'); // Tambahkan baris ini
    });
    /**
     * MODUL: LAPORAN KASASI
     * Monitoring berkas kasasi dan pengiriman dokumen PDF.
     */
    Route::controller(LaporanKasasiController::class)->prefix('kasasi')->name('kasasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
        Route::get('/export', 'export')->name('export');
    });

    /**
     * MODUL: SURAT MASUK (PERSURATAN)
     * Manajemen arsip surat masuk digital di lingkungan kepaniteraan.
     */
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

    /**
     * MODUL: LAPORAN PERKARA & PUTUSAN
     * Statistik jumlah perkara masuk, putus, dan putusan sela.
     */
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

    /**
     * MODUL: REKAP EKSEKUSI
     * Monitoring perkara permohonan eksekusi yang sedang berjalan.
     */
    Route::controller(RekapEksekusiController::class)->prefix('eksekusi')->name('laporan.eksekusi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::get('/detail', 'detail')->name('detail');
    });

    /**
     * MODUL: LAPORAN BANDING (RK-1 & RK-2)
     * Data perkara banding yang diterima dan diputus oleh PTA Bandung.
     */
    Route::controller(LaporanBandingController::class)->prefix('laporan/banding')->name('laporan.banding.')->group(function () {
        Route::get('/diterima', 'diterima')->name('diterima');
        Route::get('/detail', 'detail')->name('detail');
        Route::get('/diterima/export', 'exportRK1')->name('diterima.export');
        Route::get('/putus', 'diputus')->name('putus');
        Route::get('/putus/detail', 'detailPutus')->name('putus.detail');
        Route::get('/putus/export', 'exportRK2')->name('putus.export');
        Route::get('/jenis-perkara', 'perJenis')->name('jenis');
        Route::get('/jenis-perkara/export', 'exportJenis')->name('jenis.export');
    });

    /**
     * MODUL: SISA PANJAR BIAYA PERKARA
     * Monitoring sisa biaya perkara yang belum dikembalikan ke pihak (lebih dari 6 bulan).
     */
    Route::controller(SisaPanjarController::class)->prefix('sisa-panjar')->name('sisa.')->group(function () {
        Route::get('/pertama', 'SisaPanjarPertama')->name('pertama');
        Route::get('/banding', 'SisaPanjarBanding')->name('banding');
        Route::get('/kasasi', 'SisaPanjarKasasi')->name('kasasi');
        Route::get('/pk', 'SisaPanjarPK')->name('pk');
    });

    /**
     * MODUL: ADMINISTRASI USER & LOG AKTIVITAS
     * Manajemen hak akses pengguna dan pencatatan log sistem.
     */
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}/update', 'update')->name('update');
        Route::delete('/{id}/delete', 'destroy')->name('destroy');
    });

    Route::controller(AktaCeraiController::class)->prefix('akta-cerai')->name('akta-cerai.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail/{satker}', 'detail')->name('detail'); // Untuk detail per perkara
        Route::get('/export', 'export')->name('export');         // Untuk tarik data excel
        Route::get('/akta-cerai/export-detail', [AktaCeraiController::class, 'exportDetail'])->name('export-detail');
    });

    Route::get('/activity-log', function () {
        return view('activity_log');
    })->name('activity.log');
});
