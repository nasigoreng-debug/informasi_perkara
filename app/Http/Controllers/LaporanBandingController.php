<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BandingService;
use App\Exports\RK1Export;
use App\Exports\RK2Export;
use Maatwebsite\Excel\Facades\Excel;

class LaporanBandingController extends Controller
{
    protected $bandingService;
    public function __construct(BandingService $bandingService)
    {
        $this->bandingService = $bandingService;
    }

    public function diterima(Request $request)
    {
        // tgl_awal default: 01 Januari tahun berjalan (agar data setahun muncul)
        // tgl_akhir default: Hari ini (25-02-2026)
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekap($tgl_awal, $tgl_akhir);
        $summary = $this->bandingService->getSummary();

        return view('laporan.banding_diterima', compact('results', 'summary', 'tgl_awal', 'tgl_akhir'));
    }

    public function diputus(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK2($tgl_awal, $tgl_akhir);
        return view('laporan.banding_putus', compact('results', 'tgl_awal', 'tgl_akhir'));
    }

    public function detail(Request $request)
    {
        $details = $this->bandingService->getDetailPerkara($request->satker, $request->jenis, $request->tgl_awal, $request->tgl_akhir);
        return view('laporan.banding_detail', compact('details', 'request'));
    }

    public function detailPutus(Request $request)
    {
        // Mengambil parameter dari URL
        $satker = $request->satker;
        $jenis = $request->jenis;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        // Memanggil Service untuk mengambil data rincian
        $details = $this->bandingService->getDetailPutusan($satker, $jenis, $tgl_awal, $tgl_akhir);

        return view('laporan.banding_putus_detail', compact('details', 'request'));
    }

    public function exportRK1(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekap($tgl_awal, $tgl_akhir);

        return Excel::download(new RK1Export($results, $tgl_awal, $tgl_akhir), "Laporan_RK1_{$tgl_awal}_sd_{$tgl_akhir}.xlsx");
    }

    public function exportRK2(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->input('tgl_akhir', date('Y-m-d'));

        $results = $this->bandingService->getRekapRK2($tgl_awal, $tgl_akhir);

        // Tambahkan variabel tgl_awal dan tgl_akhir di sini
        return Excel::download(new RK2Export($results, $tgl_awal, $tgl_akhir), "Laporan_RK2_{$tgl_awal}_sd_{$tgl_akhir}.xlsx");
    }
}
