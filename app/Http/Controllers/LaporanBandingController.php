<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BandingService;
use App\Exports\RK1Export;
use App\Exports\RK2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ActivityLog; // Tambahkan pemanggilan Model Log

class LaporanBandingController extends Controller
{
    protected $bandingService;

    public function __construct(BandingService $bandingService)
    {
        $this->bandingService = $bandingService;
    }

    /**
     * Laporan RK1 - Perkara Banding Diterima
     */
    public function diterima(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekap($tgl_awal, $tgl_akhir);
        $summary = $this->bandingService->getSummary();

        // LOG: Akses Monitoring RK1
        ActivityLog::record('Akses RK1', 'LaporanBanding', "Membuka Monitoring RK1 periode {$tgl_awal} s/d {$tgl_akhir}");

        return view('laporan.banding_diterima', compact('results', 'summary', 'tgl_awal', 'tgl_akhir'));
    }

    /**
     * Laporan RK2 - Perkara Banding Diputus
     */
    public function diputus(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK2($tgl_awal, $tgl_akhir);

        // LOG: Akses Monitoring RK2
        ActivityLog::record('Akses RK2', 'LaporanBanding', "Membuka Monitoring RK2 periode {$tgl_awal} s/d {$tgl_akhir}");

        return view('laporan.banding_putus', compact('results', 'tgl_awal', 'tgl_akhir'));
    }

    public function detail(Request $request)
    {
        $details = $this->bandingService->getDetailPerkara($request->satker, $request->jenis, $request->tgl_awal, $request->tgl_akhir);

        // LOG: Lihat Detail RK1
        ActivityLog::record('Lihat Detail RK1', 'LaporanBanding', "Melihat rincian RK1 Satker: {$request->satker} Jenis: {$request->jenis}");

        return view('laporan.banding_detail', compact('details', 'request'));
    }

    public function detailPutus(Request $request)
    {
        $satker = $request->satker;
        $jenis = $request->jenis;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        $details = $this->bandingService->getDetailPutusan($satker, $jenis, $tgl_awal, $tgl_akhir);

        // LOG: Lihat Detail RK2
        ActivityLog::record('Lihat Detail RK2', 'LaporanBanding', "Melihat rincian RK2 Satker: {$satker} Jenis: {$jenis}");

        return view('laporan.banding_putus_detail', compact('details', 'request'));
    }

    /**
     * Export Excel RK1
     */
    public function exportRK1(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekap($tgl_awal, $tgl_akhir);

        // LOG: Export RK1
        ActivityLog::record('Export RK1', 'LaporanBanding', "Mendownload Excel RK1 periode {$tgl_awal} s/d {$tgl_akhir}");

        return Excel::download(new RK1Export($results, $tgl_awal, $tgl_akhir), "Laporan_RK1_{$tgl_awal}_sd_{$tgl_akhir}.xlsx");
    }

    /**
     * Export Excel RK2
     */
    public function exportRK2(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK2($tgl_awal, $tgl_akhir);

        // LOG: Export RK2
        ActivityLog::record('Export RK2', 'LaporanBanding', "Mendownload Excel RK2 periode {$tgl_awal} s/d {$tgl_akhir}");

        return Excel::download(new RK2Export($results, $tgl_awal, $tgl_akhir), "Laporan_RK2_{$tgl_awal}_sd_{$tgl_akhir}.xlsx");
    }

    /**
     * Statistik Per Jenis Perkara
     */
    public function perJenis(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-12-31'));

        $results = $this->bandingService->getRekapJenisPerkara($tgl_awal, $tgl_akhir);

        // LOG: Akses Rekap Jenis Perkara
        ActivityLog::record('Akses Rekap Jenis', 'LaporanBanding', "Melihat rekap per jenis perkara periode {$tgl_awal} s/d {$tgl_akhir}");

        return view('laporan.banding_jenis', compact('results', 'tgl_awal', 'tgl_akhir'));
    }

    /**
     * Export Excel Jenis Perkara
     */
    public function exportJenis(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        // LOG: Export Jenis Perkara
        ActivityLog::record('Export Rekap Jenis', 'LaporanBanding', "Mendownload Excel rekap jenis perkara periode {$tgl_awal} s/d {$tgl_akhir}");

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\JenisPerkaraExport($tgl_awal, $tgl_akhir),
            "rekap_jenis_perkara_{$tgl_awal}_sd_{$tgl_akhir}.xlsx"
        );
    }
}
