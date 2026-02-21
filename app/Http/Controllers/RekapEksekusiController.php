<?php

namespace App\Http\Controllers;

use App\Services\RekapEksekusiService;
use Illuminate\Http\Request;

class RekapEksekusiController extends Controller
{
    protected $rekapService;

    public function __construct(RekapEksekusiService $rekapService)
    {
        $this->rekapService = $rekapService;
    }

    public function index(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-01-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-12-31');

        // PROTEKSI: Mencegah tanggal akhir lebih kecil dari tanggal awal
        if ($tglAkhir < $tglAwal) {
            $tglAkhir = $tglAwal;
        }

        $data = $this->rekapService->getRekap($tglAwal, $tglAkhir);
        $summary = $this->rekapService->getSummary($data);
        $allTime = $this->rekapService->getAllTimeSummary();

        return view('eksekusi.index', compact('data', 'summary', 'allTime', 'tglAwal', 'tglAkhir'));
    }

    public function detail(Request $request)
    {
        $satker = $request->get('satker');
        $jenis = $request->get('jenis');

        // Ambil range tanggal dari request, jika tidak ada gunakan default tahun ini
        $tglAwal = $request->get('tgl_awal') ?? date('Y-01-01');
        $tglAkhir = $request->get('tgl_akhir') ?? date('Y-12-31');

        if (!$satker || !$jenis) {
            return redirect()->route('laporan.eksekusi.index')->with('error', 'Parameter tidak valid.');
        }

        try {
            $dataDetail = $this->rekapService->getDetailPerkara($satker, $jenis, $tglAwal, $tglAkhir);

            // Kirim tglAwal dan tglAkhir, JANGAN kirim $tahun lagi
            return view('eksekusi.detail', [
                'satker' => $satker,
                'jenis' => $jenis,
                'tglAwal' => $tglAwal,
                'tglAkhir' => $tglAkhir,
                'data' => $dataDetail
            ]);
        } catch (\Exception $e) {
            return redirect()->route('laporan.eksekusi.index')->with('error', 'Gagal memuat detail: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', date('Y-01-01'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-12-31'));

        try {
            $data = $this->rekapService->getRekap($tglAwal, $tglAkhir);
            $summary = $this->rekapService->getSummary($data);
            $filename = "rekap_eksekusi_{$tglAwal}_sd_{$tglAkhir}.csv";

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function () use ($data, $summary) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Satker', 'SISA LALU', 'DITERIMA', 'BEBAN', 'SELESAI', '% SELESAI', 'SISA KINI']);

                foreach ($data as $row) {
                    $r = (array) $row;
                    $persen = $r['BEBAN'] > 0 ? round(($r['SELESAI'] / $r['BEBAN']) * 100, 2) : 0;
                    fputcsv($file, [$r['satker'], $r['SISA'], $r['DITERIMA'], $r['BEBAN'], $r['SELESAI'], $persen . '%', $r['SISA_TAHUN_INI']]);
                }

                $totalPersen = ($summary['BEBAN'] ?? 0) > 0 ? round(($summary['SELESAI'] / $summary['BEBAN']) * 100, 2) : 0;
                fputcsv($file, ['TOTAL KESELURUHAN', $summary['SISA'], $summary['DITERIMA'], $summary['BEBAN'], $summary['SELESAI'], $totalPersen . '%', $summary['SISA_TAHUN_INI']]);
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal ekspor: ' . $e->getMessage());
        }
    }
}
