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
     * Query Detail per Satker menggunakan Query Builder
     */
    protected function getDataSatker($database, $namaSatker, $nomorUrut, $tahun, $bulan): Collection
    {
        try {
            // Menggunakan Query Builder untuk keamanan dan kemudahan pengelolaan
            $query = DB::connection($database)->table("{$database}.perkara_kasasi as pk")
                ->join("{$database}.perkara_banding as pb", 'pk.perkara_id', '=', 'pb.perkara_id')
                ->join("{$database}.perkara as p", 'pk.perkara_id', '=', 'p.perkara_id')
                ->select([
                    'pk.perkara_id', 
                    'pb.nomor_perkara_pn', 
                    'pb.nomor_perkara_banding', 
                    'pk.nomor_perkara_kasasi',
                    'pk.tanggal_pendaftaran_kasasi', 
                    'pk.status_putusan_kasasi_text', 
                    'pk.amar_putusan_kasasi', 
                    'pk.putusan_kasasi',
                    'pb.hakim1_banding', 
                    'p.jenis_perkara_nama',
                    DB::raw("? as nomor_urut"),
                    DB::raw("? as pengadilan_agama")
                ])
                ->addBinding([$nomorUrut, $namaSatker], 'select');

            // Filter Tahun secara dinamis
            if ($tahun) {
                $query->where(function($q) use ($tahun) {
                    $q->whereYear('pk.tanggal_pendaftaran_kasasi', $tahun)
                      ->orWhereRaw("RIGHT(TRIM(pk.nomor_perkara_kasasi), 4) = ?", [$tahun]);
                });
            }

            // Filter Bulan secara dinamis
            if ($bulan) {
                $query->where(function($q) use ($bulan) {
                    $q->whereMonth('pk.tanggal_pendaftaran_kasasi', $bulan)
                      ->orWhereNull('pk.tanggal_pendaftaran_kasasi');
                });
            }

            $results = $query->get();

            return collect($results)->map(function ($item) use ($database) {
                $cleanAmar = strip_tags($item->amar_putusan_kasasi ?? '');
                $lowerAmar = strtolower($cleanAmar);
                $statusColor = "secondary";
                $statusLabel = "PROSES";

                // Logika penentuan status berdasarkan amar putusan
                if (!empty($cleanAmar)) {
                    if (Str::contains($lowerAmar, ['menolak', 'tolak'])) {
                        $statusLabel = "DITOLAK";
                        $statusColor = "success";
                    } elseif (Str::contains($lowerAmar, ['mengabulkan', 'kabul'])) {
                        $statusLabel = "DIBATALKAN";
                        $statusColor = "danger";
                    } elseif (Str::contains($lowerAmar, ['tidak dapat diterima', 'kabul'])) {
                        $statusLabel = "TIDAK DAPAT DITERIMA";
                        $statusColor = "primary";
                    } else {
                        $statusLabel = "TIDAK TERDEFINISIKAN";
                        $statusColor = "info";
                    }
                }

                return (object) [
                    'unique_id' => $database . '_' . $item->perkara_id,
                    'perkara_id' => $item->perkara_id,
                    'nama_db' => $database,
                    'nomor_urut' => $item->nomor_urut,
                    'pengadilan_agama' => $item->pengadilan_agama,
                    'no_pa' => $item->nomor_perkara_pn ?? '-',
                    'no_pta' => $item->nomor_perkara_banding ?? '-',
                    'no_kasasi' => $item->nomor_perkara_kasasi ?? '-',
                    'jenis_perkara' => $item->jenis_perkara_nama ?? '-',
                    'tgl_reg_kasasi' => $item->tanggal_pendaftaran_kasasi,
                    'tgl_putusan' => ($item->putusan_kasasi && $item->putusan_kasasi != '0000-00-00') ? $item->putusan_kasasi : null,
                    'amar_full' => $item->amar_putusan_kasasi,
                    'status_putusan_kasasi_text' => $item->status_putusan_kasasi_text ?? '-',
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
     * Mengambil daftar tahun yang tersedia dari nomor perkara kasasi
     */
    public function getAvailableYears(): array
    {
        $years = collect();
        $currentYear = (int) date('Y');

        foreach (array_keys(SatkerConfig::SATKERS) as $db) {
            try {
                $res = DB::connection($db)->table('perkara_kasasi')
                    ->selectRaw("DISTINCT RIGHT(TRIM(nomor_perkara_kasasi), 4) as tahun")
                    ->whereNotNull('nomor_perkara_kasasi')
                    ->where('nomor_perkara_kasasi', '!=', '')
                    ->whereRaw("RIGHT(TRIM(nomor_perkara_kasasi), 4) REGEXP '^[0-9]{4}$'")
                    ->get();

                foreach ($res as $row) {
                    $years->push((int) $row->tahun);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        $years->push($currentYear);
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