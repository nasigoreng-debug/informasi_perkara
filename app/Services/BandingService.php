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
     * RK1: Laporan Penerimaan
     */
    public function getRekap($tglAwal, $tglAkhir)
    {
        $unions = [];
        $bindings = [];

        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            $filter = $this->getFilterSatkerQuery($satker, 'a.');

            // Cek tabel ecourt secara manual untuk menentukan struktur query
            $hasTable = $this->checkTableExists($db, 'ecourt_banding');

            if ($hasTable) {
                $unions[] = "SELECT '{$satker}' as satker_key, a.nama_satker, a.nomor_perkara_banding, 
                             MAX(IF(b.nomor_perkara IS NOT NULL, 'E-Court', 'Manual')) AS jenis_pendaftaran 
                             FROM siappta.perkara a LEFT JOIN {$db}.ecourt_banding b ON a.nomor_perkara_pa = b.nomor_perkara 
                             WHERE a.tgl_register BETWEEN ? AND ? AND {$filter} 
                             GROUP BY a.nomor_perkara_banding, a.nama_satker";
            } else {
                $unions[] = "SELECT '{$satker}' as satker_key, a.nama_satker, a.nomor_perkara_banding, 'Manual' AS jenis_pendaftaran 
                             FROM siappta.perkara a 
                             WHERE a.tgl_register BETWEEN ? AND ? AND {$filter} 
                             GROUP BY a.nomor_perkara_banding, a.nama_satker";
            }
            // Tambahkan tglAwal dan tglAkhir untuk setiap satker (2 parameter per union)
            $bindings[] = $tglAwal;
            $bindings[] = $tglAkhir;
        }

        $gabunganDataSql = implode("\n UNION ALL \n", $unions);

        // Membangun tabel referensi agar satker yang nol tetap muncul
        $refSqls = [];
        foreach ($this->daftarSatker as $s) {
            $namaTampil = ($s === 'TASIKKOTA') ? 'TASIKMALAYA KOTA' : $s;
            $refSqls[] = "SELECT '{$s}' as s_key, '{$namaTampil}' as nama_tampil";
        }
        $gabunganRefSql = implode("\n UNION ALL \n", $refSqls);

        // Gunakan Raw Select dengan bindings yang sudah dikumpulkan
        $sql = "SELECT IFNULL(satker, 'TOTAL SELURUH WILAYAH') AS satker, satker_key, total_perkara, jumlah_ecourt, jumlah_manual
                FROM (
                    SELECT ref.nama_tampil AS satker, MAX(data.satker_key) as satker_key, COUNT(data.nomor_perkara_banding) AS total_perkara,
                    SUM(CASE WHEN data.jenis_pendaftaran = 'E-Court' THEN 1 ELSE 0 END) AS jumlah_ecourt,
                    SUM(CASE WHEN data.jenis_pendaftaran = 'Manual' THEN 1 ELSE 0 END) AS jumlah_manual
                    FROM ($gabunganRefSql) AS ref 
                    LEFT JOIN ($gabunganDataSql) AS data ON ref.s_key = data.satker_key
                    GROUP BY ref.nama_tampil WITH ROLLUP
                ) AS hasil_rollup
                ORDER BY CASE WHEN satker = 'TOTAL SELURUH WILAYAH' THEN 1 ELSE 0 END, satker ASC";

        $hasilQuery = DB::connection('bandung')->select($sql, $bindings);

        return $this->processSummary($hasilQuery);
    }

    /**
     * RK2: Keadaan Perkara
     */
    public function getRekapRK2($tglAwal, $tglAkhir)
    {
        $unions = [];
        $bindings = [];
        foreach ($this->daftarSatker as $satker) {
            $filter = $this->getFilterSatkerQuery($satker);
            $unions[] = "SELECT '{$satker}' as satker_key, 
                         SUM(IF(tgl_register < ? AND (tgl_putusan IS NULL OR tgl_putusan >= ?), 1, 0)) as sisa_lalu,
                         SUM(IF(tgl_register BETWEEN ? AND ?, 1, 0)) as diterima,
                         SUM(IF(tgl_putusan BETWEEN ? AND ?, 1, 0)) as selesai
                         FROM (SELECT DISTINCT nomor_perkara_banding, tgl_register, tgl_putusan FROM siappta.perkara WHERE {$filter}) AS data_unik";

            // 6 parameter per satker (sesuai jumlah ? di atas)
            array_push($bindings, $tglAwal, $tglAwal, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir);
        }

        $gabunganSql = implode(" UNION ALL ", $unions);
        $finalSql = "SELECT satker_key, sisa_lalu, diterima, (sisa_lalu + diterima) as beban, selesai, ((sisa_lalu + diterima) - selesai) as sisa_ini 
                     FROM ($gabunganSql) as data ORDER BY satker_key ASC";

        return DB::connection('bandung')->select($finalSql, $bindings);
    }

    public function getDetailPutusan($satkerKey, $jenis, $tglAwal, $tglAkhir)
    {
        $filterSatker = $this->getFilterSatkerQuery($satkerKey, "a.");
        $query = DB::connection('bandung')->table('siappta.perkara as a')
            ->select('a.nomor_perkara_banding', 'a.nomor_perkara_pa', 'a.jenis_perkara', 'a.tgl_register', 'a.tgl_putusan')
            ->distinct()
            ->whereRaw($filterSatker);

        if ($jenis == 'sisa_lalu') {
            $query->where('a.tgl_register', '<', $tglAwal)
                ->where(fn($q) => $q->whereNull('a.tgl_putusan')->orWhere('a.tgl_putusan', '>=', $tglAwal));
        } elseif ($jenis == 'diterima') {
            $query->whereBetween('a.tgl_register', [$tglAwal, $tglAkhir]);
        } elseif ($jenis == 'beban') {
            $query->where('a.tgl_register', '<=', $tglAkhir)
                ->where(fn($q) => $q->whereNull('a.tgl_putusan')->orWhere('a.tgl_putusan', '>=', $tglAwal));
        } elseif ($jenis == 'selesai') {
            $query->whereBetween('a.tgl_putusan', [$tglAwal, $tglAkhir]);
        } elseif ($jenis == 'sisa_ini') {
            $query->where('a.tgl_register', '<=', $tglAkhir)
                ->where(fn($q) => $q->whereNull('a.tgl_putusan')->orWhere('a.tgl_putusan', '>', $tglAkhir));
        }

        return $query->orderBy('a.tgl_register', 'asc')->get();
    }

    public function getDetailPerkara($satkerKey, $jenis, $tglAwal, $tglAkhir)
    {
        $db = strtolower($satkerKey);
        $filterSatker = $this->getFilterSatkerQuery($satkerKey, "a.");

        $query = DB::connection('bandung')->table('siappta.perkara as a')
            ->leftJoin("{$db}.ecourt_banding as b", 'a.nomor_perkara_pa', '=', 'b.nomor_perkara')
            ->select([
                'a.nama_satker',
                'a.nomor_perkara_banding',
                'a.nomor_perkara_pa',
                'a.jenis_perkara',
                'a.tgl_register',
                // Menggunakan MAX untuk memastikan hanya satu status yang diambil jika ada duplikat di tabel join
                DB::raw("MAX(IF(b.nomor_perkara IS NOT NULL, 'E-Court', 'Manual')) as jenis")
            ])
            ->whereBetween('a.tgl_register', [$tglAwal, $tglAkhir])
            ->whereRaw($filterSatker)
            // Tambahkan Group By untuk menyatukan baris yang sama
            ->groupBy('a.nomor_perkara_banding', 'a.nama_satker', 'a.nomor_perkara_pa', 'a.jenis_perkara', 'a.tgl_register');

        if ($jenis === 'ecourt') $query->havingRaw("jenis = 'E-Court'");
        if ($jenis === 'manual') $query->havingRaw("jenis = 'Manual'");

        return $query->orderBy('a.tgl_register', 'asc')->get();
    }

    public function getRekapJenisPerkara($tglAwal, $tglAkhir)
    {
        $unions = [];
        $bindings = [];
        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            $filter = $this->getFilterSatkerQuery($satker, "a.");

            if ($this->checkTableExists($db, 'ecourt_banding')) {
                $unions[] = "SELECT a.jenis_perkara as jenis, MAX(IF(b.nomor_perkara IS NOT NULL, 'E-Court', 'Manual')) as pendaftaran
                             FROM siappta.perkara a LEFT JOIN {$db}.ecourt_banding b ON a.nomor_perkara_pa = b.nomor_perkara 
                             WHERE {$filter} AND a.tgl_register BETWEEN ? AND ? GROUP BY a.nomor_perkara_banding";
            } else {
                $unions[] = "SELECT a.jenis_perkara as jenis, 'Manual' as pendaftaran
                             FROM siappta.perkara a WHERE {$filter} AND a.tgl_register BETWEEN ? AND ? GROUP BY a.nomor_perkara_banding";
            }
            array_push($bindings, $tglAwal, $tglAkhir);
        }

        $gabunganSql = implode("\n UNION ALL \n", $unions);
        $sql = "SELECT kategori, total, ecourt, manual FROM (
                    SELECT IFNULL(jenis, 'TOTAL SELURUH JENIS PERKARA') as kategori, COUNT(*) as total, 
                    SUM(CASE WHEN pendaftaran = 'E-Court' THEN 1 ELSE 0 END) as ecourt, 
                    SUM(CASE WHEN pendaftaran = 'Manual' THEN 1 ELSE 0 END) as manual 
                    FROM ($gabunganSql) as data_jenis GROUP BY jenis WITH ROLLUP
                ) AS hasil_akhir 
                ORDER BY CASE WHEN kategori = 'TOTAL SELURUH JENIS PERKARA' THEN 1 ELSE 0 END, total DESC";

        return DB::connection('bandung')->select($sql, $bindings);
    }

    private function getFilterSatkerQuery($satker, $prefix = "")
    {
        if ($satker === 'TASIKMALAYA') return "{$prefix}nama_satker LIKE '%Tasikmalaya%' AND {$prefix}nama_satker NOT LIKE '%Kota%'";
        if ($satker === 'TASIKKOTA') return "({$prefix}nama_satker LIKE '%Kota%Tasikmalaya%' OR {$prefix}nama_satker LIKE '%Tasikmalaya%Kota%')";
        if ($satker === 'SOREANG') return "({$prefix}nama_satker LIKE '%Soreang%' OR {$prefix}nama_satker LIKE '%Kab%Bandung%')";
        if ($satker === 'NGAMPRAH') return "({$prefix}nama_satker LIKE '%Ngamprah%' OR {$prefix}nama_satker LIKE '%Bandung Barat%')";

        $namaLike = ucfirst(strtolower($satker));
        return "{$prefix}nama_satker LIKE '%{$namaLike}%'";
    }

    private function checkTableExists($db, $table)
    {
        try {
            return count(DB::connection('bandung')->select("SHOW TABLES IN {$db} LIKE '{$table}'")) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function processSummary($results)
    {
        $dataReguler = [];
        foreach ($results as $row) {
            if ($row->satker === 'TOTAL SELURUH WILAYAH') {
                $this->barisTotal = (array) $row;
            } else {
                $dataReguler[] = $row;
            }
        }
        return $dataReguler;
    }

    public function getSummary()
    {
        return $this->barisTotal;
    }
}
