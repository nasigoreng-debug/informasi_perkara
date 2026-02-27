<?php

namespace App\Http\Controllers;

use App\Services\SisaPanjarService;
use Illuminate\Http\Request;
use App\Models\ActivityLog; // Tambahkan pemanggilan Model Log

class SisaPanjarController extends Controller
{
    protected $sisaService;

    public function __construct(SisaPanjarService $sisaService)
    {
        $this->sisaService = $sisaService;
    }

    /**
     * Halaman Menu Utama dengan Ringkasan Log
     */
    public function index()
    {
        $banding = $this->sisaService->getSisaPanjarData('banding');
        $kasasi  = $this->sisaService->getSisaPanjarData('kasasi');
        $pk      = $this->sisaService->getSisaPanjarData('pk');

        $totalBanding = collect($banding)->sum('sisa');
        $totalKasasi  = collect($kasasi)->sum('sisa');
        $totalPK      = collect($pk)->sum('sisa');

        // LOG: Akses Menu Utama Sisa Panjar
        ActivityLog::record(
            'Akses Menu Sisa Panjar',
            'Keuangan',
            "Membuka menu utama monitoring sisa panjar tingkat Banding, Kasasi, dan PK."
        );

        return view('sisa_panjar.menu', [
            'totalBanding' => $totalBanding,
            'totalKasasi'  => $totalKasasi,
            'totalPK'      => $totalPK,
        ]);
    }

    public function SisaPanjarPertama()
    {
        $perkara = $this->sisaService->getSisaPanjarData('pertama');

        // LOG: Lihat Detail Sisa Panjar TK.I
        ActivityLog::record('Lihat Sisa Panjar', 'Keuangan', "Melihat rincian sisa panjar biaya perkara Tingkat Pertama.");

        return view('sisa_panjar.sisa_panjar_pertama', [
            'data' => collect($perkara),
            'label' => 'Tingkat Pertama'
        ]);
    }

    public function SisaPanjarBanding()
    {
        $perkara = $this->sisaService->getSisaPanjarData('banding');

        // LOG: Lihat Detail Sisa Panjar Banding
        ActivityLog::record('Lihat Sisa Panjar', 'Keuangan', "Melihat rincian sisa panjar biaya perkara Tingkat Banding.");

        return view('sisa_panjar.sisa_panjar_banding', [
            'data' => collect($perkara),
            'label' => 'Banding'
        ]);
    }

    public function SisaPanjarKasasi()
    {
        $perkara = $this->sisaService->getSisaPanjarData('kasasi');

        // LOG: Lihat Detail Sisa Panjar Kasasi
        ActivityLog::record('Lihat Sisa Panjar', 'Keuangan', "Melihat rincian sisa panjar biaya perkara Tingkat Kasasi.");

        return view('sisa_panjar.sisa_panjar_kasasi', [
            'data' => collect($perkara),
            'label' => 'Kasasi'
        ]);
    }

    public function SisaPanjarPK()
    {
        $perkara = $this->sisaService->getSisaPanjarData('pk');

        // LOG: Lihat Detail Sisa Panjar PK
        ActivityLog::record('Lihat Sisa Panjar', 'Keuangan', "Melihat rincian sisa panjar biaya perkara Tingkat PK.");

        return view('sisa_panjar.sisa_panjar_pk', [
            'data' => collect($perkara),
            'label' => 'PK'
        ]);
    }
}
