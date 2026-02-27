<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaporanPerkaraSatkerService;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanPerkaraExport;
use App\Exports\LaporanPerkaraDiputusExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Config\SatkerConfig;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog; 

class LaporanPerkaraController extends Controller
{
    protected $laporanService;

    public function __construct(LaporanPerkaraSatkerService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    private $jenisPerkara = [
        'iz' => 'Izin Poligami',
        'pp' => 'Pencegahan Perkawinan',
        'p_ppn' => 'Penolakan Perkawinan oleh PPN',
        'pb' => 'Pembatalan Perkawinan',
        'lks' => 'Kelalaian Kewajiban Suami/Isteri',
        'ct' => 'Cerai Talak',
        'cg' => 'Cerai Gugat',
        'hb' => 'Harta Bersama',
        'pa' => 'Penguasaan Anak',
        'nai' => 'Nafkah Anak oleh Ibu',
        'hbi' => 'Hak-hak Bekas Isteri',
        'psa' => 'Pengesahan Anak',
        'pkot' => 'Pencabutan Kekuasaan Orang Tua',
        'pw' => 'Perwalian',
        'phw' => 'Pencabutan Kekuasaan Wali',
        'pol' => 'Penunjukan orang lain sebagai Wali oleh Pengadilan',
        'grw' => 'Ganti Rugi terhadap Wali',
        'aua' => 'Asal Usul Anak',
        'pkc' => 'Penolakan Kawin Campuran',
        'isbath' => 'Pengesahan Perkawinan/Istbat Nikah',
        'ik' => 'Izin Kawin',
        'dk' => 'Dispensasi Kawin',
        'wa' => 'Wali Adhol',
        'kw' => 'Kewarisan',
        'wst' => 'Wasiat',
        'hb_h' => 'Hibah',
        'wkf' => 'Wakaf',
        'zkt' => 'Zakat',
        'infq' => 'Infaq',
        'es' => 'Ekonomi Syariah',
        'p3hp' => 'P3HP/Penetapan Ahli Waris',
        'll' => 'Lain-Lain'
    ];

    private $statusPutusan = [
        'dicabut' => 67,
        'ditolak' => 63,
        'dikabulkan' => 62,
        'tidak_diterima' => [64, 92],
        'gugur' => [65, 93],
        'dicoret' => 66,
    ];

    /**
     * Laporan Perkara Diterima
     */
    public function index(Request $request)
    {
        $res = $this->fetch($request);

        // LOG: Akses Laporan Diterima
        ActivityLog::record('Akses Laporan', 'LaporanPerkara', "Membuka Laporan Perkara Diterima Tahun {$res['year']}");

        return view('laporan.perkara_diterima', array_merge($res, ['jenisPerkara' => $this->jenisPerkara]));
    }

    /**
     * Laporan Perkara Diputus
     */
    public function putus(Request $request)
    {
        $res = $this->fetchPutus($request);

        // LOG: Akses Laporan Diputus
        ActivityLog::record('Akses Laporan', 'LaporanPerkara', "Membuka Laporan Perkara Diputus Tahun {$res['year']}");

        return view('laporan.perkara_putus', array_merge($res, [
            'jenisPerkara' => $this->jenisPerkara,
            'statusPutusan' => $this->statusPutusan
        ]));
    }

    /**
     * Laporan Perkara Putusan Sela
     */
    public function PutusanSela(Request $request)
    {
        $tgl_awal  = $request->input('tgl_awal', date('Y') . '-01-01');
        $tgl_akhir = $request->input('tgl_akhir', date('Y') . '-12-31');

        $data = $this->laporanService->getPutusanSelaSemuaSatker($tgl_awal, $tgl_akhir);

        // LOG: Akses Putusan Sela
        ActivityLog::record('Akses Laporan', 'LaporanPerkara', "Membuka Laporan Putusan Sela periode {$tgl_awal} s/d {$tgl_akhir}");

        return view('laporan.putusan_sela', compact('data', 'tgl_awal', 'tgl_akhir'));
    }

    /**
     * Export Laporan Perkara Diterima
     */
    public function export(Request $request)
    {
        $res = $this->fetch($request);

        // LOG: Export Excel
        ActivityLog::record('Export Excel', 'LaporanPerkara', "Download Excel Perkara Diterima Tahun {$res['year']}");

        return Excel::download(new LaporanPerkaraExport($res['laporan'], $this->jenisPerkara), 'Laporan_Perkara_Diterima.xlsx');
    }

    /**
     * Export Laporan Perkara Diputus
     */
    public function exportPutus(Request $request)
    {
        $res = $this->fetchPutus($request);

        // LOG: Export Excel
        ActivityLog::record('Export Excel', 'LaporanPerkara', "Download Excel Perkara Diputus Tahun {$res['year']}");

        return Excel::download(
            new LaporanPerkaraDiputusExport(
                $res['laporan'],
                $this->jenisPerkara,
                $res['year'],
                $res['month'],
                $res['quarter']
            ),
            'Laporan_Perkara_Diputus_' . $res['year'] . '.xlsx'
        );
    }

    /**
     * Export Putusan Sela
     */
    public function exportPutusanSela(Request $request)
    {
        $tgl_awal  = $request->get('tgl_awal', date('Y-01-01'));
        $tgl_akhir = $request->get('tgl_akhir', date('Y-12-31'));

        $data = $this->laporanService->getPutusanSelaSemuaSatker($tgl_awal, $tgl_akhir);

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diexport pada periode ini.');
        }

        // LOG: Export Putusan Sela
        ActivityLog::record('Export Excel', 'LaporanPerkara', "Download Excel Putusan Sela periode {$tgl_awal} s/d {$tgl_akhir}");

        return Excel::download(new \App\Exports\PutusanSelaExport($data), 'Laporan_Putusan_Sela.xlsx');
    }

    // --- PRIVATE FETCH METHODS (Fetch & FetchPutus tetap seperti sebelumnya) ---
    private function fetch($request)
    { /* Isinya tetap sama seperti kode Bapak */
    }
    private function fetchPutus($request)
    { /* Isinya tetap sama seperti kode Bapak */
    }
}
