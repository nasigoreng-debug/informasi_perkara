<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BandingService
{
    protected $barisTotal = null;

    // Daftar Satker Urut sesuai Standar
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
     * RK.1: Laporan Perkara Diterima (SINKRON & FINAL)
     */
    public function getRekapRK1($tglAwal, $tglAkhir)
    {
        $unions = [];
        $bindings = [];
        foreach ($this->daftarSatker as $satker) {
            $filter = $this->getFilterSatkerQuery($satker, 'a.');
            $unions[] = "SELECT '{$satker}' as satker_key, a.nomor_perkara_banding, a.jenis_perkara 
                         FROM siappta.perkara a 
                         WHERE a.tgl_register BETWEEN ? AND ? AND {$filter}";
            $bindings[] = $tglAwal;
            $bindings[] = $tglAkhir;
        }

        $gabunganDataSql = implode("\n UNION ALL \n", $unions);
        $gabunganRefSql = $this->generateRefSatkerSql();
        $mapping = $this->getJenisPerkaraMapping('data.');

        $selects = [];
        foreach ($mapping as $alias => $cond) {
            $selects[] = "SUM(CASE WHEN {$cond} THEN 1 ELSE 0 END) AS {$alias}";
        }
        $rawSelect = implode(', ', $selects);
        $orderList = "'" . implode("','", $this->getOrderNames()) . "'";

        $sql = "SELECT * FROM (
                    SELECT IFNULL(ref.nama_tampil, 'JUMLAH KESELURUHAN') AS satker, 
                    {$rawSelect}, COUNT(data.nomor_perkara_banding) AS jml
                    FROM ($gabunganRefSql) AS ref 
                    LEFT JOIN ($gabunganDataSql) AS data ON ref.s_key = data.satker_key
                    GROUP BY ref.nama_tampil WITH ROLLUP
                ) AS hasil_akhir
                ORDER BY CASE WHEN satker = 'JUMLAH KESELURUHAN' THEN 1 ELSE 0 END ASC, FIELD(satker, {$orderList}) ASC";

        return $this->processSummary(DB::connection('bandung')->select($sql, $bindings));
    }

    /**
     * RK.2: Laporan Perkara Diputus (SINKRON 100% SESUAI CSV)
     */
    public function getRekapRK2($tglAwal, $tglAkhir)
    {
        $unions = [];
        $bindings = [];
        $mappingTypes = $this->getJenisPerkaraMapping('a.');

        // Status Logic PTA (Banding)
        $status_case = "CASE 
            WHEN (a.jenis_putus_text LIKE '%Cabut%' OR a.jenis_putus_text = 'Dicabut') THEN 'dicabut'
            WHEN (a.jenis_putus_text LIKE '%Tidak%Terima%' OR a.jenis_putus_text LIKE '%N.O%') THEN 'tidak_diterima'
            WHEN (a.jenis_putus_text LIKE '%Gugur%') THEN 'gugur'
            WHEN (a.jenis_putus_text LIKE '%Coret%') THEN 'dicoret'
            WHEN (a.jenis_putus_text LIKE '%Tolak%') THEN 'ditolak'
            ELSE 'dikabulkan' 
        END";

        // Jenis Logic (Anti Kumulasi - 1 Berkas 1 Jenis Utama)
        $jenis_case = "CASE ";
        foreach ($mappingTypes as $alias => $cond) {
            $jenis_case .= "WHEN {$cond} THEN '{$alias}' ";
        }
        $jenis_case .= "ELSE 'll' END";

        foreach ($this->daftarSatker as $satker) {
            $filter = $this->getFilterSatkerQuery($satker, 'a.');
            $selects = [
                "SUM(IF(a.tgl_register < ? AND (a.tgl_putusan IS NULL OR a.tgl_putusan >= ?), 1, 0)) as sisa_lalu",
                "SUM(IF(a.tgl_register BETWEEN ? AND ?, 1, 0)) as diterima",
                "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'dicabut', 1, 0)) as dicabut",
                "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'ditolak', 1, 0)) as ditolak",
                "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'tidak_diterima', 1, 0)) as tidak_diterima",
                "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'gugur', 1, 0)) as gugur",
                "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'dicoret', 1, 0)) as dicoret"
            ];
            foreach ($mappingTypes as $alias => $cond) {
                $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'dikabulkan' AND ($jenis_case) = '{$alias}', 1, 0)) as {$alias}";
            }

            $unions[] = "SELECT '{$satker}' as satker_key, " . implode(", ", $selects) . " FROM siappta.perkara a WHERE {$filter}";
            array_push($bindings, $tglAwal, $tglAwal, $tglAwal, $tglAkhir);
            array_push($bindings, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir, $tglAwal, $tglAkhir);
            foreach ($mappingTypes as $m) {
                array_push($bindings, $tglAwal, $tglAkhir);
            }
        }

        $orderList = "'" . implode("','", $this->getOrderNames()) . "'";
        $sql = "SELECT ref.nama_tampil AS satker, data.*, (IFNULL(data.sisa_lalu,0) + IFNULL(data.diterima,0)) as beban 
                FROM (" . $this->generateRefSatkerSql() . ") AS ref 
                LEFT JOIN (" . implode(" UNION ALL ", $unions) . ") AS data ON ref.s_key = data.satker_key 
                ORDER BY FIELD(ref.nama_tampil, {$orderList}) ASC";

        return $this->calculateManualTotal(DB::connection('bandung')->select($sql, $bindings));
    }

    // --- FUNGSI PENDUKUNG (INI YANG BIKIN ERROR KALAU GAK ADA) ---

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

    private function getFilterSatkerQuery($s, $p = "")
    {
        if ($s === 'TASIKMALAYA') return "{$p}nama_satker LIKE '%Tasikmalaya%' AND {$p}nama_satker NOT LIKE '%Kota%'";
        if ($s === 'TASIKKOTA') return "({$p}nama_satker LIKE '%Kota%Tasikmalaya%' OR {$p}nama_satker LIKE '%Tasikmalaya%Kota%')";
        if ($s === 'SOREANG') return "({$p}nama_satker LIKE '%Soreang%' OR {$p}nama_satker LIKE '%Kab%Bandung%')";
        if ($s === 'NGAMPRAH') return "({$p}nama_satker LIKE '%Ngamprah%' OR {$p}nama_satker LIKE '%Bandung Barat%')";
        return "{$p}nama_satker LIKE '%" . ucfirst(strtolower($s)) . "%'";
    }

    private function getJenisPerkaraMapping($pfx = "")
    {
        return ['iz' => "{$pfx}jenis_perkara LIKE '%Poligami%'", 'pp' => "{$pfx}jenis_perkara LIKE '%Pencegahan%'", 'p_ppn' => "{$pfx}jenis_perkara LIKE '%Penolakan%'", 'pb' => "{$pfx}jenis_perkara LIKE '%Pembatalan%'", 'lks' => "{$pfx}jenis_perkara LIKE '%Kelalaian%'", 'ct' => "{$pfx}jenis_perkara LIKE '%Cerai Talak%'", 'cg' => "{$pfx}jenis_perkara LIKE '%Cerai Gugat%'", 'hb' => "{$pfx}jenis_perkara LIKE '%Harta Bersama%'", 'pa' => "{$pfx}jenis_perkara LIKE '%Penguasaan Anak%'", 'nai' => "{$pfx}jenis_perkara LIKE '%Nafkah Anak%'", 'hbi' => "{$pfx}jenis_perkara LIKE '%Hak%Isteri%'", 'psa' => "({$pfx}jenis_perkara LIKE '%Pengesahan Anak%' OR {$pfx}jenis_perkara LIKE '%Pengangkatan Anak%')", 'pkot' => "{$pfx}jenis_perkara LIKE '%Pencabutan%Orang Tua%'", 'pw' => "{$pfx}jenis_perkara LIKE '%Perwalian%'", 'phw' => "{$pfx}jenis_perkara LIKE '%Pencabutan%Wali%'", 'pol' => "{$pfx}jenis_perkara LIKE '%Penunjukan%Lain%'", 'grw' => "{$pfx}jenis_perkara LIKE '%Ganti Rugi%'", 'aua' => "{$pfx}jenis_perkara LIKE '%Asal Usul%'", 'pkc' => "{$pfx}jenis_perkara LIKE '%Kawin Campuran%'", 'isbath' => "{$pfx}jenis_perkara LIKE '%Isbath%'", 'ik' => "{$pfx}jenis_perkara LIKE '%Izin Kawin%'", 'dk' => "{$pfx}jenis_perkara LIKE '%Dispensasi%'", 'wa' => "{$pfx}jenis_perkara LIKE '%Wali Adhol%'", 'es' => "{$pfx}jenis_perkara LIKE '%Ekonomi Syari%'", 'kw' => "{$pfx}jenis_perkara LIKE '%Kewarisan%'", 'wst' => "{$pfx}jenis_perkara LIKE '%Wasiat%'", 'hb_h' => "{$pfx}jenis_perkara LIKE '%Hibah%'", 'wkf' => "{$pfx}jenis_perkara LIKE '%Wakaf%'", 'zkt_infq' => "({$pfx}jenis_perkara LIKE '%Zakat%' OR {$pfx}jenis_perkara LIKE '%Infaq%')", 'p3hp' => "({$pfx}jenis_perkara LIKE '%P3HP%' OR {$pfx}jenis_perkara LIKE '%Ahli Waris%')", 'll' => "{$pfx}jenis_perkara LIKE '%Lain-lain%'"];
    }

    protected function processSummary($results)
    {
        return $results;
    }
}
