<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BandingService
{
    protected $barisTotal = null;
    protected $daftarSatker = [
        'BANDUNG',
        'INDRAMAYU',
        'MAJALENGKA',
        'SUMBER',
        'CIAMIS',
        'TASIKMALAYA',
        'KARAWANG',
        'CIMAHI',
        'SUBANG',
        'SUMEDANG',
        'PURWAKARTA',
        'SUKABUMI',
        'CIANJUR',
        'KUNINGAN',
        'CIBADAK',
        'CIREBON',
        'GARUT',
        'BOGOR',
        'BEKASI',
        'CIBINONG',
        'CIKARANG',
        'DEPOK',
        'TASIKKOTA',
        'BANJAR',
        'SOREANG',
        'NGAMPRAH'
    ];

    /**
     * RK1: Laporan Penerimaan (Logika Asli Bapak - Tetap & Tidak Berubah)
     */
    public function getRekap($tglAwal, $tglAkhir)
    {
        $unions = [];
        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            $namaLike = ($satker === 'TASIKKOTA') ? 'Tasikmalaya Kota' : ucfirst($db);

            $hasTable = false;
            try {
                $check = DB::connection('bandung')->select("SHOW TABLES IN {$db} LIKE 'ecourt_banding'");
                $hasTable = count($check) > 0;
            } catch (\Exception $e) {
                $hasTable = false;
            }

            if ($hasTable) {
                $unions[] = "SELECT '{$satker}' as satker_key, a.nama_satker, a.nomor_perkara_banding, MAX(IF(b.nomor_perkara IS NOT NULL, 'E-Court', 'Manual')) AS jenis_pendaftaran FROM siappta.perkara a LEFT JOIN {$db}.ecourt_banding b ON a.nomor_perkara_pa = b.nomor_perkara WHERE a.tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}' AND a.nama_satker LIKE '%{$namaLike}%' GROUP BY a.nomor_perkara_banding, a.nama_satker";
            } else {
                $unions[] = "SELECT '{$satker}' as satker_key, a.nama_satker, a.nomor_perkara_banding, 'Manual' AS jenis_pendaftaran FROM siappta.perkara a WHERE a.tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}' AND a.nama_satker LIKE '%{$namaLike}%' GROUP BY a.nomor_perkara_banding, a.nama_satker";
            }
        }

        $gabunganDataSql = implode("\n UNION ALL \n", $unions);
        $refSql = [];
        foreach ($this->daftarSatker as $s) {
            $namaTampil = ($s === 'TASIKKOTA') ? 'TASIKMALAYA KOTA' : $s;
            $refSql[] = "SELECT '{$s}' as s_key, '{$namaTampil}' as nama_tampil";
        }
        $gabunganRefSql = implode("\n UNION ALL \n", $refSql);

        $sql = "SELECT IFNULL(satker, 'TOTAL SELURUH WILAYAH') AS satker, satker_key, total_perkara, jumlah_ecourt, jumlah_manual
                FROM (SELECT ref.nama_tampil AS satker, MAX(data.satker_key) as satker_key, COUNT(data.nomor_perkara_banding) AS total_perkara,
                      SUM(CASE WHEN data.jenis_pendaftaran = 'E-Court' THEN 1 ELSE 0 END) AS jumlah_ecourt,
                      SUM(CASE WHEN data.jenis_pendaftaran = 'Manual' THEN 1 ELSE 0 END) AS jumlah_manual
                      FROM ($gabunganRefSql) AS ref LEFT JOIN ($gabunganDataSql) AS data ON ref.s_key = data.satker_key
                      GROUP BY ref.nama_tampil WITH ROLLUP) AS hasil
                ORDER BY CASE WHEN satker = 'TOTAL SELURUH WILAYAH' THEN 1 ELSE 0 END, satker ASC";

        $hasilQuery = DB::connection('bandung')->select($sql);
        $dataReguler = [];
        foreach ($hasilQuery as $row) {
            if ($row->satker === 'TOTAL SELURUH WILAYAH') {
                $this->barisTotal = (array) $row;
            } else {
                $dataReguler[] = $row;
            }
        }
        return $dataReguler;
    }

    /**
     * RK2: Keadaan Perkara (Sekarang Sinkron 100% dengan RK1)
     */
    public function getRekapRK2($tglAwal, $tglAkhir)
    {
        $unions = [];
        foreach ($this->daftarSatker as $satker) {
            $filter = $this->getFilterSatkerQuery($satker);
            $unions[] = "SELECT '{$satker}' as satker_key, 
                         SUM(IF(tgl_register < '{$tglAwal}' AND (tgl_putusan IS NULL OR tgl_putusan >= '{$tglAwal}'), 1, 0)) as sisa_lalu,
                         SUM(IF(tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}', 1, 0)) as diterima,
                         SUM(IF(tgl_putusan BETWEEN '{$tglAwal}' AND '{$tglAkhir}', 1, 0)) as selesai
                         FROM (SELECT DISTINCT nomor_perkara_banding, tgl_register, tgl_putusan FROM siappta.perkara WHERE {$filter}) AS data_unik";
        }

        $gabunganSql = implode(" UNION ALL ", $unions);
        return DB::connection('bandung')->select("SELECT satker_key, sisa_lalu, diterima, (sisa_lalu + diterima) as beban, selesai, ((sisa_lalu + diterima) - selesai) as sisa_ini FROM ($gabunganSql) as data ORDER BY satker_key ASC");
    }

    /**
     * FILTER KUNCI: Disamakan dengan Logika String Matching RK1
     */
    private function getFilterSatkerQuery($satker)
    {
        $db = strtolower($satker);
        $namaLike = ($satker === 'TASIKKOTA') ? 'Tasikmalaya Kota' : ucfirst($db);

        // Khusus satker dengan nama mirip, kita definisikan manual agar tidak meleset
        if ($satker === 'SOREANG') {
            return "(nama_satker LIKE '%Soreang%' OR nama_satker LIKE '%Kab%Bandung%')";
        }
        if ($satker === 'NGAMPRAH') {
            return "(nama_satker LIKE '%Ngamprah%' OR nama_satker LIKE '%Bandung Barat%')";
        }

        // Untuk Bandung dan lainnya, pakai LIKE murni sesuai nama satkernya (Tanpa NOT LIKE)
        // Ini yang menjamin total Diterima akan jadi 59, sama dengan RK1.
        return "nama_satker LIKE '%{$namaLike}%'";
    }

    public function getDetailPutusan($satkerKey, $jenis, $tglAwal, $tglAkhir)
    {
        $filterSatker = $this->getFilterSatkerQuery($satkerKey);
        $query = "SELECT DISTINCT a.nomor_perkara_banding, a.nomor_perkara_pa, a.jenis_perkara AS jenis_perkara, a.tgl_register, a.tgl_putusan 
                  FROM siappta.perkara a WHERE {$filterSatker} ";

        if ($jenis == 'sisa_lalu') {
            $query .= " AND a.tgl_register < '{$tglAwal}' AND (a.tgl_putusan IS NULL OR a.tgl_putusan >= '{$tglAwal}')";
        } elseif ($jenis == 'diterima') {
            $query .= " AND a.tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}'";
        } elseif ($jenis == 'beban') {
            $query .= " AND a.tgl_register <= '{$tglAkhir}' AND (a.tgl_putusan IS NULL OR a.tgl_putusan >= '{$tglAwal}')";
        } elseif ($jenis == 'selesai') {
            $query .= " AND a.tgl_putusan BETWEEN '{$tglAwal}' AND '{$tglAkhir}'";
        } elseif ($jenis == 'sisa_ini') {
            $query .= " AND a.tgl_register <= '{$tglAkhir}' AND (a.tgl_putusan IS NULL OR a.tgl_putusan > '{$tglAkhir}')";
        }

        return DB::connection('bandung')->select($query . " ORDER BY a.tgl_register ASC");
    }

    public function getDetailPerkara($satkerKey, $jenis, $tglAwal, $tglAkhir)
    {
        $db = strtolower($satkerKey);
        $namaLike = ($satkerKey === 'TASIKKOTA') ? 'Tasikmalaya Kota' : ucfirst($db);
        $kondisiEcourt = ($jenis === 'ecourt') ? "AND b.nomor_perkara IS NOT NULL" : "";
        $kondisiManual = ($jenis === 'manual') ? "AND b.nomor_perkara IS NULL" : "";

        return DB::connection('bandung')->select("SELECT a.nama_satker, a.nomor_perkara_banding, a.nomor_perkara_pa, a.jenis_perkara AS jenis_perkara, a.tgl_register, IF(b.nomor_perkara IS NOT NULL, 'E-Court', 'Manual') as jenis 
            FROM siappta.perkara a LEFT JOIN {$db}.ecourt_banding b ON a.nomor_perkara_pa = b.nomor_perkara 
            WHERE a.tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}' AND a.nama_satker LIKE '%{$namaLike}%' {$kondisiEcourt} {$kondisiManual} ORDER BY a.tgl_register ASC");
    }

    public function getSummary()
    {
        return $this->barisTotal;
    }

    /**
     * FITUR BARU: Rekap Per Jenis Perkara (Hanya Tambahan, Tidak Merubah Yang Sudah Fix)
     */
    public function getRekapJenisPerkara($tglAwal, $tglAkhir)
    {
        $unions = [];
        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            // Tetap gunakan filter kunci yang sudah sinkron 100%
            $filter = $this->getFilterSatkerQuery($satker);

            $hasTable = false;
            try {
                $check = DB::connection('bandung')->select("SHOW TABLES IN {$db} LIKE 'ecourt_banding'");
                $hasTable = count($check) > 0;
            } catch (\Exception $e) {
                $hasTable = false;
            }

            if ($hasTable) {
                $unions[] = "SELECT a.jenis_perkara as jenis, 
                             MAX(IF(b.nomor_perkara IS NOT NULL, 'E-Court', 'Manual')) as pendaftaran
                             FROM siappta.perkara a LEFT JOIN {$db}.ecourt_banding b ON a.nomor_perkara_pa = b.nomor_perkara 
                             WHERE {$filter} AND a.tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}' 
                             GROUP BY a.nomor_perkara_banding";
            } else {
                $unions[] = "SELECT a.jenis_perkara as jenis, 
                             'Manual' as pendaftaran
                             FROM siappta.perkara a WHERE {$filter} AND a.tgl_register BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
                             GROUP BY a.nomor_perkara_banding";
            }
        }

        $gabunganSql = implode("\n UNION ALL \n", $unions);

        // Query final untuk grouping berdasarkan Jenis Perkara
        // Query final dengan Subquery agar tidak error ROLLUP vs ORDER BY
        $sql = "SELECT * FROM (
                SELECT 
                    IFNULL(jenis, 'TOTAL SELURUH JENIS PERKARA') as kategori,
                    COUNT(*) as total,
                    SUM(CASE WHEN pendaftaran = 'E-Court' THEN 1 ELSE 0 END) as ecourt,
                    SUM(CASE WHEN pendaftaran = 'Manual' THEN 1 ELSE 0 END) as manual
                FROM ($gabunganSql) as data_jenis
                GROUP BY jenis WITH ROLLUP
            ) AS hasil_akhir
            ORDER BY CASE WHEN kategori = 'TOTAL SELURUH JENIS PERKARA' THEN 1 ELSE 0 END, total DESC";

        return DB::connection('bandung')->select($sql);
    }
}
