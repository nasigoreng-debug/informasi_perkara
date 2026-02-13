<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Config\SatkerConfig;
use Illuminate\Support\Str;

class LaporanKasasiServiceL10
{
    protected Collection $results;
    public function __construct() { $this->results = collect(); }

    public function getLaporanKasasi(int $tahun, ?int $bulan = null): Collection
    {
        $this->results = collect();
        foreach (SatkerConfig::SATKERS as $database => $namaSatker) {
            $nomorUrut = SatkerConfig::getNomorUrut($database);
            $data = $this->getDataSatker($database, $namaSatker, $nomorUrut, $tahun, $bulan);
            $this->results = $this->results->concat($data);
        }
        return $this->sortResults($this->results);
    }

    protected function getDataSatker($database, $namaSatker, $nomorUrut, $tahun, $bulan): Collection
    {
        try {
            $sql = "SELECT pb.nomor_perkara_pn, pb.nomor_perkara_banding, pk.nomor_perkara_kasasi,
                           pk.tanggal_pendaftaran_kasasi, pk.amar_putusan_kasasi, pk.putusan_kasasi,
                           pb.hakim1_banding, p.jenis_perkara_nama, ? as nomor_urut, ? as pengadilan_agama
                    FROM {$database}.perkara_kasasi pk
                    INNER JOIN {$database}.perkara_banding pb ON pk.perkara_id = pb.perkara_id
                    INNER JOIN {$database}.perkara p ON pk.perkara_id = p.perkara_id
                    WHERE pk.nomor_perkara_kasasi IS NOT NULL AND YEAR(pk.tanggal_pendaftaran_kasasi) = ?";
            
            $params = [$nomorUrut, $namaSatker, $tahun];
            if ($bulan) { 
                $sql .= " AND MONTH(pk.tanggal_pendaftaran_kasasi) = ?"; 
                $params[] = $bulan; 
            }

            $results = DB::connection($database)->select($sql, $params);
            return collect($results)->map(function ($item, $key) use ($nomorUrut) {
                $cleanAmar = strip_tags($item->amar_putusan_kasasi);
                $lowerAmar = strtolower($cleanAmar);
                $statusColor = "secondary"; $statusLabel = "PROSES";
                
                if (!empty($cleanAmar)) {
                    if (Str::contains($lowerAmar, ['menolak', 'tolak'])) { $statusLabel = "MENOLAK KASASI"; $statusColor = "success"; }
                    elseif (Str::contains($lowerAmar, ['mengabulkan', 'kabul'])) { $statusLabel = "MENGABULKAN KASASI"; $statusColor = "danger"; }
                    else { $statusLabel = "SUDAH PUTUS"; $statusColor = "info"; }
                }
                return (object) [
                    'unique_id' => $nomorUrut . '_' . $key,
                    'nomor_urut' => $item->nomor_urut,
                    'pengadilan_agama' => $item->pengadilan_agama,
                    'no_pa' => $item->nomor_perkara_pn ?? '-',
                    'no_pta' => $item->nomor_perkara_banding ?? '-',
                    'no_kasasi' => $item->nomor_perkara_kasasi ?? '-',
                    'jenis_perkara' => $item->jenis_perkara_nama ?? '-',
                    'tgl_reg_kasasi' => $item->tanggal_pendaftaran_kasasi,
                    'tgl_putusan' => ($item->putusan_kasasi && $item->putusan_kasasi != '0000-00-00') ? $item->putusan_kasasi : null,
                    'amar_full' => $item->amar_putusan_kasasi,
                    'status_label' => $statusLabel,
                    'status_color' => $statusColor,
                    'kmh' => $item->hakim1_banding ?? '-',
                ];
            });
        } catch (\Exception $e) { return collect(); }
    }

    public function getGrandTotal(int $tahun, ?int $bulan = null): int 
    {
        $total = 0;
        foreach (array_keys(SatkerConfig::SATKERS) as $db) {
            $sql = "SELECT COUNT(*) as total FROM {$db}.perkara_kasasi WHERE YEAR(tanggal_pendaftaran_kasasi) = ?";
            $params = [$tahun];
            if ($bulan) { $sql .= " AND MONTH(tanggal_pendaftaran_kasasi) = ?"; $params[] = $bulan; }
            try { $r = DB::connection($db)->select($sql, $params); $total += $r[0]->total; } catch (\Exception $e) {}
        }
        return $total;
    }

    public function getTotalPerSatker(int $tahun, ?int $bulan = null): Collection
    {
        $totals = collect();
        foreach (SatkerConfig::SATKERS as $db => $nama) {
            $sql = "SELECT COUNT(*) as total FROM {$db}.perkara_kasasi WHERE YEAR(tanggal_pendaftaran_kasasi) = ?";
            $params = [$tahun];
            if ($bulan) { $sql .= " AND MONTH(tanggal_pendaftaran_kasasi) = ?"; $params[] = $bulan; }
            try { $r = DB::connection($db)->select($sql, $params); $totals->push((object)['pengadilan_agama'=>$nama,'total'=>$r[0]->total]); } catch (\Exception $e) { $totals->push((object)['pengadilan_agama'=>$nama,'total'=>0]); }
        }
        return $totals;
    }

    protected function sortResults($results) { return $results->sortBy([['nomor_urut','asc'],['tgl_reg_kasasi','desc']])->values(); }
    public function getAvailableYears() { return range(date('Y'), date('Y') - 4); }
}