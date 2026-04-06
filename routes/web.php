<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    SidangController,
    LaporanKasasiController,
    LaporanPerkaraDiterimaController,
    LaporanPerkaraDiputusController,
    RekapEksekusiController,
    SuratMasukController,
    SisaPanjarController,
    UserController,
    CourtCalendarController,
    AktaCeraiController,
    SuratKeluarController,
    SuratKeputusanController,
    PengaduanController,
    PeraturanController,
    RetensiArsipPerkaraController,
    SyncMonitoringController,
    RK1Controller,
    RK2Controller,
    JenisPerkaraBandingController,
    KinerjaController,
    BankPutusanController,
    ArsipAktifController,
    MonitoringElaporanController,
    InputDataController
};

/*
|--------------------------------------------------------------------------
| SISTEM INFORMASI PERKARA - PTA BANDUNG
|--------------------------------------------------------------------------
| File ini mengatur semua endpoint URL yang tersedia dalam aplikasi.
| Route dibagi berdasarkan:
| 1. Route publik (tanpa autentikasi)
| 2. Route autentikasi (login/logout)
| 3. Route privat (memerlukan login)
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. ROUTE PUBLIK (TIDAK PERLU LOGIN)
// =========================================================================
Route::get('/jadwal-sidang/public', [SidangController::class, 'index_public'])->name('sidang.index_public');
Route::get('/jdih-ptabandung', [PeraturanController::class, 'index_public'])->name('peraturan.public');
Route::get('/arsip-perkara/public', [RetensiArsipPerkaraController::class, 'index_public'])->name('arsip.public');
Route::get('/dashboard-public', [DashboardController::class, 'index_public'])->name('dashboard.public');

// =========================================================================
// 2. ROUTE AUTENTIKASI (LOGIN/LOGOUT)
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// 3. ROUTE PRIVAT (HARUS LOGIN TERLEBIH DAHULU)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // ---------------------------------------------------------------------
    // HALAMAN UTAMA & NAVIGASI
    // ---------------------------------------------------------------------
    Route::get('/', fn() => view('welcome'))->name('welcome');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detail', [DashboardController::class, 'detail'])->name('dashboard.detail');
    Route::get('/monitoring', fn() => view('monitoring'))->name('monitoring');
    Route::get('/laporan-utama', fn() => view('laporan-utama'))->name('laporan-utama');
    Route::get('/administrasi', fn() => view('administrasi'))->name('administrasi');
    Route::get('/kinerja', [KinerjaController::class, 'index'])->name('kinerja.index');
    Route::get('/activity-log', fn() => view('activity_log'))->name('activity.log');
    Route::get('/under', fn() => view('errors.under_construction'))->name('errors.under_construction');
    Route::get('/sisa-panjar', fn() => view('sisa_panjar_menu'))->name('sisa.panjar.menu');

    // ---------------------------------------------------------------------
    // MODUL: JADWAL SIDANG
    // ---------------------------------------------------------------------
    Route::controller(SidangController::class)
        ->prefix('jadwal-sidang')
        ->name('sidang.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });

    // ---------------------------------------------------------------------
    // MODUL: KALENDER PENGADILAN
    // ---------------------------------------------------------------------
    Route::controller(CourtCalendarController::class)
        ->prefix('court-calendar')
        ->name('court-calendar.')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/detail/{satker}', 'detail')->name('detail');
            Route::get('/export', 'export')->name('export');
            Route::get('/export-detail/{satker}', 'exportDetail')->name('export-detail');
        });

    // ---------------------------------------------------------------------
    // MODUL: LAPORAN KASASI
    // ---------------------------------------------------------------------
    Route::controller(LaporanKasasiController::class)
        ->prefix('kasasi')
        ->name('kasasi.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail', 'detail')->name('detail');
            Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
            Route::get('/export', 'export')->name('export');
        });

    // ---------------------------------------------------------------------
    // MODUL: SURAT MASUK
    // ---------------------------------------------------------------------
    Route::controller(SuratMasukController::class)
        ->prefix('surat-masuk')
        ->name('surat.masuk.')
        ->group(function () {
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

    // ---------------------------------------------------------------------
    // MODUL: SURAT KELUAR
    // ---------------------------------------------------------------------
    Route::controller(SuratKeluarController::class)
        ->prefix('surat-keluar')
        ->name('surat.keluar.')
        ->group(function () {
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

    // ---------------------------------------------------------------------
    // MODUL: SURAT KEPUTUSAN (SK)
    // ---------------------------------------------------------------------
    Route::controller(SuratKeputusanController::class)
        ->prefix('surat-keputusan')
        ->name('sk.')
        ->group(function () {
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

    // ---------------------------------------------------------------------
    // MODUL: LAPORAN PERKARA DITERIMA (RK3)
    // ---------------------------------------------------------------------
    Route::controller(LaporanPerkaraDiterimaController::class)
        ->prefix('laporan-perkara-diterima')
        ->name('laporan.diterima.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::post('/sync', function () {
                Artisan::queue('sync:perkara-diterima', [
                    '--start' => date('Y-m-d'),
                    '--end' => date('Y-m-d')
                ]);
                return back()->with('success', 'Sinkronisasi RK3 sedang berjalan.');
            })->name('sync');
        });

    // ---------------------------------------------------------------------
    // MODUL: LAPORAN PERKARA DIPUTUS (RK4)
    // ---------------------------------------------------------------------
    Route::controller(LaporanPerkaraDiputusController::class)
        ->prefix('laporan-perkara-diputus')
        ->name('laporan.diputus.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/sync', function () {
                Artisan::queue('sync:perkara-diputus', [
                    '--start' => date('Y-m-d'),
                    '--end' => date('Y-m-d')
                ]);
                return back()->with('success', 'Sinkronisasi RK4 sedang berjalan.');
            })->name('sync');
        });

    // ---------------------------------------------------------------------
    // MODUL: REKAP EKSEKUSI
    // ---------------------------------------------------------------------
    Route::controller(RekapEksekusiController::class)
        ->prefix('eksekusi')
        ->name('laporan.eksekusi.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/detail', 'detail')->name('detail');
        });

    // ---------------------------------------------------------------------
    // MODUL: LAPORAN BANDING (RK1 & RK2)
    // ---------------------------------------------------------------------
    Route::prefix('laporan/banding')->name('laporan.banding.')->group(function () {

        // RK1 - Perkara Banding yang Diterima
        Route::controller(RK1Controller::class)->group(function () {
            Route::get('/diterima', 'index')->name('diterima');
            Route::get('/detail', 'detail')->name('detail');
            Route::get('/diterima/export', 'export')->name('diterima.export');
        });

        // RK2 - Perkara Banding yang Diputus
        Route::controller(RK2Controller::class)->group(function () {
            Route::get('/putus', 'index')->name('putus');
            Route::get('/putus/detail', 'detail')->name('putus.detail');
            Route::get('/putus/export', 'export')->name('putus.export');
        });

        // Statistik Jenis Perkara Banding
        Route::controller(JenisPerkaraBandingController::class)->group(function () {
            Route::get('/jenis-perkara', 'index')->name('jenis');
            Route::get('/jenis-perkara/export', 'export')->name('jenis.export');
            Route::get('/jenis-perkara/detail/{id_jenis}', 'detail')->name('jenis.detail');
        });
    });

    // ---------------------------------------------------------------------
    // MODUL: SISA PANJAR
    // ---------------------------------------------------------------------
    Route::controller(SisaPanjarController::class)
        ->prefix('sisa-panjar')
        ->name('sisa.panjar.')
        ->group(function () {
            Route::get('/', 'index')->name('menu');
            Route::get('/pertama', 'SisaPanjarPertama')->name('pertama');
            Route::get('/banding', 'SisaPanjarBanding')->name('banding');
            Route::get('/kasasi', 'SisaPanjarKasasi')->name('kasasi');
            Route::get('/pk', 'SisaPanjarPK')->name('pk');
            Route::get('/detail', 'detail')->name('detail');
        });

    // ---------------------------------------------------------------------
    // MODUL: MANAJEMEN PENGGUNA
    // ---------------------------------------------------------------------
    Route::controller(UserController::class)
        ->prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}/update', 'update')->name('update');
            Route::delete('/{id}/delete', 'destroy')->name('destroy');
        });

    // ---------------------------------------------------------------------
    // MODUL: AKTA CERAI
    // ---------------------------------------------------------------------
    Route::controller(AktaCeraiController::class)
        ->prefix('akta-cerai')
        ->name('akta-cerai.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail/{satker}', 'detail')->name('detail');
            Route::get('/export', 'export')->name('export');
            Route::get('/export-detail', 'exportDetail')->name('export-detail');
        });

    // ---------------------------------------------------------------------
    // MODUL: PENGADUAN (SIWAS)
    // ---------------------------------------------------------------------
    Route::controller(PengaduanController::class)
        ->prefix('pengaduan')
        ->name('pengaduan.')
        ->group(function () {
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

    // ---------------------------------------------------------------------
    // MODUL: PERATURAN (JDIH)
    // ---------------------------------------------------------------------
    Route::controller(PeraturanController::class)
        ->prefix('peraturan')
        ->name('peraturan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

    // ---------------------------------------------------------------------
    // MODUL: ARSIP DIGITAL PERKARA
    // ---------------------------------------------------------------------
    Route::controller(RetensiArsipPerkaraController::class)
        ->prefix('retensi-arsip-perkara')
        ->name('retensi-arsip.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/detail/{id}', 'show')->name('show');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });

    // ---------------------------------------------------------------------
    // MODUL: MONITORING SINKRONISASI DATA
    // ---------------------------------------------------------------------
    Route::prefix('admin/monitoring-sync')
        ->name('admin.sync.')
        ->group(function () {
            Route::get('/', [SyncMonitoringController::class, 'index'])->name('index');
            Route::get('/status-json', [SyncMonitoringController::class, 'getStatusJson'])->name('status_json');
            Route::post('/start-rk4', function () {
                Artisan::queue('sync:perkara-diputus', [
                    '--start' => date('Y-m-01'),
                    '--end' => date('Y-m-d')
                ]);
                return response()->json(['status' => 'started']);
            })->name('start_rk4');
        });

    // ---------------------------------------------------------------------
    // MODUL: BANK PUTUSAN
    // ---------------------------------------------------------------------
    Route::get('/bank-putusan', [BankPutusanController::class, 'index'])->name('bank.index');
    Route::post('/bank-putusan/upload', [BankPutusanController::class, 'upload'])->name('bank.upload');

    // ---------------------------------------------------------------------
    // MODUL: ARSIP AKTIF
    // ---------------------------------------------------------------------
    Route::prefix('arsip-aktif')->name('arsip-aktif.')->group(function () {
        Route::get('/', [ArsipAktifController::class, 'index'])->name('index');
        Route::post('/store', [ArsipAktifController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ArsipAktifController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ArsipAktifController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ArsipAktifController::class, 'destroy'])->name('destroy');
        Route::get('/get-perkara', [ArsipAktifController::class, 'getPerkaraSiappta'])->name('get-perkara');
    });

    // ---------------------------------------------------------------------
    // MODUL: MONITORING E-LAPORAN
    // ---------------------------------------------------------------------
    Route::get('/monitoring-elaporan', [MonitoringElaporanController::class, 'index'])->name('monitoring.index');

    // ---------------------------------------------------------------------
    // MODUL: INPUT PERKARA
    // ---------------------------------------------------------------------
    Route::get('/input-perkara', [InputDataController::class, 'index'])->name('input.index');
    Route::get('/input-perkara/detail', [InputDataController::class, 'detail'])->name('input.detail');
});
