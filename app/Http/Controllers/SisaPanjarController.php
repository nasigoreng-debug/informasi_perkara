<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class SisaPanjarController extends Controller
{
    // Halaman Menu Utama (4 Kartu) - URL: /sisa-panjar
    public function index(Request $request)
    {
        // 1. Ambil data untuk 4 Kartu Statistik
        $rekap = DB::table('rekap_sisa_panjar')
            ->select('jenis', DB::raw('count(*) as total_perkara'), DB::raw('sum(sisa) as total_sisa'))
            ->groupBy('jenis')
            ->get();

        // Hitung statistik untuk log
        $totalPerkara = $rekap->sum('total_perkara');
        $totalSisa = $rekap->sum('total_sisa');

        $jenisPanjar = [];
        foreach ($rekap as $item) {
            $jenisPanjar[] = $item->jenis . ': ' . $item->total_perkara . ' perkara';
        }
        $jenisInfo = implode(', ', $jenisPanjar);

        // 2. Ambil 8 baris riwayat sinkronisasi terbaru
        $logs = DB::table('sync_logs')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // ✅ LOG AKSES INDEX (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Akses Menu Sisa Panjar',
            'description' => "Total Perkara: {$totalPerkara} | Total Sisa: {$totalSisa} | Detail: {$jenisInfo}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Kirim kedua variabel ke view
        return view('sisa_panjar.menu', compact('rekap', 'logs'));
    }

    // Fungsi Internal untuk Halaman Tabel Satker
    private function renderRekap(Request $request, $jenis, $label)
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

        $totalPerkara = $data->sum('total_perkara');
        $totalSisa = $data->sum('total_sisa');

        // ✅ LOG REKAP (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Monitoring Sisa Panjar',
            'description' => "Melihat Rekap Sisa Panjar {$label} | Total Perkara: {$totalPerkara} | Total Sisa: {$totalSisa} | Jumlah Satker: " . $data->count(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('sisa_panjar.index_sisa', compact('data', 'label', 'jenis'));
    }

    public function SisaPanjarPertama(Request $request)
    {
        return $this->renderRekap($request, 'pertama', 'Tingkat Pertama');
    }

    public function SisaPanjarBanding(Request $request)
    {
        return $this->renderRekap($request, 'banding', 'Tingkat Banding');
    }

    public function SisaPanjarKasasi(Request $request)
    {
        return $this->renderRekap($request, 'kasasi', 'Tingkat Kasasi');
    }

    public function SisaPanjarPK(Request $request)
    {
        return $this->renderRekap($request, 'pk', 'Peninjauan Kembali');
    }

    // Halaman Detail Perkara per Satker
    public function detail(Request $request)
    {
        $satker = $request->get('satker');
        $jenis = $request->get('jenis');

        $listPerkara = DB::table('rekap_sisa_panjar')
            ->where('jenis', $jenis)
            ->where('satker_key', $satker)
            ->distinct()
            ->orderByDesc('selisih_bulan')
            ->get();

        $totalPerkara = $listPerkara->count();
        $totalSisa = $listPerkara->sum('sisa');

        // Mapping label jenis
        $labelJenis = '';
        if ($jenis == 'pertama') $labelJenis = 'Tingkat Pertama';
        elseif ($jenis == 'banding') $labelJenis = 'Tingkat Banding';
        elseif ($jenis == 'kasasi') $labelJenis = 'Tingkat Kasasi';
        elseif ($jenis == 'pk') $labelJenis = 'Peninjauan Kembali';

        // ✅ LOG DETAIL (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Detail Sisa Panjar',
            'description' => "Satker: {$satker} | Jenis: {$labelJenis} | Total Perkara: {$totalPerkara} | Total Sisa: {$totalSisa}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('sisa_panjar.detail_sisa', compact('listPerkara', 'satker', 'jenis'));
    }

    /**
     * Export Data Sisa Panjar ke Excel
     */
    public function export(Request $request)
    {
        $jenis = $request->get('jenis');

        $labelJenis = '';
        if ($jenis == 'pertama') $labelJenis = 'Tingkat Pertama';
        elseif ($jenis == 'banding') $labelJenis = 'Tingkat Banding';
        elseif ($jenis == 'kasasi') $labelJenis = 'Tingkat Kasasi';
        elseif ($jenis == 'pk') $labelJenis = 'Peninjauan Kembali';
        else $labelJenis = 'Semua Jenis';

        $query = DB::table('rekap_sisa_panjar');

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $data = $query->orderBy('jenis')->orderBy('satker_key')->get();

        $totalPerkara = $data->count();
        $totalSisa = $data->sum('sisa');

        // ✅ LOG EXPORT (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Export Excel Sisa Panjar',
            'description' => "Export data Sisa Panjar {$labelJenis} | Total Perkara: {$totalPerkara} | Total Sisa: {$totalSisa}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Redirect dengan info (implementasikan export sesuai kebutuhan)
        return redirect()->back()->with('info', "Export Excel untuk data Sisa Panjar {$labelJenis} akan diproses");
    }

    /**
     * Statistik Sisa Panjar
     */
    public function statistics(Request $request)
    {
        $rekap = DB::table('rekap_sisa_panjar')
            ->select('jenis', DB::raw('count(*) as total_perkara'), DB::raw('sum(sisa) as total_sisa'))
            ->groupBy('jenis')
            ->get();

        $totalPerkara = $rekap->sum('total_perkara');
        $totalSisa = $rekap->sum('total_sisa');

        $statByJenis = [];
        foreach ($rekap as $item) {
            $statByJenis[] = [
                'jenis' => $item->jenis,
                'total_perkara' => $item->total_perkara,
                'total_sisa' => $item->total_sisa
            ];
        }

        // ✅ LOG STATISTICS (PAKAI MODEL)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Statistik Sisa Panjar',
            'description' => "Total Perkara: {$totalPerkara} | Total Sisa: {$totalSisa}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'total_perkara' => $totalPerkara,
                'total_sisa' => $totalSisa,
                'by_jenis' => $statByJenis
            ]
        ]);
    }
}
