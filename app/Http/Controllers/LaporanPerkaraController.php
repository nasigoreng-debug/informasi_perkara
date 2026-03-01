<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaporanPerkaraSatkerService; // Pastikan ini sesuai dengan nama file service Anda
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanPerkaraExport;
use App\Exports\LaporanPerkaraDiputusExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Config\SatkerConfig;
use Illuminate\Support\Facades\Log;

class LaporanPerkaraController extends Controller
{
    protected $laporanService;

    /**
     * Konstruktor untuk Inject Service
     */
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
        return view('laporan.perkara_diterima', array_merge($res, ['jenisPerkara' => $this->jenisPerkara]));
    }

    /**
     * Laporan Perkara Diputus
     */
    public function putus(Request $request)
    {
        $res = $this->fetchPutus($request);
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

        return view('laporan.putusan_sela', compact('data', 'tgl_awal', 'tgl_akhir'));
    }

    /**
     * Export Laporan Perkara Diterima
     */
    public function export(Request $request)
    {
        $res = $this->fetch($request);
        return Excel::download(new LaporanPerkaraExport($res['laporan'], $this->jenisPerkara), 'Laporan_Perkara_Diterima.xlsx');
    }

    /**
     * Export Laporan Perkara Diputus
     */
    public function exportPutus(Request $request)
    {
        $res = $this->fetchPutus($request);

        return Excel::download(
            new LaporanPerkaraDiputusExport(
                $res['laporan'],
                $this->jenisPerkara,
                $res['year'],
                $res['month'],
                $res['quarter']
            ),
            'Laporan_Perkara_Diputus_' . $res['year'] .
                (!empty($res['month']) ? '_Bulan_' . $res['month'] : '') .
                (!empty($res['quarter']) ? '_Triwulan_' . $res['quarter'] : '') .
                '.xlsx'
        );
    }

    /**
     * Fetch data untuk laporan perkara diterima
     */
    private function fetch($request)
    {
        $y = $request->get('tahun', date('Y'));
        $m = $request->get('bulan');
        $q = $request->get('triwulan');

        $laporan = [];
        $totals = array_fill_keys(array_keys($this->jenisPerkara), 0);
        $grandTotalJml = 0;

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $query = DB::connection($koneksi)->table('perkara as p')
                    ->whereYear('p.tanggal_pendaftaran', $y);

                if ($m) {
                    $query->whereMonth('p.tanggal_pendaftaran', $m);
                } elseif ($q) {
                    $range = [1 => [1, 3], 2 => [4, 6], 3 => [7, 9], 4 => [10, 12]];
                    $query->whereBetween(DB::raw('MONTH(p.tanggal_pendaftaran)'), [$range[$q][0], $range[$q][1]]);
                }

                $selects = ["COUNT(*) as jml"];
                foreach ($this->jenisPerkara as $key => $nama) {
                    $selects[] = "COUNT(CASE WHEN p.jenis_perkara_nama = '$nama' THEN 1 END) AS $key";
                }

                $data = $query->selectRaw(implode(", ", $selects))->first();

                $row = [
                    'no_urut' => SatkerConfig::getNomorUrut($koneksi),
                    'satker' => strtoupper($namaSatker),
                    'jml' => $data->jml ?? 0
                ];

                foreach ($this->jenisPerkara as $key => $nama) {
                    $val = $data->$key ?? 0;
                    $row[$key] = $val;
                    $totals[$key] += $val;
                }

                $laporan[] = (object) $row;
                $grandTotalJml += ($data->jml ?? 0);
            } catch (\Exception $e) {
                Log::error("Error fetch diterima {$koneksi}: " . $e->getMessage());
                $row = [
                    'no_urut' => SatkerConfig::getNomorUrut($koneksi),
                    'satker' => strtoupper($namaSatker),
                    'jml' => 0
                ];
                foreach ($this->jenisPerkara as $key => $n) $row[$key] = 0;
                $laporan[] = (object) $row;
                continue;
            }
        }

        usort($laporan, fn($a, $b) => $a->no_urut <=> $b->no_urut);

        $footer = ['no_urut' => 'TOTAL', 'satker' => 'JUMLAH KESELURUHAN', 'jml' => $grandTotalJml];
        foreach ($totals as $key => $val) $footer[$key] = $val;
        $laporan[] = (object) $footer;

        return [
            'laporan' => $laporan,
            'year' => $y,
            'month' => $m,
            'quarter' => $q
        ];
    }

    /**
     * Fetch data untuk laporan perkara diputus
     */
    private function fetchPutus($request)
    {
        $y = $request->get('tahun', date('Y'));
        $m = $request->get('bulan');
        $q = $request->get('triwulan');

        $laporan = [];

        $totals = array_fill_keys(array_keys($this->jenisPerkara), 0);
        $totalsStatus = [
            'dicabut' => 0,
            'ditolak' => 0,
            'dikabulkan' => 0,
            'tidak_diterima' => 0,
            'gugur' => 0,
            'dicoret' => 0
        ];
        $grandTotalJml = 0;
        $grandTotalSisaLalu = 0;
        $grandTotalDiterima = 0;

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $sisaLalu = DB::connection($koneksi)->table('perkara as p')
                    ->leftJoin('perkara_putusan as pu', 'p.perkara_id', '=', 'pu.perkara_id')
                    ->whereYear('p.tanggal_pendaftaran', '<', $y)
                    ->where(function ($q) use ($y) {
                        $q->whereNull('pu.tanggal_putusan')
                            ->orWhereYear('pu.tanggal_putusan', '>=', $y);
                    })
                    ->count(DB::raw('DISTINCT p.perkara_id'));

                $queryDiterima = DB::connection($koneksi)->table('perkara as p')
                    ->whereYear('p.tanggal_pendaftaran', $y);

                if ($m) {
                    $queryDiterima->whereMonth('p.tanggal_pendaftaran', $m);
                } elseif ($q) {
                    $range = [1 => [1, 3], 2 => [4, 6], 3 => [7, 9], 4 => [10, 12]];
                    $queryDiterima->whereBetween(DB::raw('MONTH(p.tanggal_pendaftaran)'), [$range[$q][0], $range[$q][1]]);
                }

                $diterima = $queryDiterima->count();

                $queryPutus = DB::connection($koneksi)->table('perkara as p')
                    ->join('perkara_putusan as pu', 'p.perkara_id', '=', 'pu.perkara_id')
                    ->whereYear('pu.tanggal_putusan', $y);

                if ($m) {
                    $queryPutus->whereMonth('pu.tanggal_putusan', $m);
                } elseif ($q) {
                    $range = [1 => [1, 3], 2 => [4, 6], 3 => [7, 9], 4 => [10, 12]];
                    $queryPutus->whereBetween(DB::raw('MONTH(pu.tanggal_putusan)'), [$range[$q][0], $range[$q][1]]);
                }

                $selects = ["COUNT(DISTINCT p.perkara_id) as jml"];
                foreach ($this->jenisPerkara as $key => $nama) {
                    $selects[] = "COUNT(CASE WHEN p.jenis_perkara_nama = '$nama' AND pu.status_putusan_id = 62 THEN 1 END) AS $key";
                }

                $selects[] = "COUNT(CASE WHEN pu.status_putusan_id = 67 THEN 1 END) AS dicabut";
                $selects[] = "COUNT(CASE WHEN pu.status_putusan_id = 63 THEN 1 END) AS ditolak";
                $selects[] = "COUNT(CASE WHEN pu.status_putusan_id = 62 THEN 1 END) AS dikabulkan";
                $selects[] = "COUNT(CASE WHEN pu.status_putusan_id IN (64, 92) THEN 1 END) AS tidak_diterima";
                $selects[] = "COUNT(CASE WHEN pu.status_putusan_id IN (65, 93) THEN 1 END) AS gugur";
                $selects[] = "COUNT(CASE WHEN pu.status_putusan_id = 66 THEN 1 END) AS dicoret";

                $data = $queryPutus->selectRaw(implode(", ", $selects))->first();

                $beban = $sisaLalu + $diterima;
                $totalDiputus = $data->jml ?? 0;
                $sisaAkhir = $beban - $totalDiputus;
                $persentase = ($totalDiputus > 0) ? round(($data->dikabulkan / $totalDiputus) * 100, 2) : 0;

                $row = [
                    'no_urut' => SatkerConfig::getNomorUrut($koneksi),
                    'satker' => strtoupper($namaSatker),
                    'sisa_tahun_lalu' => $sisaLalu,
                    'diterima' => $diterima,
                    'beban' => $beban,
                    'jml' => $totalDiputus,
                    'dicabut' => $data->dicabut ?? 0,
                    'ditolak' => $data->ditolak ?? 0,
                    'dikabulkan' => $data->dikabulkan ?? 0,
                    'tidak_diterima' => $data->tidak_diterima ?? 0,
                    'gugur' => $data->gugur ?? 0,
                    'dicoret' => $data->dicoret ?? 0,
                    'persentase' => $persentase,
                    'sisa' => $sisaAkhir
                ];

                foreach ($this->jenisPerkara as $key => $nama) {
                    $val = $data->$key ?? 0;
                    $row[$key] = $val;
                    $totals[$key] += $val;
                }

                $totalsStatus['dicabut'] += ($data->dicabut ?? 0);
                $totalsStatus['ditolak'] += ($data->ditolak ?? 0);
                $totalsStatus['dikabulkan'] += ($data->dikabulkan ?? 0);
                $totalsStatus['tidak_diterima'] += ($data->tidak_diterima ?? 0);
                $totalsStatus['gugur'] += ($data->gugur ?? 0);
                $totalsStatus['dicoret'] += ($data->dicoret ?? 0);

                $grandTotalJml += $totalDiputus;
                $grandTotalSisaLalu += $sisaLalu;
                $grandTotalDiterima += $diterima;

                $laporan[] = (object) $row;
            } catch (\Exception $e) {
                Log::error("Error fetch putus {$koneksi}: " . $e->getMessage());
                $row = [
                    'no_urut' => SatkerConfig::getNomorUrut($koneksi),
                    'satker' => strtoupper($namaSatker),
                    'sisa_tahun_lalu' => 0,
                    'diterima' => 0,
                    'beban' => 0,
                    'jml' => 0,
                    'dicabut' => 0,
                    'ditolak' => 0,
                    'dikabulkan' => 0,
                    'tidak_diterima' => 0,
                    'gugur' => 0,
                    'dicoret' => 0,
                    'persentase' => 0,
                    'sisa' => 0
                ];
                foreach ($this->jenisPerkara as $key => $nama) {
                    $row[$key] = 0;
                }
                $laporan[] = (object) $row;
                continue;
            }
        }

        usort($laporan, fn($a, $b) => $a->no_urut <=> $b->no_urut);

        $totalBeban = $grandTotalSisaLalu + $grandTotalDiterima;
        $totalSisaAkhir = $totalBeban - $grandTotalJml;
        $totalPersentase = ($grandTotalJml > 0) ? round(($totalsStatus['dikabulkan'] / $grandTotalJml) * 100, 2) : 0;

        $footer = [
            'no_urut' => 'TOTAL',
            'satker' => 'JUMLAH KESELURUHAN',
            'sisa_tahun_lalu' => $grandTotalSisaLalu,
            'diterima' => $grandTotalDiterima,
            'beban' => $totalBeban,
            'jml' => $grandTotalJml,
            'dicabut' => $totalsStatus['dicabut'],
            'ditolak' => $totalsStatus['ditolak'],
            'dikabulkan' => $totalsStatus['dikabulkan'],
            'tidak_diterima' => $totalsStatus['tidak_diterima'],
            'gugur' => $totalsStatus['gugur'],
            'dicoret' => $totalsStatus['dicoret'],
            'persentase' => $totalPersentase,
            'sisa' => $totalSisaAkhir
        ];

        foreach ($totals as $key => $val) {
            $footer[$key] = $val;
        }

        $laporan[] = (object) $footer;

        return [
            'laporan' => $laporan,
            'year' => $y,
            'month' => $m,
            'quarter' => $q,
        ];
    }

    public function exportPutusanSela(Request $request)
    {
        // Gunakan default yang sama dengan index agar data tidak kosong
        $tgl_awal  = $request->get('tgl_awal', '2026-01-01');
        $tgl_akhir = $request->get('tgl_akhir', '2026-12-31');

        // Ambil data dari service
        $data = $this->laporanService->getPutusanSelaSemuaSatker($tgl_awal, $tgl_akhir);

        // Cek jika data kosong, kembalikan dengan pesan error agar tidak download file zonk
        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diexport pada periode ini.');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PutusanSelaExport($data),
            'Laporan_Putusan_Sela.xlsx'
        );
    }
}
