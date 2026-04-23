<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourtCalendarService;
use Illuminate\Support\Facades\DB;
use App\Config\SatkerConfig;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ActivityLog; // TAMBAHKAN INI

class CourtCalendarController extends Controller
{
    protected $service;

    public function __construct(CourtCalendarService $service)
    {
        $this->service = $service;
    }

    /**
     * INDEX - Monitoring Court Calendar
     */
    public function index(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', date('Y-01-01'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));
        $results = $this->service->getMonitoringData($tglAwal, $tglAkhir);

        // ✅ TAMBAHKAN LOG INDEX
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Akses Monitoring COURT CALENDAR',
            'description' => "Periode: $tglAwal s/d $tglAkhir",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('court_calendar.index', compact('results', 'tglAwal', 'tglAkhir'));
    }

    /**
     * DETAIL - Lihat Detail Perkara Tanpa Court Calendar
     */
    public function detail(Request $request, $satker)
    {
        $tglAwal = $request->get('tgl_awal');
        $tglAkhir = $request->get('tgl_akhir');

        try {
            $data = DB::connection('bandung')->table("{$satker}.perkara as p")
                ->leftJoin("{$satker}.perkara_court_calendar as pcc", 'p.perkara_id', '=', 'pcc.perkara_id')
                ->leftJoin("{$satker}.perkara_putusan as pp", 'p.perkara_id', '=', 'pp.perkara_id')
                ->leftJoin("{$satker}.perkara_akta_cerai as pac", 'p.perkara_id', '=', 'pac.perkara_id')
                ->whereBetween('p.tanggal_pendaftaran', [$tglAwal, $tglAkhir])
                ->whereNull('pcc.rencana_tanggal') // Yang belum isi Court Calendar
                ->where(function ($query) {
                    // Filter: Hanya yang sudah Minutasi / Putusan / Akta Cerai
                    $query->whereNotNull('pp.tanggal_minutasi')
                        ->orWhereNotNull('pp.tanggal_putusan')
                        ->orWhereNotNull('pac.nomor_akta_cerai');
                })
                ->select([
                    'p.nomor_perkara',
                    'p.tanggal_pendaftaran',
                    'p.jenis_perkara_nama',
                    'p.proses_terakhir_text'
                ])
                ->orderBy('p.tanggal_pendaftaran', 'desc')
                ->get();

            $namaSatker = SatkerConfig::SATKERS[$satker] ?? strtoupper($satker);

            // ✅ LOG DETAIL
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Lihat Detail COURT CALENDAR',
                'description' => "Satker: $namaSatker ($satker), Periode: $tglAwal s/d $tglAkhir, Total: " . $data->count() . " data",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return view('court_calendar.detail', compact('data', 'namaSatker', 'tglAwal', 'tglAkhir'));
        } catch (\Exception $e) {
            // ✅ LOG ERROR
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Detail COURT CALENDAR',
                'description' => "Satker: $satker - Error: " . $e->getMessage(),
                'ip_address' => $request->ip()
            ]);

            return "Gagal akses database satker [$satker]. Error: " . $e->getMessage();
        }
    }

    /**
     * EXPORT DETAIL - Export Excel Detail
     */
    public function exportDetail(Request $request, $satker)
    {
        try {
            if (ob_get_contents()) ob_end_clean();

            $tglAwal = $request->get('tgl_awal');
            $tglAkhir = $request->get('tgl_akhir');

            $dataRaw = DB::connection('bandung')->table("{$satker}.perkara as p")
                ->leftJoin("{$satker}.perkara_court_calendar as pcc", 'p.perkara_id', '=', 'pcc.perkara_id')
                ->whereBetween('p.tanggal_pendaftaran', [$tglAwal, $tglAkhir])
                ->whereNull('pcc.rencana_tanggal')
                ->select(['p.nomor_perkara', 'p.tanggal_pendaftaran', 'p.jenis_perkara_nama', 'p.proses_terakhir_text'])
                ->get();

            $dataExcel = $dataRaw->map(fn($item, $key) => [
                'No' => $key + 1,
                'Nomor Perkara' => $item->nomor_perkara,
                'Tgl Daftar' => date('d/m/Y', strtotime($item->tanggal_pendaftaran)),
                'Jenis Perkara' => $item->jenis_perkara_nama,
                'Proses Terakhir' => $item->proses_terakhir_text,
            ]);

            $namaSatker = SatkerConfig::SATKERS[$satker] ?? strtoupper($satker);

            // ✅ TAMBAHKAN LOG EXPORT
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Export Excel COURT CALENDAR',
                'description' => "Satker: $namaSatker ($satker), Periode: $tglAwal s/d $tglAkhir, Total: " . $dataExcel->count() . " data",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return Excel::download(
                new \App\Exports\CourtCalendarExport($dataExcel, ['NO', 'NOMOR PERKARA', 'TGL DAFTAR', 'JENIS PERKARA', 'PROSES TERAKHIR']),
                "Detail-CC-{$satker}_" . date('Ymd_His') . ".xlsx"
            );
        } catch (\Exception $e) {
            // ✅ LOG ERROR (Opsional)
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Error Export COURT CALENDAR',
                'description' => "Satker: $satker - Error: " . $e->getMessage(),
                'ip_address' => $request->ip()
            ]);

            return "Gagal Export: " . $e->getMessage();
        }
    }
}
