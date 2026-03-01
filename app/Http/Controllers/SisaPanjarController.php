<?php

namespace App\Http\Controllers;

use App\Services\SisaPanjarService;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class SisaPanjarController extends Controller
{
    protected $sisaService;

    public function __construct(SisaPanjarService $sisaService)
    {
        $this->sisaService = $sisaService;
    }

    // Halaman Menu Utama (4 Kartu)
    public function index()
    {
        return view('sisa_panjar.menu', [
            'totalPertama' => collect($this->sisaService->getSisaPanjarData('pertama'))->sum('sisa'),
            'totalBanding' => collect($this->sisaService->getSisaPanjarData('banding'))->sum('sisa'),
            'totalKasasi'  => collect($this->sisaService->getSisaPanjarData('kasasi'))->sum('sisa'),
            'totalPK'      => collect($this->sisaService->getSisaPanjarData('pk'))->sum('sisa'),
        ]);
    }

    // Fungsi Internal untuk Render Rekap
    private function renderRekap($jenis, $label)
    {
        $data = collect($this->sisaService->getSisaPanjarData($jenis));
        ActivityLog::record('Monitoring', 'Keuangan', "Melihat Rekap Sisa Panjar $label");

        return view('sisa_panjar.index_sisa', compact('data', 'label', 'jenis'));
    }

    public function SisaPanjarPertama()
    {
        return $this->renderRekap('pertama', 'Tingkat Pertama');
    }
    public function SisaPanjarBanding()
    {
        return $this->renderRekap('banding', 'Tingkat Banding');
    }
    public function SisaPanjarKasasi()
    {
        return $this->renderRekap('kasasi', 'Tingkat Kasasi');
    }
    public function SisaPanjarPK()
    {
        return $this->renderRekap('pk', 'Peninjauan Kembali');
    }

    // Halaman Detail (Pindah Halaman)
    public function detail(Request $request)
    {
        $satker = $request->get('satker');
        $jenis = $request->get('jenis');

        $allData = collect($this->sisaService->getSisaPanjarData($jenis));
        $listPerkara = $allData->where('satker_key', strtoupper($satker));

        ActivityLog::record('Monitoring', 'Keuangan', "Melihat Detail Sisa Panjar $jenis Satker: $satker");

        return view('sisa_panjar.detail_sisa', [
            'data' => $listPerkara,
            'satker' => $satker,
            'jenis' => $jenis
        ]);
    }
}
