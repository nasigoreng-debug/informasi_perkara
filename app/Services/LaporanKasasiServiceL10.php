<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Config\SatkerConfig;
use Illuminate\Support\Str;

class LaporanKasasiServiceL10
{
    protected Collection $results;

    public function __construct()
    {
        $this->results = collect();
    }

    /**
     * Fungsi Utama: Ambil Data Kasasi
     */
    public function getLaporanKasasi(?int $tahun = null, ?int $bulan = null): Collection
    {
        $this->results = collect();
        foreach (SatkerConfig::SATKERS as $database => $namaSatker) {
            $nomorUrut = SatkerConfig::getNomorUrut($database);
            $data = $this->getDataSatker($database, $namaSatker, $nomorUrut, $tahun, $bulan);
            $this->results = $this->results->concat($data);
        }
        return $this->sortResults($this->results);
    }

    /**
     * Query Detail per Satker
     */
    protected function getDataSatker($database, $namaSatker, $nomorUrut, $tahun, $bulan): Collection
    {
        try {
            $sql = "SELECT pb.nomor_perkara_pn, pb.nomor_perkara_banding, pk.nomor_perkara_kasasi,
                           pk.tanggal_pendaftaran_kasasi, pk.amar_putusan_kasasi, pk.putusan_kasasi,
                           pb.hakim1_banding, p.jenis_perkara_nama, ? as nomor_urut, ? as pengadilan_agama
                    FROM {$database}.perkara_kasasi pk
                    INNER JOIN {$database}.perkara_banding pb ON pk.perkara_id = pb.perkara_id
                    INNER JOIN {$database}.perkara p ON pk.perkara_id = p.perkara_id
                    WHERE 1=1";

            $params = [$nomorUrut, $namaSatker];

            // Filter Tahun: Cek di tanggal ATAU di 4 digit terakhir nomor perkara
            if ($tahun) {
                $sql .= " AND (YEAR(pk.tanggal_pendaftaran_kasasi) = ? OR RIGHT(TRIM(pk.nomor_perkara_kasasi), 4) = ?)";
                $params[] = $tahun;
                $params[] = $tahun;
            }

            // Filter Bulan
            if ($bulan) {
                $sql .= " AND (MONTH(pk.tanggal_pendaftaran_kasasi) = ? OR pk.tanggal_pendaftaran_kasasi IS NULL)";
                $params[] = $bulan;
            }

            $results = DB::connection($database)->select($sql, $params);

            return collect($results)->map(function ($item, $key) use ($nomorUrut) {
                $cleanAmar = strip_tags($item->amar_putusan_kasasi ?? '');
                $lowerAmar = strtolower($cleanAmar);
                $statusColor = "secondary";
                $statusLabel = "PROSES";

                if (!empty($cleanAmar)) {
                    if (Str::contains($lowerAmar, ['menolak', 'tolak'])) {
                        $statusLabel = "MENOLAK KASASI";
                        $statusColor = "success";
                    } elseif (Str::contains($lowerAmar, ['mengabulkan', 'kabul'])) {
                        $statusLabel = "MENGABULKAN KASASI";
                        $statusColor = "danger";
                    } else {
                        $statusLabel = "SUDAH PUTUS";
                        $statusColor = "info";
                    }
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
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * MENGAMBIL LIST TAHUN DARI NOMOR PERKARA KASASI (DINAMIS)
     */
    public function getAvailableYears(): array
    {
        $years = collect();
        $currentYear = (int) date('Y');

        foreach (array_keys(SatkerConfig::SATKERS) as $db) {
            try {
                // Ambil 4 karakter terakhir dari nomor perkara kasasi yang isinya angka
                $res = DB::connection($db)->select("
                    SELECT DISTINCT RIGHT(TRIM(nomor_perkara_kasasi), 4) as tahun 
                    FROM perkara_kasasi 
                    WHERE nomor_perkara_kasasi IS NOT NULL 
                    AND nomor_perkara_kasasi != ''
                    AND RIGHT(TRIM(nomor_perkara_kasasi), 4) REGEXP '^[0-9]{4}$'
                ");

                foreach ($res as $row) {
                    $years->push((int) $row->tahun);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Masukkan tahun sekarang sebagai opsi wajib
        $years->push($currentYear);

        // Hapus duplikat, urutkan dari tahun terbaru ke terlama
        return $years->unique()->sortDesc()->values()->toArray();
    }

    public function getGrandTotal(?int $tahun, ?int $bulan = null): int
    {
        return $this->getLaporanKasasi($tahun, $bulan)->count();
    }

    public function getTotalPerSatker(?int $tahun, ?int $bulan = null): Collection
    {
        $allData = $this->getLaporanKasasi($tahun, $bulan);
        $totals = collect();
        foreach (SatkerConfig::SATKERS as $db => $nama) {
            $count = $allData->where('pengadilan_agama', $nama)->count();
            $totals->push((object)['pengadilan_agama' => $nama, 'total' => $count]);
        }
        return $totals;
    }

    protected function sortResults($results)
    {
        return $results->sortBy([['nomor_urut', 'asc'], ['tgl_reg_kasasi', 'desc']])->values();
    }
}
