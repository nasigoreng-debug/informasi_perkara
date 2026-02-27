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

    public function index(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', '2025-01-01');
        $tglAkhir = $request->get('tgl_akhir', '2025-12-31');

        $data = $this->service->getMonitoringData($tglAwal, $tglAkhir);

        ActivityLog::record('Monitoring', 'CourtCalendar', "Melihat rekapitulasi monitoring Court Calendar");

        return view('court_calendar.index', compact('data', 'tglAwal', 'tglAkhir'));
    }

    public function detail(Request $request, $satker)
    {
        $tglAwal = $request->get('tgl_awal', '2025-01-01');
        $tglAkhir = $request->get('tgl_akhir', '2025-12-31');
        $db = strtolower($satker);

        // Query Detail Perkara Persis Pola Bapak yang Berhasil
        $sql = "SELECT 
                    p.nomor_perkara, 
                    p.tanggal_pendaftaran, 
                    p.proses_terakhir_text, 
                    pp.tanggal_putusan
                FROM {$db}.perkara p
                JOIN {$db}.perkara_putusan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN {$db}.perkara_court_calendar pcc ON p.perkara_id = pcc.perkara_id
                WHERE pcc.rencana_tanggal IS NULL
                AND (p.proses_terakhir_text LIKE '%minutasi%' OR p.proses_terakhir_text LIKE '%Akta Cerai%')
                AND pp.tanggal_putusan BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
                ORDER BY pp.tanggal_putusan DESC";

        $data = DB::connection('bandung')->select($sql);

        ActivityLog::record('Monitoring', 'CourtCalendar', "Melihat detail tunggakan satker: {$satker}");

        return view('court_calendar.detail', compact('data', 'satker', 'tglAwal', 'tglAkhir'));
    }
}
