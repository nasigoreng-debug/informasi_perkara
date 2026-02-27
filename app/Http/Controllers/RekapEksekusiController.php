<?php

namespace App\Http\Controllers;

use App\Services\RekapEksekusiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection; // Tambahkan ini

class RekapEksekusiController extends Controller
{
    protected $rekapService;

    public function __construct(RekapEksekusiService $rekapService)
    {
        $this->rekapService = $rekapService;
    }

    /**
     * Menampilkan rekapitulasi eksekusi dengan filter otomatis.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tglAwal = $request->tgl_awal ?? date('Y-01-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-12-31');

        if ($tglAkhir < $tglAwal) {
            $tglAkhir = $tglAwal;
        }

        // 1. Ambil data mentah
        $dataRaw = $this->rekapService->getRekap($tglAwal, $tglAkhir);

        // --- SOLUSI: Ubah Array ke Collection agar bisa menggunakan ->filter() ---
        $dataCollection = collect($dataRaw);

        if ($user->role !== 'Super Admin' && $user->satker) {
            // Berdasarkan Debug Anda, query SQL menggunakan nama satker UPPERCASE (BANDUNG, INDRAMAYU, dll)
            $keyword = strtoupper($user->satker->tabel);

            $data = $dataCollection->filter(function ($item) use ($keyword) {
                // Pastikan $item adalah objek atau array sesuai hasil query
                $namaSatker = is_object($item) ? $item->satker : $item['satker'];
                return str_contains(strtoupper($namaSatker), $keyword);
            });
        } else {
            $data = $dataCollection;
        }

        $summary = $this->rekapService->getSummary($data);
        $allTime = $this->rekapService->getAllTimeSummary();

        return view('eksekusi.index', compact('data', 'summary', 'allTime', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Menampilkan detail perkara eksekusi.
     */
    public function detail(Request $request)
    {
        $user = Auth::user();
        $satker = $request->get('satker');
        $jenis = $request->get('jenis');
        $tglAwal = $request->get('tgl_awal') ?? date('Y-01-01');
        $tglAkhir = $request->get('tgl_akhir') ?? date('Y-12-31');

        if (!$satker || !$jenis) {
            return redirect()->route('laporan.eksekusi.index')->with('error', 'Parameter tidak valid.');
        }

        // --- PROTEKSI DETAIL ---
        if ($user->role !== 'Super Admin' && $user->satker) {
            $keyword = strtoupper($user->satker->tabel);
            if (!str_contains(strtoupper($satker), $keyword)) {
                return redirect()->route('laporan.eksekusi.index')->with('error', 'Akses Ditolak!');
            }
        }

        try {
            $dataDetail = $this->rekapService->getDetailPerkara($satker, $jenis, $tglAwal, $tglAkhir);

            return view('eksekusi.detail', [
                'satker' => $satker,
                'jenis' => $jenis,
                'tglAwal' => $tglAwal,
                'tglAkhir' => $tglAkhir,
                'data' => $dataDetail
            ]);
        } catch (\Exception $e) {
            return redirect()->route('laporan.eksekusi.index')->with('error', 'Gagal memuat detail.');
        }
    }

    /**
     * Export data CSV.
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $tglAwal = $request->get('tgl_awal', date('Y-01-01'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-12-31'));

        try {
            $dataRaw = $this->rekapService->getRekap($tglAwal, $tglAkhir);
            $dataCollection = collect($dataRaw);

            if ($user->role !== 'Super Admin' && $user->satker) {
                $keyword = strtoupper($user->satker->tabel);
                $data = $dataCollection->filter(function ($item) use ($keyword) {
                    $namaSatker = is_object($item) ? $item->satker : $item['satker'];
                    return str_contains(strtoupper($namaSatker), $keyword);
                });
                $suffix = $user->satker->tabel;
            } else {
                $data = $dataCollection;
                $suffix = "pta_bandung";
            }

            $summary = $this->rekapService->getSummary($data);
            $filename = "rekap_eksekusi_{$suffix}.csv";

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
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal ekspor.');
        }
    }
}