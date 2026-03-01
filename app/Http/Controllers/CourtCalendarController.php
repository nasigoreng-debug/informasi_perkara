<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourtCalendarService;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

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

        // KUNCI PERBAIKAN: Ambil nama DB asli dari Config
        $db = \App\Config\SatkerConfig::getDbName($satker);

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
                ->select([
                    'p.nomor_perkara',
                    'p.tanggal_pendaftaran',
                    'p.proses_terakhir_text',
                    'pp.tanggal_putusan'
                ])
                ->orderBy('pp.tanggal_putusan', 'desc')
                ->get();

            ActivityLog::record('Monitoring', 'CourtCalendar', "Melihat detail tunggakan satker: {$satker}");

            return view('court_calendar.detail', compact('data', 'satker', 'tglAwal', 'tglAkhir'));
        } catch (\Exception $e) {
            // Jika database tidak ditemukan, kirim pesan error yang rapi ke view
            return view('court_calendar.detail', [
                'data' => collect(),
                'satker' => $satker,
                'tglAwal' => $tglAwal,
                'tglAkhir' => $tglAkhir,
                'errorMessage' => "Database '{$db}' tidak ditemukan di server."
            ]);
        }
    }

    /**
     * Export Rekap Wilayah ke Excel
     */
    public function export(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', '2026-01-01');
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));
        $data = $this->service->getMonitoringData($tglAwal, $tglAkhir);

        ActivityLog::record('Monitoring', 'CourtCalendar', "Export Excel Rekap Court Calendar");

        header("Content-Type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Rekap_Court_Calendar.xls");

        return view('court_calendar.export_excel', compact('data', 'tglAwal', 'tglAkhir'));
    }
}
