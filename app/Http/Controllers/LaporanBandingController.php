<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BandingService;
use App\Exports\RK1Export;
use App\Exports\RK2Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ActivityLog;

class LaporanBandingController extends Controller
{
    protected $bandingService;

    public function __construct(BandingService $bandingService)
    {
        $this->bandingService = $bandingService;
    }

    /**
     * Laporan RK1 - Perkara Banding Diterima (Rincian Jenis)
     */
    public function diterima(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK1($tgl_awal, $tgl_akhir);

        ActivityLog::record('Akses RK1', 'LaporanBanding', "Membuka Monitoring RK1 periode {$tgl_awal} s/d {$tgl_akhir}");

        return view('laporan.banding_diterima', compact('results', 'tgl_awal', 'tgl_akhir'));
    }

    /**
     * Laporan RK2 - Perkara Banding Diputus (Keadaan Perkara)
     */
    public function diputus(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK2($tgl_awal, $tgl_akhir);

        ActivityLog::record('Akses RK2', 'LaporanBanding', "Membuka Monitoring RK2 periode {$tgl_awal} s/d {$tgl_akhir}");

        return view('laporan.banding_putus', compact('results', 'tgl_awal', 'tgl_akhir'));
    }

    public function detail(Request $request)
    {
        $details = $this->bandingService->getDetailPerkara($request->satker, $request->jenis, $request->tgl_awal, $request->tgl_akhir);
        ActivityLog::record('Lihat Detail RK1', 'LaporanBanding', "Melihat rincian RK1 Satker: {$request->satker} Jenis: {$request->jenis}");

        return view('laporan.banding_detail', compact('details', 'request'));
    }

    public function detailPutus(Request $request)
    {
        $details = $this->bandingService->getDetailPutusan($request->satker, $request->jenis, $request->tgl_awal, $request->tgl_akhir);
        ActivityLog::record('Lihat Detail RK2', 'LaporanBanding', "Melihat rincian RK2 Satker: {$request->satker} Jenis: {$request->jenis}");

        return view('laporan.banding_putus_detail', compact('details', 'request'));
    }

    public function exportRK1(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK1($tgl_awal, $tgl_akhir);
        ActivityLog::record('Export RK1', 'LaporanBanding', "Mendownload Excel RK1 periode {$tgl_awal} s/d {$tgl_akhir}");

        return Excel::download(new RK1Export($results, $tgl_awal, $tgl_akhir), "Laporan_RK1_{$tgl_awal}.xlsx");
    }

    public function exportRK2(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK2($tgl_awal, $tgl_akhir);
        ActivityLog::record('Export RK2', 'LaporanBanding', "Mendownload Excel RK2 periode {$tgl_awal} s/d {$tgl_akhir}");

        return Excel::download(new RK2Export($results, $tgl_awal, $tgl_akhir), "Laporan_RK2_{$tgl_awal}.xlsx");
    }
}
