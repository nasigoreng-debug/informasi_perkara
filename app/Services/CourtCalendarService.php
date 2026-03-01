<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Config\SatkerConfig;

class CourtCalendarService
{
    public function getMonitoringData($tglAwal, $tglAkhir): Collection
    {
        $results = collect();
        foreach (SatkerConfig::SATKERS as $db => $namaSatker) {
            $results->push($this->getDataPerSatker($db, $namaSatker, $tglAwal, $tglAkhir));
        }
        return $results->sortByDesc('jumlah')->values();
    }

    protected function getDataPerSatker($db, $namaSatker, $tglAwal, $tglAkhir): object
    {
        try {
            // CEK DULU: Apakah tabel 'perkara' ada di database ini?
            // Ini untuk mencegah error 1146 (Table not found)
            $tableExists = DB::connection('bandung')
                ->select("SHOW TABLES FROM {$db} LIKE 'perkara'");

            if (empty($tableExists)) {
                return (object) ['satker' => $namaSatker, 'db' => $db, 'jumlah' => 0, 'error' => 'Table not found'];
            }

            $jumlah = DB::connection('bandung')->table("{$db}.perkara as p")
                ->join("{$db}.perkara_putusan as pp", 'p.perkara_id', '=', 'pp.perkara_id')
                ->leftJoin("{$db}.perkara_court_calendar as pcc", 'p.perkara_id', '=', 'pcc.perkara_id')
                ->whereNull('pcc.rencana_tanggal')
                ->where(function ($q) {
                    $q->where('p.proses_terakhir_text', 'LIKE', '%minutasi%')
                        ->orWhere('p.proses_terakhir_text', 'LIKE', '%Akta Cerai%');
                })
                ->whereBetween('pp.tanggal_putusan', [$tglAwal, $tglAkhir])
                ->count();

            return (object) ['satker' => $namaSatker, 'db' => $db, 'jumlah' => $jumlah];
        } catch (\Exception $e) {
            // Log errornya di sini jika perlu: \Log::error($e->getMessage());
            return (object) ['satker' => $namaSatker, 'db' => $db, 'jumlah' => 0, 'error' => true];
        }
    }
}
