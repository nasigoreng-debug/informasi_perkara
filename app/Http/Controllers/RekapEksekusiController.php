<?php

namespace App\Http\Controllers;

use App\Services\RekapEksekusiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\ActivityLog;

class RekapEksekusiController extends Controller
{
    protected $rekapService;

    public function __construct(RekapEksekusiService $rekapService)
    {
        $this->rekapService = $rekapService;
    }

    /**
     * Tampilan Index - Filter Otomatis & Log
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tglAwal = $request->tgl_awal ?? date('Y-01-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-12-31');

        if ($tglAkhir < $tglAwal) $tglAkhir = $tglAwal;

        $dataRaw = $this->rekapService->getRekap($tglAwal, $tglAkhir);
        $dataCollection = collect($dataRaw);

        // Logika Admin vs Satker
        if ($user->role_id == 1) {
            $data = $dataCollection;
        } else {
            $keyword = strtoupper($user->satker->tabel ?? '');
            $data = $dataCollection->filter(function ($item) use ($keyword) {
                $namaSatker = is_object($item) ? $item->satker : ($item['satker'] ?? '');
                return str_contains(strtoupper($namaSatker), $keyword);
            });
        }

        $summary = $this->rekapService->getSummary($data);
        $allTime = $this->rekapService->getAllTimeSummary();

        return view('eksekusi.index', compact('data', 'summary', 'allTime', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Detail Perkara - Proteksi & Log
     * FIXED: Variabel disamakan menjadi $data agar compact() tidak error
     */
    public function detail(Request $request)
    {
        $user = Auth::user();
        $satker = $request->get('satker');
        $jenis = $request->get('jenis');
        $tglAwal = $request->get('tgl_awal') ?? date('Y-01-01');
        $tglAkhir = $request->get('tgl_akhir') ?? date('Y-12-31');

        if ($user->role_id != 1 && $user->satker) {
            $keyword = strtoupper($user->satker->tabel);
            if (!str_contains(strtoupper($satker), $keyword)) {
                return redirect()->route('laporan.eksekusi.index')->with('error', 'Akses Ditolak!');
            }
        }

        // Kita simpan hasil ke variabel $data agar cocok dengan fungsi compact('data')
        $data = $this->rekapService->getDetailPerkara($satker, $jenis, $tglAwal, $tglAkhir);

        // LOG: Catat aktivitas intip detail
        ActivityLog::record('Lihat Detail', 'RekapEksekusi', "Melihat detail {$jenis} Satker: {$satker}");

        return view('eksekusi.detail', compact('satker', 'jenis', 'tglAwal', 'tglAkhir', 'data'));
    }

    /**
     * Export CSV Anti-Berantakan & Catat Log
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $tglAwal = $request->get('tgl_awal', date('Y-01-01'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-12-31'));

        try {
            $dataRaw = $this->rekapService->getRekap($tglAwal, $tglAkhir);
            $dataCollection = collect($dataRaw);

            if ($user->role_id == 1) {
                $data = $dataCollection;
                $suffix = "pta_bandung_pusat";
            } else {
                $keyword = strtoupper($user->satker->tabel ?? '');
                $data = $dataCollection->filter(function ($item) use ($keyword) {
                    $namaSatker = is_object($item) ? $item->satker : ($item['satker'] ?? '');
                    return str_contains(strtoupper($namaSatker), $keyword);
                });
                $suffix = strtolower($user->satker->tabel ?? 'satker');
            }

            // --- CATAT LOG ---
            ActivityLog::record('Export Data', 'RekapEksekusi', "Export CSV Eksekusi periode {$tglAwal} s/d {$tglAkhir}");

            $filename = "rekap_eksekusi_{$suffix}_" . date('Ymd_His') . ".csv";

            return response()->stream(function () use ($data) {
                $file = fopen('php://output', 'w');
                fputs($file, "sep=,\n");
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['SATUAN KERJA', 'SISA LALU', 'DITERIMA', 'BEBAN', 'SELESAI', 'RASIO (%)', 'SISA KINI']);

                foreach ($data as $row) {
                    $r = (array) $row;
                    $beban = (int) ($r['BEBAN'] ?? 0);
                    $selesai = (int) ($r['SELESAI'] ?? 0);
                    $persen = $beban > 0 ? round(($selesai / $beban) * 100, 2) : 0;

                    fputcsv($file, [
                        $r['satker'] ?? '-',
                        $r['SISA'] ?? 0,
                        $r['DITERIMA'] ?? 0,
                        $r['BEBAN'] ?? 0,
                        $r['SELESAI'] ?? 0,
                        $persen . '%',
                        $r['SISA_TAHUN_INI'] ?? 0
                    ]);
                }
                fclose($file);
            }, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename={$filename}",
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal ekspor: ' . $e->getMessage());
        }
    }
}