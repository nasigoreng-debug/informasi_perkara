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
            $data = DB::connection('bandung')->table("{$db}.perkara as a")
                ->join("{$db}.perkara_putusan as p", 'a.perkara_id', '=', 'p.perkara_id')
                ->join("{$db}.perkara_akta_cerai as b", 'a.perkara_id', '=', 'b.perkara_id')
                ->leftJoin("{$db}.perkara_ikrar_talak as c", 'a.perkara_id', '=', 'c.perkara_id')
                ->whereBetween('b.tgl_akta_cerai', [$tglAwal, $tglAkhir])
                ->select([
                    'a.jenis_perkara_nama',
                    'b.tgl_akta_cerai',
                    'p.tanggal_bht',
                    'c.tgl_ikrar_talak',
                    DB::raw("CASE WHEN a.jenis_perkara_nama LIKE '%Talak%' THEN DATEDIFF(b.tgl_akta_cerai, c.tgl_ikrar_talak) ELSE DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) END as selisih_hari"),
                    DB::raw("DATEDIFF(b.tgl_akta_cerai, p.tanggal_bht) as selisih_anomali")
                ])->get();

            $total = $data->count();
            if ($total === 0) return $this->emptyResponse($namaSatker);

            $tepat = $data->whereBetween('selisih_hari', [0, 7])->count();
            $lambat = $data->where('selisih_hari', '>', 7)->count();
            $anomali = $data->where('selisih_anomali', '<', 0)->count();

            return (object) [
                'satker' => $namaSatker,
                'total' => $total,
                'tepat_waktu' => $tepat,
                'terlambat' => $lambat,
                'anomali' => $anomali,
                'persen_tepat_waktu' => round(($tepat / $total) * 100, 2)
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
