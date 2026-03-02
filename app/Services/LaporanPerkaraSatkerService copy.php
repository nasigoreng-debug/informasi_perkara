<?php

namespace App\Services;

use App\Config\SatkerConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LaporanPerkaraSatkerService
{
    /**
     * Status putusan ID mapping sesuai standar SIPP
     */
    protected $statusPutusan = [
        'dikabulkan' => 62,
        'ditolak' => 63,
        'tidak_diterima' => [64, 92],
        'gugur' => [65, 93],
        'dicoret' => 66,
        'dicabut' => 67,
    ];

    /**
     * Dapatkan data sisa tahun lalu dari seluruh Satker
     */
    public function getSisaTahunLalu($tahun): Collection
    {
        $results = collect();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $dbName = config("database.connections.{$koneksi}.database");

                $jumlah = DB::connection($koneksi)->table("{$dbName}.perkara as p")
                    ->leftJoin("{$dbName}.perkara_putusan as pu", 'p.perkara_id', '=', 'pu.perkara_id')
                    ->whereYear('p.tanggal_pendaftaran', '<', $tahun)
                    ->where(function ($q) use ($tahun) {
                        $q->whereNull('pu.tanggal_putusan')
                            ->orWhereYear('pu.tanggal_putusan', '>=', $tahun);
                    })
                    ->distinct()
                    ->count('p.perkara_id');

                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'jumlah' => $jumlah
                ]);
            } catch (\Exception $e) {
                Log::warning("Gagal getSisaTahunLalu untuk {$namaSatker}: " . $e->getMessage());
                $results->push((object)['nama_satker' => $namaSatker, 'koneksi' => $koneksi, 'jumlah' => 0]);
            }
        }
        return $results;
    }

    /**
     * Dapatkan data perkara diterima per tahun
     */
    public function getPerkaraDiterima($tahun): Collection
    {
        $results = collect();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $dbName = config("database.connections.{$koneksi}.database");

                $jumlah = DB::connection($koneksi)->table("{$dbName}.perkara")
                    ->whereYear('tanggal_pendaftaran', $tahun)
                    ->count();

                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'jumlah' => $jumlah
                ]);
            } catch (\Exception $e) {
                Log::warning("Gagal getPerkaraDiterima untuk {$namaSatker}: " . $e->getMessage());
                $results->push((object)['nama_satker' => $namaSatker, 'koneksi' => $koneksi, 'jumlah' => 0]);
            }
        }
        return $results;
    }

    /**
     * Dapatkan data perkara diputus detail dengan Agregasi CASE WHEN
     */
    public function getPerkaraDiputusDetail($tahun): Collection
    {
        $results = collect();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $dbName = config("database.connections.{$koneksi}.database");

                $result = DB::connection($koneksi)->table("{$dbName}.perkara as p")
                    ->join("{$dbName}.perkara_putusan as pu", 'p.perkara_id', '=', 'pu.perkara_id')
                    ->select([
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Izin Poligami' AND pu.status_putusan_id = 62 THEN 1 END) AS izin_poligami"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Pembatalan Perkawinan' AND pu.status_putusan_id = 62 THEN 1 END) AS pembatalan_perkawinan"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Cerai Talak' AND pu.status_putusan_id = 62 THEN 1 END) AS cerai_talak"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Cerai Gugat' AND pu.status_putusan_id = 62 THEN 1 END) AS cerai_gugat"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Harta Bersama' AND pu.status_putusan_id = 62 THEN 1 END) AS harta_bersama"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Penguasaan Anak' AND pu.status_putusan_id = 62 THEN 1 END) AS penguasaan_anak"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Pengesahan Anak' AND pu.status_putusan_id = 62 THEN 1 END) AS pengesahan_anak"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Pencabutan Kekuasaan Orang Tua' AND pu.status_putusan_id = 62 THEN 1 END) AS pencabutan_kekuasaan_ortu"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Perwalian' AND pu.status_putusan_id = 62 THEN 1 END) AS perwalian"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Pencabutan Kekuasaan Wali' AND pu.status_putusan_id = 62 THEN 1 END) AS pencabutan_hak_wali"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Penunjukan orang lain sebagai Wali oleh Pengadilan' AND pu.status_putusan_id = 62 THEN 1 END) AS penunjukan_wali"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Asal Usul Anak' AND pu.status_putusan_id = 62 THEN 1 END) AS asal_usul_anak"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Pengesahan Perkawinan/Istbat Nikah' AND pu.status_putusan_id = 62 THEN 1 END) AS isbath_nikah"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Dispensasi Kawin' AND pu.status_putusan_id = 62 THEN 1 END) AS dispensasi_kawin"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Wali Adhol' AND pu.status_putusan_id = 62 THEN 1 END) AS wali_adhol"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Kewarisan' AND pu.status_putusan_id = 62 THEN 1 END) AS kewarisan"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Lain-Lain' AND pu.status_putusan_id = 62 THEN 1 END) AS lain_lain"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'Ekonomi Syariah' AND pu.status_putusan_id = 62 THEN 1 END) AS ekonomi_syariah"),
                        DB::raw("COUNT(CASE WHEN p.jenis_perkara_nama = 'P3HP/Penetapan Ahli Waris' AND pu.status_putusan_id = 62 THEN 1 END) AS penetapan_ahli_waris"),
                        DB::raw("COUNT(DISTINCT p.perkara_id) AS total_diputus"),
                        DB::raw("COUNT(CASE WHEN pu.status_putusan_id = 67 THEN 1 END) AS dicabut"),
                        DB::raw("COUNT(CASE WHEN pu.status_putusan_id = 63 THEN 1 END) AS ditolak"),
                        DB::raw("COUNT(CASE WHEN pu.status_putusan_id = 62 THEN 1 END) AS dikabulkan"),
                        DB::raw("COUNT(CASE WHEN pu.status_putusan_id IN (64, 92) THEN 1 END) AS tidak_diterima"),
                        DB::raw("COUNT(CASE WHEN pu.status_putusan_id IN (65, 93) THEN 1 END) AS gugur"),
                        DB::raw("COUNT(CASE WHEN pu.status_putusan_id = 66 THEN 1 END) AS dicoret"),
                        DB::raw("ROUND(COUNT(CASE WHEN pu.status_putusan_id = 62 THEN 1 END) * 100.0 / NULLIF(COUNT(DISTINCT p.perkara_id), 0), 2) AS persentase_dikabulkan")
                    ])
                    ->whereYear('pu.tanggal_putusan', $tahun)
                    ->first();

                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'data' => $result ? (array) $result : $this->getEmptyDiputusDetail()
                ]);
            } catch (\Exception $e) {
                Log::warning("Gagal getPerkaraDiputusDetail untuk {$namaSatker}: " . $e->getMessage());
                $results->push((object)['nama_satker' => $namaSatker, 'koneksi' => $koneksi, 'data' => $this->getEmptyDiputusDetail()]);
            }
        }
        return $results;
    }

    /**
     * Dapatkan laporan lengkap gabungan seluruh Satker
     */
    public function getLaporanSemuaSatker($tahun): Collection
    {
        $sisaData = $this->getSisaTahunLalu($tahun)->keyBy('koneksi');
        $diterimaData = $this->getPerkaraDiterima($tahun)->keyBy('koneksi');
        $diputusData = $this->getPerkaraDiputusDetail($tahun)->keyBy('koneksi');

        $laporan = collect();
        $total = $this->getEmptyTotal();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            $sisa = $sisaData->get($koneksi)->jumlah ?? 0;
            $diterima = $diterimaData->get($koneksi)->jumlah ?? 0;
            $diputus = $diputusData->get($koneksi)->data ?? $this->getEmptyDiputusDetail();

            $beban = $sisa + $diterima;
            $sisaAkhir = $beban - ($diputus['total_diputus'] ?? 0);

            $row = (object) array_merge([
                'nama_satker' => $namaSatker,
                'koneksi' => $koneksi,
                'sisa_tahun_lalu' => $sisa,
                'diterima' => $diterima,
                'beban' => $beban,
                'sisa' => $sisaAkhir
            ], $diputus);

            $laporan->push($row);
            $this->akumulasiTotal($total, $row);
        }

        $total['beban'] = $total['sisa'] + $total['diterima'];
        $total['sisa_akhir'] = $total['beban'] - $total['total_diputus'];
        $laporan->push((object) array_merge(['nama_satker' => 'TOTAL KESELURUHAN'], $total));

        return $laporan;
    }

    /**
     * Mengambil data Putusan Sela dari database siappta
     */
    public function getPutusanSelaSemuaSatker($tgl_awal, $tgl_akhir): Collection
    {
        try {
            // Kita langsung tembak ke koneksi 'siappta'
            // Pastikan di config/database.php sudah ada koneksi bernama 'siappta'
            return DB::connection('siappta')->table('perkara')
                ->select([
                    'perkara_id',
                    'nomor_perkara_banding',
                    'nomor_perkara_pa',
                    'nama_satker', // Field ini ada di tabel Anda
                    'tgl_register',
                    'tgl_putusan_sela',
                    'nama_km as ketua_majelis',
                    'keterangan'
                ])
                ->whereNotNull('tgl_putusan_sela')
                ->where('tgl_putusan_sela', '!=', '')
                ->whereBetween('tgl_putusan_sela', [$tgl_awal, $tgl_akhir])
                ->orderBy('tgl_putusan_sela', 'ASC')
                ->get();
        } catch (\Exception $e) {
            Log::error("Gagal ambil data Putusan Sela: " . $e->getMessage());
            return collect();
        }
    }

    public function PutusanSela(Request $request)
    {
        $results = [];

        foreach (\App\Config\SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                // Mencoba melakukan koneksi dan query sederhana
                $dbName = config("database.connections.{$koneksi}.database");
                $check = \Illuminate\Support\Facades\DB::connection($koneksi)->getPdo();

                // Jika berhasil sampai sini, berarti koneksi fisik LANCAR
                $count = \Illuminate\Support\Facades\DB::connection($koneksi)
                    ->table("{$dbName}.perkara")
                    ->count();

                $results[$namaSatker] = "LANCAR (Total ada $count baris di tabel perkara)";
            } catch (\Exception $e) {
                // Jika gagal, tampilkan error-nya apa
                $results[$namaSatker] = "GAGAL: " . $e->getMessage();
            }
        }

        // Tampilkan hasil pengecekan semua satker di layar
        dd($results);
    }

    private function getEmptyDiputusDetail(): array
    {
        return [
            'izin_poligami' => 0,
            'pembatalan_perkawinan' => 0,
            'cerai_talak' => 0,
            'cerai_gugat' => 0,
            'harta_bersama' => 0,
            'penguasaan_anak' => 0,
            'pengesahan_anak' => 0,
            'pencabutan_kekuasaan_ortu' => 0,
            'perwalian' => 0,
            'pencabutan_hak_wali' => 0,
            'penunjukan_wali' => 0,
            'asal_usul_anak' => 0,
            'isbath_nikah' => 0,
            'dispensasi_kawin' => 0,
            'wali_adhol' => 0,
            'kewarisan' => 0,
            'lain_lain' => 0,
            'ekonomi_syariah' => 0,
            'penetapan_ ahli_waris' => 0,
            'total_diputus' => 0,
            'dicabut' => 0,
            'ditolak' => 0,
            'dikabulkan' => 0,
            'tidak_diterima' => 0,
            'gugur' => 0,
            'dicoret' => 0,
            'persentase_dikabulkan' => 0
        ];
    }

    private function getEmptyTotal(): array
    {
        return array_merge(['sisa' => 0, 'diterima' => 0, 'beban' => 0, 'sisa_akhir' => 0], $this->getEmptyDiputusDetail());
    }

    private function akumulasiTotal(array &$total, object $row): void
    {
        $total['sisa'] += $row->sisa_tahun_lalu;
        $total['diterima'] += $row->diterima;
        foreach (array_keys($this->getEmptyDiputusDetail()) as $key) {
            if ($key !== 'persentase_dikabulkan') {
                $total[$key] += $row->{$key} ?? 0;
            }
        }
    }
}
