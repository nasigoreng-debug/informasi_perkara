<?php

namespace App\Http\Controllers;

use App\Services\SisaPanjarService;
use Illuminate\Http\Request;

class SisaPanjarController extends Controller
{
    protected $sisaService;

    public function __construct(SisaPanjarService $sisaService)
    {
        $this->sisaService = $sisaService;
    }

    // Halaman Menu Utama
    public function index()
    {
        $banding = $this->sisaService->getSisaPanjarData('banding');
        $kasasi  = $this->sisaService->getSisaPanjarData('kasasi');
        $pk      = $this->sisaService->getSisaPanjarData('pk');

        return view('sisa_panjar.menu', [
            'totalBanding' => collect($banding)->sum('sisa'),
            'totalKasasi'  => collect($kasasi)->sum('sisa'),
            'totalPK'      => collect($pk)->sum('sisa'),
        ]);
    }

    public function SisaPanjarBanding()
    {
        $perkara = $this->sisaService->getSisaPanjarData('banding');
        return view('sisa_panjar.sisa_panjar_banding', [
            'data' => collect($perkara), // Wajib 'data'
            'label' => 'Banding'
        ]);
    }

    public function SisaPanjarKasasi()
    {
        $perkara = $this->sisaService->getSisaPanjarData('kasasi');
        return view('sisa_panjar.sisa_panjar_kasasi', [
            'data' => collect($perkara), // Wajib 'data'
            'label' => 'Kasasi'
        ]);
    }

    public function SisaPanjarPK()
    {
        $perkara = $this->sisaService->getSisaPanjarData('pk');
        return view('sisa_panjar.sisa_panjar_pk', [
            'data' => collect($perkara), // Wajib 'data'
            'label' => 'PK'
        ]);
    }
}
