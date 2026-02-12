<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Config\SatkerConfig;

class LaporanKasasiServiceL10
{
    protected Collection $results;

    public function __construct()
    {
        $this->results = collect();
    }

    /**
     * Get laporan kasasi semua satker
     */
    public function getLaporanKasasi(int $tahun): Collection
    {
        $this->results = collect();

        foreach (SatkerConfig::SATKERS as $database => $namaSatker) {
            $nomorUrut = SatkerConfig::getNomorUrut($database);
            $data = $this->getDataSatker($database, $namaSatker, $nomorUrut, $tahun);
            $this->results = $this->results->concat($data);
        }

        return $this->sortResults($this->results);
    }

    /**
     * Get data per satker - BERDASARKAN TAHUN KASASI
     */
    protected function getDataSatker(string $database, string $namaSatker, int $nomorUrut, int $tahun): Collection
    {
        try {
            // CEK KONEKSI
            DB::connection($database)->getPdo();

            // GUNAKAN TAHUN DARI TANGGAL KASASI
            $results = DB::connection($database)
                ->select("
                    SELECT 
                        pb.nomor_perkara_pn,
                        pb.nomor_perkara_banding,
                        pk.nomor_perkara_kasasi,
                        pk.tanggal_pendaftaran_kasasi,
                        pb.hakim1_banding,
                        p.jenis_perkara_nama,  -- <--- (1) AMBIL KOLOM INI
                        ? as nomor_urut,
                        ? as pengadilan_agama
                    FROM {$database}.perkara_kasasi pk
                    INNER JOIN {$database}.perkara_banding pb 
                        ON pk.perkara_id = pb.perkara_id
                    INNER JOIN {$database}.perkara p -- <--- (2) JOIN KE TABEL PERKARA
                        ON pk.perkara_id = p.perkara_id
                    WHERE pk.nomor_perkara_kasasi IS NOT NULL
                        AND pb.nomor_perkara_banding IS NOT NULL
                        AND YEAR(pk.tanggal_pendaftaran_kasasi) = ?
                    ORDER BY pk.tanggal_pendaftaran_kasasi DESC
                ", [
                    $nomorUrut,
                    $namaSatker,
                    $tahun
                ]);

            return collect($results)->map(function ($item) {
                return (object) [
                    'nomor_urut' => $item->nomor_urut,
                    'pengadilan_agama' => $item->pengadilan_agama,
                    'no_pa' => $item->nomor_perkara_pn ?? '-',
                    'no_pta' => $item->nomor_perkara_banding ?? '-',
                    'no_kasasi' => $item->nomor_perkara_kasasi ?? '-',
                    'jenis_perkara' => $item->jenis_perkara_nama ?? '-', // <--- (3) MAPPING DATA
                    'tgl_reg_kasasi' => $this->formatDate($item->tanggal_pendaftaran_kasasi),
                    'kmh' => $item->hakim1_banding ?? '-',
                ];
            });
        } catch (\Exception $e) {
            Log::error("Error get data {$database}: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Format date to Y-m-d (safe format)
     */
    protected function formatDate($date): string
    {
        if (!$date) {
            return '-';
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return date('Y-m-d', strtotime($date));
            } catch (\Exception $e) {
                return '-';
            }
        }
    }

    /**
     * Sort results
     */
    protected function sortResults(Collection $results): Collection
    {
        return $results->sortBy([
            ['nomor_urut', 'asc'],
            ['tgl_reg_kasasi', 'desc']
        ])->values();
    }

    /**
     * Get total per satker - BERDASARKAN TAHUN KASASI
     */
    public function getTotalPerSatker(int $tahun): Collection
    {
        $totals = collect();

        foreach (SatkerConfig::SATKERS as $database => $namaSatker) {
            $nomorUrut = SatkerConfig::getNomorUrut($database);

            try {
                $result = DB::connection($database)
                    ->select("
                        SELECT COUNT(*) as total
                        FROM {$database}.perkara_kasasi pk
                        INNER JOIN {$database}.perkara_banding pb 
                            ON pk.perkara_id = pb.perkara_id
                        WHERE pk.nomor_perkara_kasasi IS NOT NULL
                            AND pb.nomor_perkara_banding IS NOT NULL
                            AND YEAR(pk.tanggal_pendaftaran_kasasi) = ?
                    ", [$tahun]);

                $total = $result[0]->total ?? 0;

                $totals->push((object) [
                    'nomor_urut' => $nomorUrut,
                    'pengadilan_agama' => $namaSatker,
                    'total' => $total
                ]);
            } catch (\Exception $e) {
                Log::error("Error total {$database}: " . $e->getMessage());
                $totals->push((object) [
                    'nomor_urut' => $nomorUrut,
                    'pengadilan_agama' => $namaSatker,
                    'total' => 0
                ]);
            }
        }

        return $totals->sortBy('nomor_urut')->values();
    }

    /**
     * Get grand total - BERDASARKAN TAHUN KASASI
     */
    public function getGrandTotal(int $tahun): int
    {
        $total = 0;

        foreach (array_keys(SatkerConfig::SATKERS) as $database) {
            try {
                $result = DB::connection($database)
                    ->select("
                        SELECT COUNT(*) as total
                        FROM {$database}.perkara_kasasi pk
                        INNER JOIN {$database}.perkara_banding pb 
                            ON pk.perkara_id = pb.perkara_id
                        WHERE pk.nomor_perkara_kasasi IS NOT NULL
                            AND pb.nomor_perkara_banding IS NOT NULL
                            AND YEAR(pk.tanggal_pendaftaran_kasasi) = ?
                    ", [$tahun]);

                $total += $result[0]->total ?? 0;
            } catch (\Exception $e) {
                continue;
            }
        }

        return $total;
    }

    /**
     * Get available years - DARI TANGGAL KASASI
     */
    public function getAvailableYears(): array
    {
        $years = [];

        foreach (array_keys(SatkerConfig::SATKERS) as $database) {
            try {
                $tahun = DB::connection($database)
                    ->table('perkara_kasasi')
                    ->whereNotNull('tanggal_pendaftaran_kasasi')
                    ->whereNotNull('nomor_perkara_kasasi')
                    ->selectRaw('YEAR(tanggal_pendaftaran_kasasi) as tahun')
                    ->distinct()
                    ->orderBy('tahun', 'desc')
                    ->pluck('tahun')
                    ->toArray();

                $years = array_merge($years, $tahun);
            } catch (\Exception $e) {
                continue;
            }
        }

        $years = array_unique($years);
        rsort($years);

        return $years;
    }

    /**
     * Debug method - ambil raw data
     */
    public function debugGetDataSatker(string $database, int $tahun): Collection
    {
        try {
            $results = DB::connection($database)
                ->select("
                    SELECT 
                        pb.nomor_perkara_pn,
                        pb.nomor_perkara_banding,
                        pk.nomor_perkara_kasasi,
                        pk.tanggal_pendaftaran_kasasi,
                        pb.hakim1_banding
                    FROM {$database}.perkara_kasasi pk
                    INNER JOIN {$database}.perkara_banding pb 
                        ON pk.perkara_id = pb.perkara_id
                    WHERE pk.nomor_perkara_kasasi IS NOT NULL
                        AND pb.nomor_perkara_banding IS NOT NULL
                        AND YEAR(pk.tanggal_pendaftaran_kasasi) = ?
                    ORDER BY pk.tanggal_pendaftaran_kasasi DESC
                ", [$tahun]);

            return collect($results);
        } catch (\Exception $e) {
            Log::error("Debug error {$database}: " . $e->getMessage());
            return collect();
        }
    }
}
