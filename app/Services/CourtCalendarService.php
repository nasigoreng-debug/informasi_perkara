<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Config\SatkerConfig;

class CourtCalendarService
{
    public function getMonitoringData($tglAwal, $tglAkhir)
    {
        $results = collect();
        foreach (SatkerConfig::SATKERS as $db => $namaSatker) {
            $results->push($this->getDataPerSatker($db, $namaSatker, $tglAwal, $tglAkhir));
        }

        return $results->sort(function ($a, $b) {
            if ($b->persentase != $a->persentase) return $b->persentase <=> $a->persentase;
            return $b->total <=> $a->total;
        })->values();
    }

    protected function getDataPerSatker($db, $namaSatker, $tglAwal, $tglAkhir)
    {
        try {
            $total = DB::connection('bandung')->table("{$db}.perkara as p")
                ->leftJoin("{$db}.perkara_putusan as pp", 'p.perkara_id', '=', 'pp.perkara_id')
                ->leftJoin("{$db}.perkara_akta_cerai as pac", 'p.perkara_id', '=', 'pac.perkara_id')
                ->whereBetween('p.tanggal_pendaftaran', [$tglAwal, $tglAkhir])
                ->where(function ($query) {
                    // Filter: Hanya ambil yang sudah Minutasi ATAU sudah Putus ATAU sudah ada Akta Cerai
                    $query->whereNotNull('pp.tanggal_minutasi')
                        ->orWhereNotNull('pp.tanggal_putusan')
                        ->orWhereNotNull('pac.nomor_akta_cerai');
                })
                ->count();

            $belum = DB::connection('bandung')->table("{$db}.perkara as p")
                ->leftJoin("{$db}.perkara_putusan as pp", 'p.perkara_id', '=', 'pp.perkara_id')
                ->leftJoin("{$db}.perkara_akta_cerai as pac", 'p.perkara_id', '=', 'pac.perkara_id')
                // Gunakan join ke court calendar untuk cek yang kosong
                ->leftJoin("{$db}.perkara_court_calendar as pcc", 'p.perkara_id', '=', 'pcc.perkara_id')
                ->whereBetween('p.tanggal_pendaftaran', [$tglAwal, $tglAkhir])
                ->where(function ($query) {
                    $query->whereNotNull('pp.tanggal_minutasi')
                        ->orWhereNotNull('pp.tanggal_putusan')
                        ->orWhereNotNull('pac.nomor_akta_cerai');
                })
                ->whereNull('pcc.rencana_tanggal') // Filter yang belum isi Court Calendar
                ->count();

            $sudah = $total - $belum;
            $persen = $total > 0 ? round(($sudah / $total) * 100, 2) : 0;

            return (object) [
                'satker' => $namaSatker,
                'db' => $db,
                'total' => $total,
                'sudah' => $sudah,
                'belum' => $belum,
                'persentase' => $persen
            ];
        } catch (\Exception $e) {
            // Jika masih kosong, coba return error untuk debug
            return (object) ['satker' => $namaSatker . ' (Error)', 'db' => $db, 'total' => 0, 'sudah' => 0, 'belum' => 0, 'persentase' => 0];
        }
    }
}
