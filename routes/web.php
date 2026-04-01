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
    MonitoringElaporanController
};

/*
|--------------------------------------------------------------------------
| SISTEM INFORMASI PERKARA - PTA BANDUNG
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC ROUTES (NO AUTHENTICATION)
// ==========================================
Route::get('/jadwal-sidang/public', [SidangController::class, 'index_public'])->name('sidang.index_public');
Route::get('/jdih-ptabandung', [PeraturanController::class, 'index_public'])->name('peraturan.public');
Route::get('/arsip-perkara/public', [RetensiArsipPerkaraController::class, 'index_public'])->name('arsip.public');

// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 3. PRIVATE ROUTES (AUTHENTICATED USERS ONLY)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // LANDING PAGES & MAIN NAVIGATION
    // ==========================================
    Route::get('/', fn() => view('welcome'))->name('welcome');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitoring', fn() => view('monitoring'))->name('monitoring');
    Route::get('/laporan-utama', fn() => view('laporan-utama'))->name('laporan-utama');
    Route::get('/administrasi', fn() => view('administrasi'))->name('administrasi');
    Route::get('/kinerja', [KinerjaController::class, 'index'])->name('kinerja.index');
    Route::get('/activity-log', fn() => view('activity_log'))->name('activity.log');
    Route::get('/under', fn() => view('errors.under_construction'))->name('errors.under_construction');

    // Sisa Panjar Menu
    Route::get('/sisa-panjar', fn() => view('sisa_panjar_menu'))->name('sisa.panjar.menu');

    // ==========================================
    // MODULE: COURT SCHEDULE MONITORING
    // ==========================================
    Route::controller(SidangController::class)
        ->prefix('jadwal-sidang')
        ->name('sidang.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });

    // ==========================================
    // MODULE: COURT CALENDAR MONITORING
    // ==========================================
    Route::controller(CourtCalendarController::class)
        ->prefix('court-calendar')
        ->name('court-calendar.')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/detail/{satker}', 'detail')->name('detail');
            Route::get('/export', 'export')->name('export');
            Route::get('/export-detail/{satker}', 'exportDetail')->name('export-detail');
        });

    // ==========================================
    // MODULE: CASSATION REPORT
    // ==========================================
    Route::controller(LaporanKasasiController::class)
        ->prefix('kasasi')
        ->name('kasasi.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail', 'detail')->name('detail');
            Route::put('/upload-pdf/{perkara_id}', 'uploadPdf')->name('upload');
            Route::get('/export', 'export')->name('export');
        });

    // ==========================================
    // MODULE: INCOMING MAIL
    // ==========================================
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

    // ==========================================
    // MODULE: OUTGOING MAIL
    // ==========================================
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

    // ==========================================
    // MODULE: DECREE LETTER (SK)
    // ==========================================
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

    // ==========================================
    // MODULE: RECEIVED CASES REPORT (RK3)
    // ==========================================
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

    // ==========================================
    // MODULE: DECIDED CASES REPORT (RK4)
    // ==========================================
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

    // ==========================================
    // MODULE: EXECUTION SUMMARY
    // ==========================================
    Route::controller(RekapEksekusiController::class)
        ->prefix('eksekusi')
        ->name('laporan.eksekusi.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/detail', 'detail')->name('detail');
        });

    // ==========================================
    // MODULE: APPEAL REPORTS (RK1 & RK2)
    // ==========================================
    Route::prefix('laporan/banding')->name('laporan.banding.')->group(function () {

        // RK1 - Received Appeal Cases
        Route::controller(RK1Controller::class)->group(function () {
            Route::get('/diterima', 'index')->name('diterima');
            Route::get('/detail', 'detail')->name('detail');
            Route::get('/diterima/export', 'export')->name('diterima.export');
        });

        // RK2 - Decided Appeal Cases
        Route::controller(RK2Controller::class)->group(function () {
            Route::get('/putus', 'index')->name('putus');
            Route::get('/putus/detail', 'detail')->name('putus.detail');
            Route::get('/putus/export', 'export')->name('putus.export');
        });

        // Case Type Statistics
        Route::controller(JenisPerkaraBandingController::class)->group(function () {
            Route::get('/jenis-perkara', 'index')->name('jenis');
            Route::get('/jenis-perkara/export', 'export')->name('jenis.export');
            Route::get('/jenis-perkara/detail/{id_jenis}', 'detail')->name('jenis.detail');
        });
    });

    // ==========================================
    // MODULE: REMAINING ADVANCE FUNDS MONITORING
    // ==========================================
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

    // ==========================================
    // MODULE: USER ADMINISTRATION
    // ==========================================
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

    // ==========================================
    // MODULE: DIVORCE DECREE
    // ==========================================
    Route::controller(AktaCeraiController::class)
        ->prefix('akta-cerai')
        ->name('akta-cerai.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail/{satker}', 'detail')->name('detail');
            Route::get('/export', 'export')->name('export');
            Route::get('/export-detail', 'exportDetail')->name('export-detail');
        });

    // ==========================================
    // MODULE: COMPLAINTS (SIWAS)
    // ==========================================
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

    // ==========================================
    // MODULE: REGULATIONS COLLECTION
    // ==========================================
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

    // ==========================================
    // MODULE: DIGITAL CASE ARCHIVES
    // ==========================================
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

    // ==========================================
    // MODULE: SYNCHRONIZATION MONITORING
    // ==========================================
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

    // ==========================================
    // MODULE: DECISION BANK
    // ==========================================
    Route::get('/bank-putusan', [BankPutusanController::class, 'index'])->name('bank.index');
    Route::post('/bank-putusan/upload', [BankPutusanController::class, 'upload'])->name('bank.upload');

    // ==========================================
    // MODULE: ACTIVE ARCHIVES
    // ==========================================
    Route::prefix('arsip-aktif')->name('arsip-aktif.')->group(function () {
        Route::get('/', [ArsipAktifController::class, 'index'])->name('index');
        Route::post('/store', [ArsipAktifController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ArsipAktifController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ArsipAktifController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ArsipAktifController::class, 'destroy'])->name('destroy');
        Route::get('/get-perkara', [ArsipAktifController::class, 'getPerkaraSiappta'])->name('get-perkara');
    });

    // ==========================================
    // MODULE: E-REPORT MONITORING
    // ==========================================
    Route::get('/monitoring-elaporan', [MonitoringElaporanController::class, 'index'])->name('monitoring.index');
});
