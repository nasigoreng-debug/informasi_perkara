<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BandingService
{
    // 1. DAFTAR SATKER (Urutan Standar Jabar)
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
     * RK.1: LAPORAN PERKARA DITERIMA
     */
    public function getRekapRK1($tglAwal, $tglAkhir)
    {
        $mapping = $this->getJenisPerkaraMapping('a.');
        $selects = [];
        foreach ($mapping as $alias => $cond) {
            $selects[] = "SUM(IF($cond, 1, 0)) AS {$alias}";
        }

        $satker_case = $this->buildSatkerCaseSql('a.');

        $sql = "SELECT ref.nama_tampil AS satker, data.*, IFNULL(data.total_diterima, 0) as jml
                FROM (" . $this->generateRefSatkerSql() . ") AS ref 
                LEFT JOIN (
                    SELECT 
                        ($satker_case) as satker_key,
                        " . implode(', ', $selects) . ",
                        COUNT(*) as total_diterima
                    FROM siappta.perkara a
                    WHERE a.tgl_register BETWEEN ? AND ?
                    GROUP BY satker_key
                ) AS data ON ref.s_key = data.satker_key
                ORDER BY FIELD(ref.nama_tampil, '" . implode("','", $this->getOrderNames()) . "') ASC";

        return $this->calculateManualTotal(DB::connection('bandung')->select($sql, [$tglAwal, $tglAkhir]));
    }

    /**
     * RK.2: LAPORAN PERKARA DIPUTUS
     */
    public function getRekapRK2($tglAwal, $tglAkhir)
    {
        $mappingTypes = $this->getJenisPerkaraMapping('a.');

        // Logic Status
        $status_case = "CASE 
            WHEN (a.jenis_putus_text LIKE '%Cabut%' OR a.jenis_putus_text = 'Dicabut') THEN 'dicabut'
            WHEN (a.jenis_putus_text LIKE '%Tidak%Terima%' OR a.jenis_putus_text LIKE '%N.O%') THEN 'tidak_diterima'
            WHEN (a.jenis_putus_text LIKE '%Gugur%') THEN 'gugur'
            WHEN (a.jenis_putus_text LIKE '%Coret%') THEN 'dicoret'
            WHEN (a.jenis_putus_text LIKE '%Tolak%') THEN 'ditolak'
            ELSE 'dikabulkan' 
        END";

        // Logic Jenis
        $jenis_case = "CASE ";
        foreach ($mappingTypes as $alias => $cond) {
            $jenis_case .= "WHEN {$cond} THEN '{$alias}' ";
        }
        $jenis_case .= "ELSE 'll' END";

        $selects = [];
        $bindings = [];

        // Sisa & Terima
        $selects[] = "SUM(IF(a.tgl_register < ? AND (a.tgl_putusan IS NULL OR a.tgl_putusan >= ?), 1, 0)) as sisa_lalu";
        array_push($bindings, $tglAwal, $tglAwal);
        $selects[] = "SUM(IF(a.tgl_register BETWEEN ? AND ?, 1, 0)) as diterima";
        array_push($bindings, $tglAwal, $tglAkhir);

        // Status Putusan Utama
        $status_dasar = ['dicabut', 'ditolak', 'tidak_diterima', 'gugur', 'dicoret'];
        foreach ($status_dasar as $st) {
            $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = '$st', 1, 0)) as $st";
            array_push($bindings, $tglAwal, $tglAkhir);
        }

        // Status Tambahan (Kolom 38-41)
        $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND (a.jenis_putus_text LIKE '%Kuat%' OR a.jenis_putus_text LIKE '%Dikuatkan%'), 1, 0)) as Dikuatkan";
        array_push($bindings, $tglAwal, $tglAkhir);
        $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND (a.jenis_putus_text LIKE '%Batal%' OR a.jenis_putus_text LIKE '%Dibatalkan%'), 1, 0)) as Dibatalkan";
        array_push($bindings, $tglAwal, $tglAkhir);
        $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND (a.jenis_putus_text LIKE '%Perbaiki%' OR a.jenis_putus_text LIKE '%Diperbaiki%'), 1, 0)) as Diperbaiki";
        array_push($bindings, $tglAwal, $tglAkhir);

        // Jenis Perkara (7-37)
        foreach ($mappingTypes as $alias => $cond) {
            $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'dikabulkan' AND ($jenis_case) = '{$alias}', 1, 0)) as {$alias}";
            array_push($bindings, $tglAwal, $tglAkhir);
        }

        $satker_case = $this->buildSatkerCaseSql('a.');

        $sql = "SELECT ref.nama_tampil AS satker, data.*, (IFNULL(data.sisa_lalu,0) + IFNULL(data.diterima,0)) as beban 
                FROM (" . $this->generateRefSatkerSql() . ") AS ref 
                LEFT JOIN (
                    SELECT ($satker_case) as satker_key, " . implode(", ", $selects) . "
                    FROM siappta.perkara a GROUP BY satker_key
                ) AS data ON ref.s_key = data.satker_key 
                ORDER BY FIELD(ref.nama_tampil, '" . implode("','", $this->getOrderNames()) . "') ASC";

        return $this->calculateManualTotal(DB::connection('bandung')->select($sql, $bindings));
    }

    // --- FUNGSI MAPPING ---
    public function getJenisPerkaraMapping($pfx = "")
    {
        return [
            'iz' => "{$pfx}jenis_perkara LIKE '%Poligami%'",
            'pp' => "{$pfx}jenis_perkara LIKE '%Pencegahan%'",
            'p_ppn' => "{$pfx}jenis_perkara LIKE '%Penolakan%'",
            'pb' => "{$pfx}jenis_perkara LIKE '%Pembatalan%'",
            'lks' => "{$pfx}jenis_perkara LIKE '%Kelalaian%'",
            'ct' => "{$pfx}jenis_perkara LIKE '%Cerai Talak%'",
            'cg' => "{$pfx}jenis_perkara LIKE '%Cerai Gugat%'",
            'hb' => "{$pfx}jenis_perkara LIKE '%Harta Bersama%'",
            'pa' => "{$pfx}jenis_perkara LIKE '%Penguasaan Anak%'",
            'nai' => "{$pfx}jenis_perkara LIKE '%Nafkah Anak%'",
            'hbi' => "{$pfx}jenis_perkara LIKE '%Hak%Isteri%'",
            'psa' => "{$pfx}jenis_perkara LIKE '%Pengesahan Anak%'",
            'pkot' => "{$pfx}jenis_perkara LIKE '%Pencabutan%Orang Tua%'",
            'pw' => "{$pfx}jenis_perkara LIKE '%Perwalian%'",
            'phw' => "{$pfx}jenis_perkara LIKE '%Pencabutan%Wali%'",
            'pol' => "{$pfx}jenis_perkara LIKE '%Penunjukan%Wali%'",
            'grw' => "{$pfx}jenis_perkara LIKE '%Ganti Rugi%'",
            'aua' => "{$pfx}jenis_perkara LIKE '%Asal Usul%'",
            'pkc' => "{$pfx}jenis_perkara LIKE '%Kawin Campuran%'",
            'isbath' => "{$pfx}jenis_perkara LIKE '%Isbath%'",
            'ik' => "{$pfx}jenis_perkara LIKE '%Izin Kawin%'",
            'dk' => "{$pfx}jenis_perkara LIKE '%Dispensasi%'",
            'wa' => "{$pfx}jenis_perkara LIKE '%Wali Adhol%'",
            'es' => "{$pfx}jenis_perkara LIKE '%Ekonomi Syari%'",
            'kw' => "{$pfx}jenis_perkara LIKE '%Kewarisan%'",
            'wst' => "{$pfx}jenis_perkara LIKE '%Wasiat%'",
            'hb_h' => "{$pfx}jenis_perkara LIKE '%Hibah%'",
            'wkf' => "{$pfx}jenis_perkara LIKE '%Wakaf%'",
            'zkt_infq' => "{$pfx}jenis_perkara LIKE '%Zakat%'",
            'p3hp' => "{$pfx}jenis_perkara LIKE '%Ahli Waris%'",
            'll' => "{$pfx}jenis_perkara LIKE '%Lain-lain%'"
        ];
    }

    // --- FUNGSI PRIVATE PENDUKUNG ---
    private function buildSatkerCaseSql($p)
    {
        $sql = "CASE ";
        foreach ($this->daftarSatker as $s) {
            $filter = $this->getFilterSatkerQuery($s, $p);
            $sql .= "WHEN $filter THEN '$s' ";
        }
        $sql .= "ELSE 'LAINNYA' END";
        return $sql;
    }

    private function getFilterSatkerQuery($s, $p = "")
    {
        if ($s === 'TASIKMALAYA') return "{$p}nama_satker LIKE '%Tasikmalaya%' AND {$p}nama_satker NOT LIKE '%Kota%'";
        if ($s === 'TASIKKOTA') return "({$p}nama_satker LIKE '%Kota%Tasikmalaya%' OR {$p}nama_satker LIKE '%Tasikmalaya%Kota%')";
        if ($s === 'SOREANG') return "({$p}nama_satker LIKE '%Soreang%' OR {$p}nama_satker LIKE '%Kab%Bandung%')";
        if ($s === 'NGAMPRAH') return "({$p}nama_satker LIKE '%Ngamprah%' OR {$p}nama_satker LIKE '%Bandung Barat%')";
        return "{$p}nama_satker LIKE '%" . ucfirst(strtolower($s)) . "%'";
    }

    private function getOrderNames()
    {
        return array_map(fn($s) => $this->getNamaTampil($s), $this->daftarSatker);
    }

    private function generateRefSatkerSql()
    {
        $sqls = [];
        foreach ($this->daftarSatker as $s) {
            $sqls[] = "SELECT '{$s}' as s_key, '" . $this->getNamaTampil($s) . "' as nama_tampil";
        }
        return implode(" UNION ALL ", $sqls);
    }

    private function getNamaTampil($s)
    {
        if ($s === 'TASIKKOTA') return 'KOTA TASIKMALAYA';
        if ($s === 'BANJAR') return 'KOTA BANJAR';
        return $s;
    }

    private function calculateManualTotal($results)
    {
        if (empty($results)) return $results;
        $total = new \stdClass();
        $total->satker = 'JUMLAH KESELURUHAN';
        foreach (array_keys((array)$results[0]) as $key) {
            if ($key !== 'satker' && $key !== 'satker_key') {
                $total->$key = array_sum(array_column($results, $key));
            }
        }
        $results[] = $total;
        return $results;
    }

    public function getRekapJenisBanding($tglAwal, $tglAkhir)
    {
        $mapping = $this->getJenisPerkaraMapping('p.');
        $results = [];
        $grandTotal = ['total' => 0, 'ecourt' => 0, 'manual' => 0];

        // List 26 Satker sesuai query Abang
        $satkers = [
            'bandung',
            'indramayu',
            'majalengka',
            'sumber',
            'ciamis',
            'tasikmalaya',
            'karawang',
            'cimahi',
            'subang',
            'sumedang',
            'purwakarta',
            'sukabumi',
            'cianjur',
            'kuningan',
            'cibadak',
            'cirebon',
            'garut',
            'bogor',
            'bekasi',
            'cibinong',
            'cikarang',
            'depok',
            'tasikkota',
            'banjar',
            'soreang',
            'ngamprah'
        ];

        // Bangun UNION ALL untuk CTE
        $unionSql = collect($satkers)->map(function ($s) {
            return "SELECT nomor_perkara FROM {$s}.ecourt_banding";
        })->implode(' UNION ALL ');

        foreach ($mapping as $alias => $condition) {
            $cleanLabel = str_replace(["p.jenis_perkara LIKE '", "'", "%"], "", $condition);

            $sql = "WITH daftar_ecourt AS ($unionSql)
                SELECT 
                    SUM(CASE WHEN ec.nomor_perkara IS NOT NULL THEN 1 ELSE 0 END) AS ecourt,
                    SUM(CASE WHEN ec.nomor_perkara IS NULL THEN 1 ELSE 0 END) AS manual,
                    COUNT(p.perkara_id) AS total
                FROM siappta.perkara p
                LEFT JOIN daftar_ecourt ec ON TRIM(p.nomor_perkara_pa) = TRIM(ec.nomor_perkara)
                WHERE ($condition) AND p.tgl_register BETWEEN ? AND ?";

            $data = DB::connection('bandung')->selectOne($sql, [$tglAwal, $tglAkhir]);

            $results[] = (object)[
                'kategori' => strtoupper($cleanLabel),
                'total' => $data->total ?? 0,
                'ecourt' => $data->ecourt ?? 0,
                'manual' => $data->manual ?? 0
            ];

            $grandTotal['total'] += ($data->total ?? 0);
            $grandTotal['ecourt'] += ($data->ecourt ?? 0);
            $grandTotal['manual'] += ($data->manual ?? 0);
        }

        // Tambah Baris Total
        $results[] = (object)[
            'kategori' => 'TOTAL SELURUH JENIS PERKARA',
            'total' => $grandTotal['total'],
            'ecourt' => $grandTotal['ecourt'],
            'manual' => $grandTotal['manual']
        ];

        return $results;
    }
}
