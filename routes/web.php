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
    AktaCeraiController,
    SuratKeluarController,
    SuratKeputusanController,
    PengaduanController,
    PeraturanController
};

/*
|--------------------------------------------------------------------------
| SISTEM INFORMASI PERKARA - PTA BANDUNG
|--------------------------------------------------------------------------
| Pengelolaan Route Aplikasi PH-Connection.
*/

// ==========================================
// 1. ROUTE PUBLIC (TANPA LOGIN)
// ==========================================
Route::get('/jadwal-sidang/public', [SidangController::class, 'index_public'])->name('sidang.index_public');
Route::get('/jdih-ptabandung', [PeraturanController::class, 'index_public'])->name('peraturan.public');

// ==========================================
// 2. OTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 3. AREA PRIVAT (Hanya User Terautentikasi)
// ==========================================
Route::middleware(['auth'])->group(function () {

    /**
     * LANDING PAGES & NAVIGASI UTAMA
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
    Route::get('/administrasi', function () {
        return view('administrasi');
    })->name('administrasi');
    Route::get('/sisa-panjar', function () {
        return view('sisa_panjar_menu');
    })->name('sisa.panjar.menu');
    Route::get('/under', function () {
        return view('errors.under_construction');
    })->name('errors.under_construction');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * MODUL: MONITORING JADWAL SIDANG
     */
    Route::controller(SidangController::class)->prefix('jadwal-sidang')->name('sidang.')->group(function () {
        Route::get('/', 'index')->name('index');
        // Route public sudah dipindahkan ke luar
    });

    /**
     * MODUL: MONITORING COURT CALENDAR
     */
    Route::controller(CourtCalendarController::class)->prefix('court-calendar')->name('court-calendar')->group(function () {
        Route::get('/', 'index');
        Route::get('/detail/{satker}', 'detail')->name('.detail');
        Route::get('/export', 'export')->name('.export');
        Route::get('/export-detail/{satker}', 'exportDetail')->name('.export-detail');
    });

    /**
     * MODUL: LAPORAN KASASI
     */
    Route::controller(LaporanKasasiController::class)->prefix('kasasi')->name('kasasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
        Route::get('/export', 'export')->name('export');
    });

    /**
     * MODUL: SURAT MASUK (PERSURATAN)
     */
    Route::controller(SuratMasukController::class)->prefix('surat-masuk')->name('surat.masuk.')->group(function () {
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
     * MODUL: SURAT KELUAR
     */
    Route::controller(SuratKeluarController::class)->prefix('surat-keluar')->name('surat.keluar.')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}/update', 'update')->name('update');
        Route::delete('/{id}/delete', 'destroy')->name('destroy');
        Route::get('/{id}/download/{type}', 'download')->name('download');
        Route::get('/cetak', 'printPDF')->name('cetak');
        Route::get('/export-excel', 'exportExcel')->name('exportExcel');
    });

    /**
     * MODUL: SURAT KEPUTUSAN (SK)
     */
    Route::controller(SuratKeputusanController::class)->prefix('surat-keputusan')->name('sk.')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/download/{id}/{type}', 'download')->name('download');
        Route::get('/export-excel', 'exportExcel')->name('exportExcel');
    });

    /**
     * MODUL: LAPORAN PERKARA & PUTUSAN
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
     */
    Route::controller(RekapEksekusiController::class)->prefix('eksekusi')->name('laporan.eksekusi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::get('/detail', 'detail')->name('detail');
    });

    /**
     * MODUL: LAPORAN BANDING
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
     * MODUL: MONITORING SISA PANJAR
     */
    Route::controller(SisaPanjarController::class)->prefix('sisa-panjar')->name('sisa.panjar.')->group(function () {
        Route::get('/', 'index')->name('menu');
        Route::get('/pertama', 'SisaPanjarPertama')->name('pertama');
        Route::get('/banding', 'SisaPanjarBanding')->name('banding');
        Route::get('/kasasi', 'SisaPanjarKasasi')->name('kasasi');
        Route::get('/pk', 'SisaPanjarPK')->name('pk');
        Route::get('/detail', 'detail')->name('detail');
    });

    /**
     * MODUL: ADMINISTRASI USER
     */
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}/update', 'update')->name('update');
        Route::delete('/{id}/delete', 'destroy')->name('destroy');
    });

    /**
     * MODUL: AKTA CERAI
     */
    Route::controller(AktaCeraiController::class)->prefix('akta-cerai')->name('akta-cerai.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail/{satker}', 'detail')->name('detail');
        Route::get('/export', 'export')->name('export');
        Route::get('/export-detail', 'exportDetail')->name('export-detail');
    });

    /**
     * MODUL: AKTA PENGADUAN (SIWAS)
     */
    Route::controller(PengaduanController::class)->prefix('pengaduan')->name('pengaduan.')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::get('/detail/{id}', 'detail')->name('detail');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/download/{id}/{type}', 'download')->name('download');
        Route::get('/export', 'exportExcel')->name('export_excel');
        Route::get('/modal-detail/{id}', 'modalDetail')->name('modal-detail');
    });

    /**
     * MODUL: HIMPUNAN PERATURAN (SEMA, PERMA, UU)
     */
    Route::controller(PeraturanController::class)->prefix('peraturan')->name('peraturan.')->group(function () {
        Route::get('/', 'index')->name('index');           // Daftar Peraturan & Monitoring
        Route::get('/create', 'create')->name('create');   // Form Tambah
        Route::post('/store', 'store')->name('store');     // Simpan Data
        Route::get('/edit/{id}', 'edit')->name('edit');     // Form Edit
        Route::put('/update/{id}', 'update')->name('update'); // Proses Update
        Route::delete('/delete/{id}', 'destroy')->name('destroy'); // Hapus Data
    });

    Route::get('/activity-log', function () {
        return view('activity_log');
    })->name('activity.log');
});
