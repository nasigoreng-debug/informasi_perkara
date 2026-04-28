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
     * MAPPING JENIS PERKARA
     * Terpusat agar perubahan label atau kondisi SQL hanya dilakukan di satu tempat.
     */
    public function getJenisPerkaraMapping($pfx = "")
    {
        return [
            'iz'      => ['label' => 'Izin Poligami', 'cond' => "{$pfx}jenis_perkara LIKE '%Poligami%'"],
            'pp'      => ['label' => 'Pencegahan Perkawinan', 'cond' => "{$pfx}jenis_perkara LIKE '%Pencegahan%'"],
            'p_ppn'   => ['label' => 'Penolakan Perkawinan oleh PPN', 'cond' => "{$pfx}jenis_perkara LIKE '%Penolakan%'"],
            'pb'      => ['label' => 'Pembatalan Perkawinan', 'cond' => "{$pfx}jenis_perkara LIKE '%Pembatalan%'"],
            'lks'     => ['label' => 'Kelalaian Kewajiban Suami Istri', 'cond' => "{$pfx}jenis_perkara LIKE '%Kelalaian%'"],
            'ct'      => ['label' => 'Cerai Talak', 'cond' => "{$pfx}jenis_perkara LIKE '%Cerai Talak%'"],
            'cg'      => ['label' => 'Cerai Gugat', 'cond' => "{$pfx}jenis_perkara LIKE '%Cerai Gugat%'"],
            'hb'      => ['label' => 'Harta Bersama', 'cond' => "{$pfx}jenis_perkara LIKE '%Harta Bersama%'"],
            'pa'      => ['label' => 'Penguasaan Anak / Hadlonah', 'cond' => "{$pfx}jenis_perkara LIKE '%Penguasaan Anak%'"],
            'nai'     => ['label' => 'Nafkah Anak oleh Ibu', 'cond' => "{$pfx}jenis_perkara LIKE '%Nafkah Anak%'"],
            'hbi'     => ['label' => 'Hak-hak Bekas Istri', 'cond' => "{$pfx}jenis_perkara LIKE '%Hak%Isteri%'"],
            'psa'     => ['label' => 'Pengesahan Anak', 'cond' => "{$pfx}jenis_perkara LIKE '%Pengesahan Anak%'"],
            'pkot'    => ['label' => 'Pencabutan Kekuasaan Orang Tua', 'cond' => "{$pfx}jenis_perkara LIKE '%Pencabutan%Orang Tua%'"],
            'pw'      => ['label' => 'Perwalian', 'cond' => "{$pfx}jenis_perkara LIKE '%Perwalian%'"],
            'phw'     => ['label' => 'Pencabutan Kekuasaan Wali', 'cond' => "{$pfx}jenis_perkara LIKE '%Pencabutan%Wali%'"],
            'pol'     => ['label' => 'Penunjukan Orang Lain Sebagai Wali', 'cond' => "{$pfx}jenis_perkara LIKE '%Penunjukan%Wali%'"],
            'grw'     => ['label' => 'Ganti Rugi Terhadap Wali', 'cond' => "{$pfx}jenis_perkara LIKE '%Ganti Rugi%'"],
            'aua'     => ['label' => 'Asal Usul Anak', 'cond' => "{$pfx}jenis_perkara LIKE '%Asal Usul%'"],
            'pkc'     => ['label' => 'Penolakan Kawin Campuran', 'cond' => "{$pfx}jenis_perkara LIKE '%Kawin Campuran%'"],
            'isbath'  => ['label' => 'Pengesahan Nikah / Isbath', 'cond' => "{$pfx}jenis_perkara LIKE '%Isbath%'"],
            'ik'      => ['label' => 'Izin Kawin', 'cond' => "{$pfx}jenis_perkara LIKE '%Izin Kawin%'"],
            'dk'      => ['label' => 'Dispensasi Kawin', 'cond' => "{$pfx}jenis_perkara LIKE '%Dispensasi%'"],
            'wa'      => ['label' => 'Wali Adhol', 'cond' => "{$pfx}jenis_perkara LIKE '%Wali Adhol%'"],
            'es'      => ['label' => 'Ekonomi Syariah', 'cond' => "{$pfx}jenis_perkara LIKE '%Ekonomi Syari%'"],
            'kw'      => ['label' => 'Kewarisan', 'cond' => "{$pfx}jenis_perkara LIKE '%Kewarisan%'"],
            'wst'     => ['label' => 'Wasiat', 'cond' => "{$pfx}jenis_perkara LIKE '%Wasiat%'"],
            'hb_h'    => ['label' => 'Hibah', 'cond' => "{$pfx}jenis_perkara LIKE '%Hibah%'"],
            'wkf'     => ['label' => 'Wakaf', 'cond' => "{$pfx}jenis_perkara LIKE '%Wakaf%'"],
            'zkt_infq' => ['label' => 'Zakat / Infaq', 'cond' => "{$pfx}jenis_perkara LIKE '%Zakat%'"],
            'p3hp'    => ['label' => 'Penetapan Ahli Waris', 'cond' => "{$pfx}jenis_perkara LIKE '%Ahli Waris%'"],
            'll'      => ['label' => 'Lain-lain', 'cond' => "{$pfx}jenis_perkara LIKE '%Lain-lain%'"]
        ];
    }

    /**
     * RK.1: LAPORAN PERKARA DITERIMA
     */
    public function getRekapRK1($tglAwal, $tglAkhir)
    {
        $mapping = $this->getJenisPerkaraMapping('a.');
        $selects = [];
        foreach ($mapping as $alias => $map) {
            $selects[] = "SUM(IF({$map['cond']}, 1, 0)) AS {$alias}";
        }

        $satker_case = $this->buildSatkerCaseSql('a.');
        $orderFields = "'" . implode("','", $this->getOrderNames()) . "'";

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
                ORDER BY FIELD(ref.nama_tampil, $orderFields) ASC";

        return $this->calculateManualTotal(DB::connection('bandung')->select($sql, [$tglAwal, $tglAkhir]));
    }

    /**
     * RK.2: LAPORAN PERKARA DIPUTUS
     */
    public function getRekapRK2($tglAwal, $tglAkhir)
    {
        $mappingTypes = $this->getJenisPerkaraMapping('a.');

        $status_case = "CASE 
            WHEN (a.jenis_putus_text LIKE '%Cabut%' OR a.jenis_putus_text = 'Dicabut') THEN 'dicabut'
            WHEN (a.jenis_putus_text LIKE '%Tidak%Terima%' OR a.jenis_putus_text LIKE '%N.O%') THEN 'tidak_diterima'
            WHEN (a.jenis_putus_text LIKE '%Gugur%') THEN 'gugur'
            WHEN (a.jenis_putus_text LIKE '%Coret%') THEN 'dicoret'
            WHEN (a.jenis_putus_text LIKE '%Tolak%') THEN 'ditolak'
            ELSE 'dikabulkan' 
        END";

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

        // Status Tambahan
        $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND (a.jenis_putus_text LIKE '%Kuat%' OR a.jenis_putus_text LIKE '%Dikuatkan%'), 1, 0)) as Dikuatkan";
        array_push($bindings, $tglAwal, $tglAkhir);
        $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND (a.jenis_putus_text LIKE '%Batal%' OR a.jenis_putus_text LIKE '%Dibatalkan%'), 1, 0)) as Dibatalkan";
        array_push($bindings, $tglAwal, $tglAkhir);
        $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND (a.jenis_putus_text LIKE '%Perbaiki%' OR a.jenis_putus_text LIKE '%Diperbaiki%'), 1, 0)) as Diperbaiki";
        array_push($bindings, $tglAwal, $tglAkhir);

        // Jenis Perkara (Jika dikabulkan)
        foreach ($mappingTypes as $alias => $map) {
            $selects[] = "SUM(IF(a.tgl_putusan BETWEEN ? AND ? AND ($status_case) = 'dikabulkan' AND {$map['cond']}, 1, 0)) as {$alias}";
            array_push($bindings, $tglAwal, $tglAkhir);
        }

        $satker_case = $this->buildSatkerCaseSql('a.');
        $orderFields = "'" . implode("','", $this->getOrderNames()) . "'";

        $sql = "SELECT ref.nama_tampil AS satker, data.*, (IFNULL(data.sisa_lalu,0) + IFNULL(data.diterima,0)) as beban 
                FROM (" . $this->generateRefSatkerSql() . ") AS ref 
                LEFT JOIN (
                    SELECT ($satker_case) as satker_key, " . implode(", ", $selects) . "
                    FROM siappta.perkara a GROUP BY satker_key
                ) AS data ON ref.s_key = data.satker_key 
                ORDER BY FIELD(ref.nama_tampil, $orderFields) ASC";

        return $this->calculateManualTotal(DB::connection('bandung')->select($sql, $bindings));
    }

    /**
     * REKAP JENIS BANDING (E-COURT VS MANUAL)
     * Dioptimasi menggunakan single query untuk mencegah bottleneck.
     */
    public function getRekapJenisBanding($tglAwal, $tglAkhir)
    {
        $mapping = $this->getJenisPerkaraMapping('p.');

        // Gabungkan semua tabel ecourt_banding satker
        $unionSql = collect($this->daftarSatker)->map(function ($s) {
            $db = strtolower($s);
            return "SELECT nomor_perkara FROM {$db}.ecourt_banding";
        })->implode(' UNION ALL ');

        // Bangun CASE statement untuk menghitung semua jenis sekaligus
        $caseSql = [];
        foreach ($mapping as $alias => $map) {
            $cond = $map['cond'];
            $caseSql[] = "SUM(CASE WHEN $cond THEN 1 ELSE 0 END) AS total_{$alias}";
            $caseSql[] = "SUM(CASE WHEN $cond AND ec.nomor_perkara IS NOT NULL THEN 1 ELSE 0 END) AS ecourt_{$alias}";
        }

        $sql = "WITH daftar_ecourt AS ($unionSql)
                SELECT " . implode(", ", $caseSql) . "
                FROM siappta.perkara p
                LEFT JOIN daftar_ecourt ec ON TRIM(p.nomor_perkara_pa) = TRIM(ec.nomor_perkara)
                WHERE p.tgl_register BETWEEN ? AND ?";

        $data = DB::connection('bandung')->selectOne($sql, [$tglAwal, $tglAkhir]);

        $results = [];
        $grandTotal = ['total' => 0, 'ecourt' => 0, 'manual' => 0];

        foreach ($mapping as $alias => $map) {
            $t = (int)($data->{"total_$alias"} ?? 0);
            $e = (int)($data->{"ecourt_$alias"} ?? 0);
            $m = $t - $e;

            $results[] = (object)[
                'kategori' => strtoupper($map['label']),
                'total'    => $t,
                'ecourt'   => $e,
                'manual'   => $m
            ];

            $grandTotal['total']  += $t;
            $grandTotal['ecourt'] += $e;
            $grandTotal['manual'] += $m;
        }

        $results[] = (object)[
            'kategori' => 'TOTAL SELURUH JENIS PERKARA',
            'total'    => $grandTotal['total'],
            'ecourt'   => $grandTotal['ecourt'],
            'manual'   => $grandTotal['manual']
        ];

        return $results;
    }

    // --- HELPER METHODS ---

    private function buildSatkerCaseSql($p)
    {
        $sql = "CASE ";
        foreach ($this->daftarSatker as $s) {
            $filter = $this->getFilterSatkerQuery($s, $p);
            $sql .= "WHEN $filter THEN '$s' ";
        }
        return $sql . "ELSE 'LAINNYA' END";
    }

    private function getFilterSatkerQuery($s, $p = "")
    {
        if ($s === 'TASIKMALAYA') return "{$p}nama_satker LIKE '%Tasikmalaya%' AND {$p}nama_satker NOT LIKE '%Kota%'";
        if ($s === 'TASIKKOTA')   return "({$p}nama_satker LIKE '%Kota%Tasikmalaya%' OR {$p}nama_satker LIKE '%Tasikmalaya%Kota%')";
        if ($s === 'SOREANG')     return "({$p}nama_satker LIKE '%Soreang%' OR {$p}nama_satker LIKE '%Kab%Bandung%')";
        if ($s === 'NGAMPRAH')    return "({$p}nama_satker LIKE '%Ngamprah%' OR {$p}nama_satker LIKE '%Bandung Barat%')";
        return "{$p}nama_satker LIKE '%" . ucfirst(strtolower($s)) . "%'";
    }

    private function generateRefSatkerSql()
    {
        $sqls = [];
        foreach ($this->daftarSatker as $s) {
            $nama = str_replace("'", "''", $this->getNamaTampil($s));
            $sqls[] = "SELECT '{$s}' as s_key, '{$nama}' as nama_tampil";
        }
        return implode(" UNION ALL ", $sqls);
    }

    private function getOrderNames()
    {
        return array_map(fn($s) => $this->getNamaTampil($s), $this->daftarSatker);
    }

    private function getNamaTampil($s)
    {
        if ($s === 'TASIKKOTA') return 'KOTA TASIKMALAYA';
        if ($s === 'BANJAR')    return 'KOTA BANJAR';
        return $s;
    }

    private function calculateManualTotal($results)
    {
        if (empty($results)) return $results;
        $total = new \stdClass();
        $total->satker = 'JUMLAH KESELURUHAN';
        foreach (array_keys((array)$results[0]) as $key) {
            if (!in_array($key, ['satker', 'satker_key'])) {
                $total->$key = array_sum(array_column($results, $key));
            }
        }
        $results[] = $total;
        return $results;
    }
}
