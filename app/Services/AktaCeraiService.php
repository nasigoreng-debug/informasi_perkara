<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Config\SatkerConfig;

class AktaCeraiService
{
    public function getMonitoringPenerbitan($tglAwal, $tglAkhir): Collection
    {
        $results = collect();
        foreach (SatkerConfig::SATKERS as $db => $namaSatker) {
            $results->push($this->getDataPerSatker($db, $namaSatker, $tglAwal, $tglAkhir));
        }

        // URUTKAN BERDASARKAN KINERJA TERTINGGI
        // Jika % sama, urutkan berdasarkan jumlah penerbitan terbanyak (beban kerja lebih berat)
        return $results->sort(function ($a, $b) {
            if ($a->persen_tepat_waktu === $b->persen_tepat_waktu) {
                return $b->total <=> $a->total;
            }
            return $b->persen_tepat_waktu <=> $a->persen_tepat_waktu;
        })->values();
    }

    protected function getDataPerSatker($db, $namaSatker, $tglAwal, $tglAkhir): object
    {
        try {
            // Query ini jauh lebih cepat karena mengandalkan agregasi database
            $summary = DB::connection('bandung')->table("{$db}.perkara as a")
                ->join("{$db}.perkara_putusan as p", 'a.perkara_id', '=', 'p.perkara_id')
                ->join("{$db}.perkara_akta_cerai as b", 'a.perkara_id', '=', 'b.perkara_id')
                ->leftJoin("{$db}.perkara_ikrar_talak as c", 'a.perkara_id', '=', 'c.perkara_id')
                ->whereBetween('b.tgl_akta_cerai', [$tglAwal, $tglAkhir])
                ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN 
                    (CASE WHEN a.jenis_perkara_nama LIKE '%Talak%' THEN DATEDIFF(b.tgl_akta_cerai, c.tgl_ikrar_talak) 
                          ELSE DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) END) BETWEEN 0 AND 7 
                    THEN 1 ELSE 0 END) as tepat_waktu,
                SUM(CASE WHEN 
                    (CASE WHEN a.jenis_perkara_nama LIKE '%Talak%' THEN DATEDIFF(b.tgl_akta_cerai, c.tgl_ikrar_talak) 
                          ELSE DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) END) > 7 
                    THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) < 0 THEN 1 ELSE 0 END) as anomali
            ")
                ->first();

            if (!$summary || $summary->total == 0) return $this->emptyResponse($namaSatker);

            return (object) [
                'satker' => $namaSatker,
                'total' => (int)$summary->total,
                'tepat_waktu' => (int)$summary->tepat_waktu,
                'terlambat' => (int)$summary->terlambat,
                'anomali' => (int)$summary->anomali,
                'persen_tepat_waktu' => round(($summary->tepat_waktu / $summary->total) * 100, 2)
            ];
        } catch (\Exception $e) {
            return $this->emptyResponse($namaSatker, true);
        }
    }

    public function getDetailAkta($satkerTampilan, $tglAwal, $tglAkhir)
    {
        $db = SatkerConfig::getDbName($satkerTampilan);
        return DB::connection('bandung')->table("{$db}.perkara as a")
            ->join("{$db}.perkara_putusan as p", 'a.perkara_id', '=', 'p.perkara_id')
            ->join("{$db}.perkara_akta_cerai as b", 'a.perkara_id', '=', 'b.perkara_id')
            ->leftJoin("{$db}.perkara_ikrar_talak as c", 'a.perkara_id', '=', 'c.perkara_id')
            ->whereBetween('b.tgl_akta_cerai', [$tglAwal, $tglAkhir])
            ->select([
                'a.nomor_perkara',
                'a.jenis_perkara_nama',
                'p.tanggal_bht',
                'c.tgl_ikrar_talak',
                'b.tgl_akta_cerai',
                'b.nomor_akta_cerai',
                DB::raw("CASE WHEN a.jenis_perkara_nama LIKE '%Talak%' THEN DATEDIFF(b.tgl_akta_cerai, c.tgl_ikrar_talak) ELSE DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) END as selisih_hari"),
                DB::raw("DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) as selisih_anomali")
            ])->get();
    }

    private function emptyResponse($namaSatker, $error = false)
    {
        return (object) ['satker' => $namaSatker, 'total' => 0, 'tepat_waktu' => 0, 'terlambat' => 0, 'anomali' => 0, 'persen_tepat_waktu' => 0, 'error' => $error];
    }
}
