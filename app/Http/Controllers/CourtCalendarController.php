<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourtCalendarService;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Config\SatkerConfig;
use Carbon\Carbon;

class CourtCalendarController extends Controller
{
    protected $service;

    public function __construct(CourtCalendarService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan Halaman Rekapitulasi Wilayah
     */
    public function index(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', '2026-01-01');
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));

        $data = $this->service->getMonitoringData($tglAwal, $tglAkhir);

        // LOG: Rekap Wilayah
        ActivityLog::record('Monitoring', 'CourtCalendar', "Melihat rekap Court Calendar periode {$tglAwal} s/d {$tglAkhir}");

        return view('court_calendar.index', compact('data', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Menampilkan Halaman Detail Tunggakan per Satker
     */
    public function detail(Request $request, $satker)
    {
        $tglAwal = $request->get('tgl_awal', '2026-01-01');
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));

        $db = SatkerConfig::getDbName($satker) ?: array_search(strtoupper(trim($satker)), SatkerConfig::SATKERS);

        if (!$db) {
            return back()->with('error', "Database untuk satker {$satker} tidak ditemukan.");
        }

        try {
            $data = DB::connection('bandung')->table("{$db}.perkara as p")
                ->join("{$db}.perkara_putusan as pp", 'p.perkara_id', '=', 'pp.perkara_id')
                ->leftJoin("{$db}.perkara_court_calendar as pcc", 'p.perkara_id', '=', 'pcc.perkara_id')
                ->whereNull('pcc.rencana_tanggal')
                ->where(function ($q) {
                    $q->where('p.proses_terakhir_text', 'LIKE', '%minutasi%')
                        ->orWhere('p.proses_terakhir_text', 'LIKE', '%Akta Cerai%');
                })
                ->whereBetween('pp.tanggal_putusan', [$tglAwal, $tglAkhir])
                ->select(['p.nomor_perkara', 'p.tanggal_pendaftaran', 'p.proses_terakhir_text', 'pp.tanggal_putusan'])
                ->orderBy('pp.tanggal_putusan', 'desc')
                ->get();

            // LOG: Detail Satker
            ActivityLog::record('Monitoring', 'CourtCalendar', "Melihat detail tunggakan satker: {$satker} periode {$tglAwal} s/d {$tglAkhir}");

            return view('court_calendar.detail', compact('data', 'satker', 'tglAwal', 'tglAkhir'));
        } catch (\Exception $e) {
            return back()->with('error', "Gagal memuat data: " . $e->getMessage());
        }
    }

    /**
     * Export Excel Rekap (Wilayah)
     */
    public function export(Request $request)
    {
        try {
            if (ob_get_contents()) ob_end_clean();

            $tglAwal = $request->get('tgl_awal', '2026-01-01');
            $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));

            $dataRaw = $this->service->getMonitoringData($tglAwal, $tglAkhir);

            $dataExcel = collect();
            foreach ($dataRaw as $key => $item) {
                $dataExcel->push([
                    'No' => $key + 1,
                    'Satuan Kerja' => $item->satker,
                    'Belum Input' => (int)($item->jumlah ?? 0),
                ]);
            }

            // LOG: Export Rekap
            ActivityLog::record('Export', 'CourtCalendar', "Export Excel Rekap Wilayah periode {$tglAwal} s/d {$tglAkhir}");

            return Excel::download(new \App\Exports\CourtCalendarExport($dataExcel, ['NO', 'SATUAN KERJA', 'JUMLAH']), "Rekap-CC-Jabar.xlsx");
        } catch (\Exception $e) {
            return "Gagal Export: " . $e->getMessage();
        }
    }

    /**
     * Export Excel Detail (Per Satker)
     */
    public function exportDetail(Request $request)
    {
        try {
            if (ob_get_contents()) ob_end_clean();

            $satker = $request->get('satker') ?: $request->route('satker');
            $tglAwal = $request->get('tgl_awal', '2026-01-01');
            $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));

            if (empty($satker)) {
                return "Gagal Export Detail: Nama Satker tidak terbaca.";
            }

            $db = SatkerConfig::getDbName($satker) ?: array_search(strtoupper(trim($satker)), SatkerConfig::SATKERS);

            if (!$db) {
                return "Gagal Export Detail: Database untuk satker '{$satker}' tidak ditemukan.";
            }

            $dataRaw = DB::connection('bandung')->table("{$db}.perkara as p")
                ->join("{$db}.perkara_putusan as pp", 'p.perkara_id', '=', 'pp.perkara_id')
                ->leftJoin("{$db}.perkara_court_calendar as pcc", 'p.perkara_id', '=', 'pcc.perkara_id')
                ->whereNull('pcc.rencana_tanggal')
                ->whereBetween('pp.tanggal_putusan', [$tglAwal, $tglAkhir])
                ->select(['p.nomor_perkara', 'p.tanggal_pendaftaran', 'pp.tanggal_putusan', 'p.proses_terakhir_text'])
                ->get();

            $dataExcel = $dataRaw->map(function ($item, $key) {
                return [
                    'No' => $key + 1,
                    'Nomor Perkara' => $item->nomor_perkara,
                    'Tgl Daftar' => $item->tanggal_pendaftaran,
                    'Tgl Putusan' => $item->tanggal_putusan,
                    'Status' => $item->proses_terakhir_text,
                ];
            });

            // LOG: Export Detail
            ActivityLog::record('Export', 'CourtCalendar', "Export Excel Detail Satker: {$satker} periode {$tglAwal} s/d {$tglAkhir}");

            return Excel::download(new \App\Exports\CourtCalendarExport($dataExcel, ['NO', 'NOMOR PERKARA', 'TGL DAFTAR', 'TGL PUTUSAN', 'STATUS']), "Detail-CC-{$satker}.xlsx");
        } catch (\Exception $e) {
            return "Gagal Export Detail: " . $e->getMessage();
        }
    }
}
