<?php

namespace App\Services;

use App\Models\PerkaraSatker;
use App\Config\SatkerConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LaporanPerkaraService
{
    /**
     * Status putusan ID mapping
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
     * Dapatkan data sisa tahun lalu untuk semua satker
     */
    public function getSisaTahunLalu($tahun): Collection
    {
        $results = collect();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $dbName = config("database.connections.{$koneksi}.database");
                
                $query = "
                    SELECT COUNT(DISTINCT p.perkara_id) as jumlah
                    FROM `{$dbName}`.perkara p
                    INNER JOIN `{$dbName}`.perkara_putusan pu ON p.perkara_id = pu.perkara_id
                    WHERE YEAR(p.tanggal_pendaftaran) < ? 
                    AND (pu.tanggal_putusan IS NULL OR YEAR(pu.tanggal_putusan) = ?)
                ";
                
                $result = DB::connection($koneksi)->selectOne($query, [$tahun, $tahun]);
                
                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'jumlah' => $result ? $result->jumlah : 0
                ]);

            } catch (\Exception $e) {
                Log::warning("Gagal getSisaTahunLalu untuk {$namaSatker}: " . $e->getMessage());
                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'jumlah' => 0
                ]);
            }
        }

        return $results;
    }

    /**
     * Dapatkan data perkara diterima untuk semua satker
     */
    public function getPerkaraDiterima($tahun): Collection
    {
        $results = collect();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $dbName = config("database.connections.{$koneksi}.database");
                
                $query = "
                    SELECT COUNT(*) as jumlah
                    FROM `{$dbName}`.perkara p
                    WHERE YEAR(p.tanggal_pendaftaran) = ?
                ";
                
                $result = DB::connection($koneksi)->selectOne($query, [$tahun]);
                
                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'jumlah' => $result ? $result->jumlah : 0
                ]);

            } catch (\Exception $e) {
                Log::warning("Gagal getPerkaraDiterima untuk {$namaSatker}: " . $e->getMessage());
                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'jumlah' => 0
                ]);
            }
        }

        return $results;
    }

    /**
     * Dapatkan data perkara diputus detail untuk semua satker
     */
    public function getPerkaraDiputusDetail($tahun): Collection
    {
        $results = collect();

        foreach (SatkerConfig::SATKERS as $koneksi => $namaSatker) {
            try {
                $dbName = config("database.connections.{$koneksi}.database");
                
                $query = "
                    SELECT
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Izin Poligami' AND pu.status_putusan_id = 62 THEN 1 END) AS izin_poligami,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Pembatalan Perkawinan' AND pu.status_putusan_id = 62 THEN 1 END) AS pembatalan_perkawinan,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Cerai Talak' AND pu.status_putusan_id = 62 THEN 1 END) AS cerai_talak,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Cerai Gugat' AND pu.status_putusan_id = 62 THEN 1 END) AS cerai_gugat,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Harta Bersama' AND pu.status_putusan_id = 62 THEN 1 END) AS harta_bersama,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Penguasaan Anak' AND pu.status_putusan_id = 62 THEN 1 END) AS penguasaan_anak,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Pengesahan Anak' AND pu.status_putusan_id = 62 THEN 1 END) AS pengesahan_anak,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Pencabutan Kekuasaan Orang Tua' AND pu.status_putusan_id = 62 THEN 1 END) AS pencabutan_kekuasaan_ortu,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Perwalian' AND pu.status_putusan_id = 62 THEN 1 END) AS perwalian,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Pencabutan Kekuasaan Wali' AND pu.status_putusan_id = 62 THEN 1 END) AS pencabutan_hak_wali,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Penunjukan orang lain sebagai Wali oleh Pengadilan' AND pu.status_putusan_id = 62 THEN 1 END) AS penunjukan_wali,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Asal Usul Anak' AND pu.status_putusan_id = 62 THEN 1 END) AS asal_usul_anak,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Pengesahan Perkawinan/Istbat Nikah' AND pu.status_putusan_id = 62 THEN 1 END) AS isbath_nikah,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Dispensasi Kawin' AND pu.status_putusan_id = 62 THEN 1 END) AS dispensasi_kawin,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Wali Adhol' AND pu.status_putusan_id = 62 THEN 1 END) AS wali_adhol,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Kewarisan' AND pu.status_putusan_id = 62 THEN 1 END) AS kewarisan,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Lain-Lain' AND pu.status_putusan_id = 62 THEN 1 END) AS lain_lain,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'Ekonomi Syariah' AND pu.status_putusan_id = 62 THEN 1 END) AS ekonomi_syariah,
                        COUNT(CASE WHEN p.jenis_perkara_nama = 'P3HP/Penetapan Ahli Waris' AND pu.status_putusan_id = 62 THEN 1 END) AS penetapan_ahli_waris,
                        COUNT(DISTINCT p.perkara_id) AS total_diputus,
                        COUNT(CASE WHEN pu.status_putusan_id = 67 THEN 1 END) AS dicabut,
                        COUNT(CASE WHEN pu.status_putusan_id = 63 THEN 1 END) AS ditolak,
                        COUNT(CASE WHEN pu.status_putusan_id = 62 THEN 1 END) AS dikabulkan,
                        COUNT(CASE WHEN pu.status_putusan_id IN (64, 92) THEN 1 END) AS tidak_diterima,
                        COUNT(CASE WHEN pu.status_putusan_id IN (65, 93) THEN 1 END) AS gugur,
                        COUNT(CASE WHEN pu.status_putusan_id = 66 THEN 1 END) AS dicoret,
                        ROUND(COUNT(CASE WHEN pu.status_putusan_id = 62 THEN 1 END) * 100.0 / NULLIF(COUNT(DISTINCT p.perkara_id), 0), 2) AS persentase_dikabulkan
                    FROM `{$dbName}`.perkara p
                    INNER JOIN `{$dbName}`.perkara_putusan pu ON p.perkara_id = pu.perkara_id
                    WHERE YEAR(pu.tanggal_putusan) = ?
                ";
                
                $result = DB::connection($koneksi)->selectOne($query, [$tahun]);
                
                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'data' => $result ? (array) $result : $this->getEmptyDiputusDetail()
                ]);

            } catch (\Exception $e) {
                Log::warning("Gagal getPerkaraDiputusDetail untuk {$namaSatker}: " . $e->getMessage());
                $results->push((object)[
                    'nama_satker' => $namaSatker,
                    'koneksi' => $koneksi,
                    'data' => $this->getEmptyDiputusDetail()
                ]);
            }
        }

        return $results;
    }

    /**
     * Dapatkan laporan lengkap gabungan untuk semua satker
     */
    public function getLaporanSemuaSatker($tahun): Collection
    {
        // Ambil semua data
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
            
            // Data per satker
            $row = (object)[
                'nama_satker' => $namaSatker,
                'koneksi' => $koneksi,
                'sisa_tahun_lalu' => $sisa,
                'diterima' => $diterima,
                'beban' => $beban,
                'izin_poligami' => $diputus['izin_poligami'] ?? 0,
                'pembatalan_perkawinan' => $diputus['pembatalan_perkawinan'] ?? 0,
                'cerai_talak' => $diputus['cerai_talak'] ?? 0,
                'cerai_gugat' => $diputus['cerai_gugat'] ?? 0,
                'harta_bersama' => $diputus['harta_bersama'] ?? 0,
                'penguasaan_anak' => $diputus['penguasaan_anak'] ?? 0,
                'pengesahan_anak' => $diputus['pengesahan_anak'] ?? 0,
                'pencabutan_kekuasaan_ortu' => $diputus['pencabutan_kekuasaan_ortu'] ?? 0,
                'perwalian' => $diputus['perwalian'] ?? 0,
                'pencabutan_hak_wali' => $diputus['pencabutan_hak_wali'] ?? 0,
                'penunjukan_wali' => $diputus['penunjukan_wali'] ?? 0,
                'asal_usul_anak' => $diputus['asal_usul_anak'] ?? 0,
                'isbath_nikah' => $diputus['isbath_nikah'] ?? 0,
                'dispensasi_kawin' => $diputus['dispensasi_kawin'] ?? 0,
                'wali_adhol' => $diputus['wali_adhol'] ?? 0,
                'kewarisan' => $diputus['kewarisan'] ?? 0,
                'lain_lain' => $diputus['lain_lain'] ?? 0,
                'ekonomi_syariah' => $diputus['ekonomi_syariah'] ?? 0,
                'penetapan_ahli_waris' => $diputus['penetapan_ahli_waris'] ?? 0,
                'total_diputus' => $diputus['total_diputus'] ?? 0,
                'dicabut' => $diputus['dicabut'] ?? 0,
                'ditolak' => $diputus['ditolak'] ?? 0,
                'dikabulkan' => $diputus['dikabulkan'] ?? 0,
                'tidak_diterima' => $diputus['tidak_diterima'] ?? 0,
                'gugur' => $diputus['gugur'] ?? 0,
                'dicoret' => $diputus['dicoret'] ?? 0,
                'persentase_dikabulkan' => $diputus['persentase_dikabulkan'] ?? 0,
                'sisa' => $sisaAkhir
            ];
            
            $laporan->push($row);
            
            // Akumulasi total
            $this->akumulasiTotal($total, $row);
        }
        
        // Hitung total beban dan sisa akhir
        $total['beban'] = $total['sisa'] + $total['diterima'];
        $total['sisa_akhir'] = $total['beban'] - $total['total_diputus'];
        
        // Tambahkan baris total ke collection
        $laporan->push((object) array_merge(['nama_satker' => 'TOTAL KESELURUHAN'], $total));

        return $laporan;
    }

    /**
     * Template data kosong untuk diputus detail
     */
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
            'penetapan_ahli_waris' => 0,
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

    /**
     * Template data total kosong
     */
    private function getEmptyTotal(): array
    {
        return [
            'sisa' => 0,
            'diterima' => 0,
            'beban' => 0,
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
            'penetapan_ahli_waris' => 0,
            'total_diputus' => 0,
            'dicabut' => 0,
            'ditolak' => 0,
            'dikabulkan' => 0,
            'tidak_diterima' => 0,
            'gugur' => 0,
            'dicoret' => 0,
            'sisa_akhir' => 0
        ];
    }

    /**
     * Akumulasi data untuk total
     */
    private function akumulasiTotal(array &$total, object $row): void
    {
        $total['sisa'] += $row->sisa_tahun_lalu;
        $total['diterima'] += $row->diterima;
        $total['izin_poligami'] += $row->izin_poligami;
        $total['pembatalan_perkawinan'] += $row->pembatalan_perkawinan;
        $total['cerai_talak'] += $row->cerai_talak;
        $total['cerai_gugat'] += $row->cerai_gugat;
        $total['harta_bersama'] += $row->harta_bersama;
        $total['penguasaan_anak'] += $row->penguasaan_anak;
        $total['pengesahan_anak'] += $row->pengesahan_anak;
        $total['pencabutan_kekuasaan_ortu'] += $row->pencabutan_kekuasaan_ortu;
        $total['perwalian'] += $row->perwalian;
        $total['pencabutan_hak_wali'] += $row->pencabutan_hak_wali;
        $total['penunjukan_wali'] += $row->penunjukan_wali;
        $total['asal_usul_anak'] += $row->asal_usul_anak;
        $total['isbath_nikah'] += $row->isbath_nikah;
        $total['dispensasi_kawin'] += $row->dispensasi_kawin;
        $total['wali_adhol'] += $row->wali_adhol;
        $total['kewarisan'] += $row->kewarisan;
        $total['lain_lain'] += $row->lain_lain;
        $total['ekonomi_syariah'] += $row->ekonomi_syariah;
        $total['penetapan_ahli_waris'] += $row->penetapan_ahli_waris;
        $total['total_diputus'] += $row->total_diputus;
        $total['dicabut'] += $row->dicabut;
        $total['ditolak'] += $row->ditolak;
        $total['dikabulkan'] += $row->dikabulkan;
        $total['tidak_diterima'] += $row->tidak_diterima;
        $total['gugur'] += $row->gugur;
        $total['dicoret'] += $row->dicoret;
    }
}