<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    SidangController,
    LaporanKasasiController,
    LaporanPerkaraDiterimaController,
    LaporanPerkaraDiputusController,
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
    PeraturanController,
    RetensiArsipPerkaraController,
    SyncMonitoringController
};

/*
|--------------------------------------------------------------------------
| SISTEM INFORMASI PERKARA - PTA BANDUNG
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIC (TANPA LOGIN)
// ==========================================
Route::get('/jadwal-sidang/public', [SidangController::class, 'index_public'])->name('sidang.index_public');
Route::get('/jdih-ptabandung', [PeraturanController::class, 'index_public'])->name('peraturan.public');
Route::get('/arsip-perkara/public', [RetensiArsipPerkaraController::class, 'index_public'])->name('arsip.public');

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

    /** LANDING PAGES & NAVIGASI UTAMA */
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

    /** MODUL: MONITORING JADWAL SIDANG */
    Route::controller(SidangController::class)->prefix('jadwal-sidang')->name('sidang.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    /** MODUL: MONITORING COURT CALENDAR */
    Route::controller(CourtCalendarController::class)->prefix('court-calendar')->name('court-calendar')->group(function () {
        Route::get('/', 'index');
        Route::get('/detail/{satker}', 'detail')->name('.detail');
        Route::get('/export', 'export')->name('.export');
        Route::get('/export-detail/{satker}', 'exportDetail')->name('.export-detail');
    });

    /** MODUL: LAPORAN KASASI */
    Route::controller(LaporanKasasiController::class)->prefix('kasasi')->name('kasasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
        Route::get('/export', 'export')->name('export');
    });

    /** MODUL: SURAT MASUK */
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

    /** MODUL: SURAT KELUAR */
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

    /** MODUL: SURAT KEPUTUSAN (SK) */
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

    /** MODUL: LAPORAN PERKARA DITERIMA (RK3) */
    Route::controller(LaporanPerkaraDiterimaController::class)->prefix('laporan-perkara-diterima')->name('laporan.diterima.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::post('/sync', function () {
            \Illuminate\Support\Facades\Artisan::queue('sync:perkara-diterima', ['--start' => date('Y-m-d'), '--end' => date('Y-m-d')]);
            return back()->with('success', 'Sinkronisasi RK3 sedang berjalan.');
        })->name('sync');
    });

    /** MODUL: LAPORAN PERKARA DIPUTUS (RK4) */
    Route::controller(LaporanPerkaraDiputusController::class)->prefix('laporan-perkara-diputus')->name('laporan.diputus.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::get('/sync', function () {
            \Illuminate\Support\Facades\Artisan::queue('sync:perkara-diputus', ['--start' => date('Y-m-d'), '--end' => date('Y-m-d')]);
            return back()->with('success', 'Sinkronisasi RK4 sedang berjalan.');
        })->name('sync');
    });

    /** MODUL: REKAP EKSEKUSI */
    Route::controller(RekapEksekusiController::class)->prefix('eksekusi')->name('laporan.eksekusi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::get('/detail', 'detail')->name('detail');
    });

    /** MODUL: LAPORAN BANDING */
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

    /** MODUL: MONITORING SISA PANJAR */
    Route::controller(SisaPanjarController::class)->prefix('sisa-panjar')->name('sisa.panjar.')->group(function () {
        Route::get('/', 'index')->name('menu');
        Route::get('/pertama', 'SisaPanjarPertama')->name('pertama');
        Route::get('/banding', 'SisaPanjarBanding')->name('banding');
        Route::get('/kasasi', 'SisaPanjarKasasi')->name('kasasi');
        Route::get('/pk', 'SisaPanjarPK')->name('pk');
        Route::get('/detail', 'detail')->name('detail');
    });

    /** MODUL: ADMINISTRASI USER */
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}/update', 'update')->name('update');
        Route::delete('/{id}/delete', 'destroy')->name('destroy');
    });

    /** MODUL: AKTA CERAI */
    Route::controller(AktaCeraiController::class)->prefix('akta-cerai')->name('akta-cerai.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail/{satker}', 'detail')->name('detail');
        Route::get('/export', 'export')->name('export');
        Route::get('/export-detail', 'exportDetail')->name('export-detail');
    });

    /** MODUL: AKTA PENGADUAN (SIWAS) */
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

    /** MODUL: HIMPUNAN PERATURAN */
    Route::controller(PeraturanController::class)->prefix('peraturan')->name('peraturan.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    /** MODUL: ARSIP PERKARA DIGITAL */
    Route::controller(RetensiArsipPerkaraController::class)->prefix('retensi-arsip-perkara')->name('retensi-arsip.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/detail/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    /** MODUL: MONITORING SINKRONISASI */
    Route::prefix('admin/monitoring-sync')->name('admin.sync.')->group(function () {
        Route::get('/', [SyncMonitoringController::class, 'index'])->name('index');
        Route::get('/status-json', [SyncMonitoringController::class, 'getStatusJson'])->name('status_json');
        Route::post('/start-rk4', function () {
            \Illuminate\Support\Facades\Artisan::queue('sync:perkara-diputus', ['--start' => date('Y-m-01'), '--end' => date('Y-m-d')]);
            return response()->json(['status' => 'started']);
        })->name('start_rk4');
    });

    Route::get('/activity-log', function () {
        return view('activity_log');
    })->name('activity.log');
});
