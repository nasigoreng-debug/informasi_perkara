<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class SisaPanjarController extends Controller
{
    // Halaman Menu Utama (4 Kartu) - URL: /sisa-panjar
    public function index()
    {
        // 1. Ambil data untuk 4 Kartu Statistik
        $rekap = DB::table('rekap_sisa_panjar')
            ->select('jenis', DB::raw('count(*) as total_perkara'), DB::raw('sum(sisa) as total_sisa'))
            ->groupBy('jenis')
            ->get();

        // 2. Ambil 8 baris riwayat sinkronisasi terbaru (Tambahkan bagian ini)
        $logs = DB::table('sync_logs')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // 3. Kirim kedua variabel ke view
        // Pastikan nama view sesuai (sisa_panjar.index atau sisa_panjar.menu)
        return view('sisa_panjar.menu', compact('rekap', 'logs'));
    }

    // Fungsi Internal untuk Halaman Tabel Satker
    private function renderRekap($jenis, $label)
    {
        $data = DB::table('rekap_sisa_panjar')
            ->where('jenis', $jenis)
            ->select(
                'satker_key',
                DB::raw('count(*) as total_perkara'),
                DB::raw('sum(sisa) as total_sisa'),
                DB::raw('MAX(updated_at) as last_update')
            )
            ->groupBy('satker_key')
            ->get();

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

    // Halaman Detail Perkara per Satker
    public function detail(Request $request)
    {
        $satker = $request->get('satker');
        $jenis = $request->get('jenis');

        $listPerkara = DB::table('rekap_sisa_panjar')
            ->where('jenis', $jenis)
            ->where('satker_key', $satker)
            ->orderByDesc('selisih_bulan')
            ->get();

        return view('sisa_panjar.detail_sisa', compact('listPerkara', 'satker', 'jenis'));
    }
}
