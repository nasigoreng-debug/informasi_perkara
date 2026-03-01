<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Untuk menangani request HTTP
use App\Services\AktaCeraiService; // Service untuk logika bisnis terkait Akta Cerai
use App\Exports\AktaCeraiExport; // Untuk ekspor Excel
use Maatwebsite\Excel\Facades\Excel; // Untuk ekspor Excel
use Illuminate\Support\Facades\Log; // Untuk logging error
use App\Models\ActivityLog; // Model untuk menyimpan log ke database
use Carbon\Carbon; // Untuk manipulasi tanggal

class AktaCeraiController extends Controller
{
    protected $service;

    public function __construct(AktaCeraiService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', date('Y-01-01'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));
        $data = $this->service->getMonitoringPenerbitan($tglAwal, $tglAkhir);

        // Hitung Grand Total
        $totals = [
            'total' => $data->sum('total'),
            'tepat' => $data->sum('tepat_waktu'),
            'lambat' => $data->sum('terlambat'),
            'anomali' => $data->sum('anomali'),
        ];
        $totals['kinerja'] = $totals['total'] > 0 ? round(($totals['tepat'] / $totals['total']) * 100, 2) : 0;

        // --- SIMPAN LOG KE DATABASE ---
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Mengakses Index Monitoring AC",
            'description' => "Periode $tglAwal s.d $tglAkhir",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return view('akta_cerai.index', compact('data', 'tglAwal', 'tglAkhir', 'totals'));
    }

    public function export(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', date('Y-01-01'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));
        $dataRaw = $this->service->getMonitoringPenerbitan($tglAwal, $tglAkhir);

        $totalAll = 0;
        $tepatAll = 0;
        $lambatAll = 0;
        $anomaliAll = 0;
        $dataExcel = collect();

        foreach ($dataRaw as $key => $item) {
            $totalAll += (int)$item->total;
            $tepatAll += (int)$item->tepat_waktu;
            $lambatAll += (int)$item->terlambat;
            $anomaliAll += (int)$item->anomali;

            $dataExcel->push([
                'Rank' => $key + 1,
                'Satker' => $item->satker,
                'Total' => intval($item->total),
                'Tepat' => intval($item->tepat_waktu),
                'Lambat' => intval($item->terlambat),
                'Anomali' => intval($item->anomali),
                'Kinerja' => $item->persen_tepat_waktu . '%'
            ]);
        }

        $dataExcel->push([
            'Rank' => '',
            'Satker' => 'GRAND TOTAL',
            'Total' => intval($totalAll),
            'Tepat' => intval($tepatAll),
            'Lambat' => intval($lambatAll),
            'Anomali' => intval($anomaliAll),
            'Kinerja' => ($totalAll > 0 ? round(($tepatAll / $totalAll) * 100, 2) : 0) . '%'
        ]);

        // --- SIMPAN LOG KE DATABASE ---
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Export Excel Rekap AC",
            'description' => "Periode $tglAwal s.d $tglAkhir",
            'ip_address' => $request->ip()
        ]);

        return Excel::download(new AktaCeraiExport($dataExcel, ['RANK', 'SATUAN KERJA', 'TOTAL AC', 'TEPAT', 'LAMBAT', 'AC < BHT', 'KINERJA (%)']), "Rekap-AC.xlsx");
    }

    public function detail(Request $request, $satker)
    {
        $tglAwal = $request->get('tgl_awal');
        $tglAkhir = $request->get('tgl_akhir');
        $kategori = $request->get('kategori', 'all');
        $data = $this->service->getDetailAkta($satker, $tglAwal, $tglAkhir);

        if ($kategori === 'tepat') $data = $data->whereBetween('selisih_hari', [0, 7]);
        elseif ($kategori === 'terlambat') $data = $data->where('selisih_hari', '>', 7);
        elseif ($kategori === 'anomali') $data = $data->where('selisih_anomali', '<', 0);

        // --- SIMPAN LOG KE DATABASE ---
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Melihat Detail AC Satker: $satker",
            'description' => "Kategori: $kategori, Periode: $tglAwal - $tglAkhir",
            'ip_address' => $request->ip()
        ]);

        return view('akta_cerai.detail', compact('data', 'satker', 'tglAwal', 'tglAkhir', 'kategori'));
    }

    public function exportDetail(Request $request)
    {
        $satker = $request->get('satker');
        $tglAwal = $request->get('tgl_awal');
        $tglAkhir = $request->get('tgl_akhir');
        $kategori = $request->get('kategori', 'all');

        $dataRaw = $this->service->getDetailAkta($satker, $tglAwal, $tglAkhir);
        if ($kategori === 'tepat') $dataRaw = $dataRaw->whereBetween('selisih_hari', [0, 7]);
        elseif ($kategori === 'terlambat') $dataRaw = $dataRaw->where('selisih_hari', '>', 7);
        elseif ($kategori === 'anomali') $dataRaw = $dataRaw->where('selisih_anomali', '<', 0);

        $dataExcel = $dataRaw->map(function ($item, $key) {
            return [
                'No' => $key + 1,
                'Nomor Perkara' => $item->nomor_perkara,
                'Jenis' => $item->jenis_perkara_nama,
                'Tgl BHT' => $item->tanggal_bht ? Carbon::parse($item->tanggal_bht)->format('d-m-Y') : '-',
                'Tgl Ikrar' => $item->tgl_ikrar_talak ? Carbon::parse($item->tgl_ikrar_talak)->format('d-m-Y') : '-',
                'Tgl Akta' => $item->tgl_akta_cerai ? Carbon::parse($item->tgl_akta_cerai)->format('d-m-Y') : '-',
                'Selisih' => $item->selisih_hari . ' Hari',
            ];
        });

        // --- SIMPAN LOG KE DATABASE ---
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Export Excel Detail AC Satker: $satker",
            'description' => "Kategori: $kategori",
            'ip_address' => $request->ip()
        ]);

        return Excel::download(new AktaCeraiExport($dataExcel, ['NO', 'NOMOR PERKARA', 'JENIS', 'TGL BHT', 'TGL IKRAR', 'TGL AKTA', 'SELISIH']), "Detail-AC-{$satker}.xlsx");
    }
}
