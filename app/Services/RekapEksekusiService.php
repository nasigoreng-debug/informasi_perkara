<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RekapEksekusiService
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

    public function getRekap($tglAwal, $tglAkhir)
    {
        $unions = [];
        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            $unions[] = "SELECT '{$satker}' AS satker, pe.permohonan_eksekusi, lipa5.tanggal_selesai FROM {$db}.perkara_eksekusi pe LEFT JOIN elaporan.lipa5 ON pe.nomor_register_eksekusi = lipa5.nomor_eksekusi";
            $unions[] = "SELECT '{$satker}' AS satker, peh.permohonan_eksekusi, lipa5.tanggal_selesai FROM {$db}.perkara_eksekusi_ht peh LEFT JOIN elaporan.lipa5 ON peh.eksekusi_nomor_perkara = lipa5.nomor_eksekusi";
        }

        $gabunganSatkerSql = implode("\n UNION ALL \n", $unions);

        $sql = "
            SELECT IFNULL(satker, 'TOTAL SEMUA SATKER') AS satker, SISA, DITERIMA, BEBAN, SELESAI, `SISA_TAHUN_INI`
            FROM (
                SELECT satker,
                    -- SISA LALU: Diterima sebelum tglAwal DAN (Belum Selesai ATAU Selesai pada/setelah tglAwal)
                    SUM(CASE WHEN permohonan_eksekusi < '$tglAwal' AND (tanggal_selesai IS NULL OR tanggal_selesai = '0000-00-00' OR tanggal_selesai >= '$tglAwal') THEN 1 ELSE 0 END) AS SISA,
                    
                    -- DITERIMA: Registrasi dalam range tanggal
                    SUM(CASE WHEN permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir' THEN 1 ELSE 0 END) AS DITERIMA,
                    
                    -- BEBAN: Sisa Lalu + Diterima
                    (SUM(CASE WHEN permohonan_eksekusi < '$tglAwal' AND (tanggal_selesai IS NULL OR tanggal_selesai = '0000-00-00' OR tanggal_selesai >= '$tglAwal') THEN 1 ELSE 0 END) + 
                     SUM(CASE WHEN permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir' THEN 1 ELSE 0 END)) AS BEBAN,
                    
                    -- SELESAI: Tanggal selesai masuk dalam range
                    SUM(CASE WHEN tanggal_selesai >= '$tglAwal' AND tanggal_selesai <= '$tglAkhir' AND tanggal_selesai != '0000-00-00' THEN 1 ELSE 0 END) AS SELESAI,
                    
                    -- SISA KINI: Beban - Selesai
                    ((SUM(CASE WHEN permohonan_eksekusi < '$tglAwal' AND (tanggal_selesai IS NULL OR tanggal_selesai = '0000-00-00' OR tanggal_selesai >= '$tglAwal') THEN 1 ELSE 0 END) + 
                      SUM(CASE WHEN permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir' THEN 1 ELSE 0 END)) - 
                     SUM(CASE WHEN tanggal_selesai >= '$tglAwal' AND tanggal_selesai <= '$tglAkhir' AND tanggal_selesai != '0000-00-00' THEN 1 ELSE 0 END)) AS `SISA_TAHUN_INI`
                FROM ($gabunganSatkerSql) AS data_satker
                GROUP BY satker WITH ROLLUP
            ) AS hasil
            ORDER BY CASE WHEN satker = 'TOTAL SEMUA SATKER' THEN 1 ELSE 0 END, satker
        ";

        $hasilQuery = DB::connection('elaporan')->select($sql);

        $dataReguler = [];
        foreach ($hasilQuery as $row) {
            if ($row->satker === 'TOTAL SEMUA SATKER') {
                $this->barisTotal = (array) $row;
            } else {
                $dataReguler[] = $row;
            }
        }
        return $dataReguler;
    }

    public function getSummary($data)
    {
        if ($this->barisTotal) return $this->barisTotal;
        $summary = ['SISA' => 0, 'DITERIMA' => 0, 'BEBAN' => 0, 'SELESAI' => 0, 'SISA_TAHUN_INI' => 0];
        foreach ($data as $row) {
            $summary['SISA'] += $row->SISA;
            $summary['DITERIMA'] += $row->DITERIMA;
            $summary['BEBAN'] += $row->BEBAN;
            $summary['SELESAI'] += $row->SELESAI;
            $summary['SISA_TAHUN_INI'] += $row->SISA_TAHUN_INI;
        }
        return $summary;
    }

    public function getAllTimeSummary()
    {
        $unions = [];
        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            $unions[] = "SELECT pe.permohonan_eksekusi, lipa5.tanggal_selesai FROM {$db}.perkara_eksekusi pe LEFT JOIN elaporan.lipa5 ON pe.nomor_register_eksekusi = lipa5.nomor_eksekusi";
            $unions[] = "SELECT peh.permohonan_eksekusi, lipa5.tanggal_selesai FROM {$db}.perkara_eksekusi_ht peh LEFT JOIN elaporan.lipa5 ON peh.eksekusi_nomor_perkara = lipa5.nomor_eksekusi";
        }
        $gabunganSatkerSql = implode("\n UNION ALL \n", $unions);
        $sql = "SELECT COUNT(*) AS total_diterima, SUM(CASE WHEN tanggal_selesai IS NOT NULL AND tanggal_selesai != '0000-00-00' THEN 1 ELSE 0 END) AS total_selesai, SUM(CASE WHEN tanggal_selesai IS NULL OR tanggal_selesai = '0000-00-00' THEN 1 ELSE 0 END) AS total_sisa FROM ($gabunganSatkerSql) AS all_data WHERE permohonan_eksekusi IS NOT NULL";
        $res = DB::connection('elaporan')->selectOne($sql);
        $d = $res->total_diterima ?? 0;
        $s = $res->total_selesai ?? 0;
        return ['diterima' => $d, 'selesai' => $s, 'sisa' => ($res->total_sisa ?? 0), 'persentase' => $d > 0 ? round(($s / $d) * 100, 2) : 0];
    }

    public function getDetailPerkara($satker, $jenis, $tglAwal, $tglAkhir)
    {
        $targetSatker = ($satker === 'ALL') ? $this->daftarSatker : [$satker];
        $unions = [];

        foreach ($targetSatker as $s) {
            $db = strtolower($s);
            $kondisi = "";

            if ($satker === 'ALL') {
                if ($jenis === 'TOTAL_DITERIMA') $kondisi = "YEAR(pe.permohonan_eksekusi) > 0";
                elseif ($jenis === 'TOTAL_SELESAI') $kondisi = "lipa5.tanggal_selesai IS NOT NULL AND lipa5.tanggal_selesai != '0000-00-00'";
                elseif ($jenis === 'TOTAL_SISA') $kondisi = "lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00'";
            } else {
                if ($jenis === 'SISA') $kondisi = "permohonan_eksekusi < '$tglAwal' AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai >= '$tglAwal')";
                elseif ($jenis === 'DITERIMA') $kondisi = "permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir'";
                elseif ($jenis === 'BEBAN') $kondisi = "((permohonan_eksekusi < '$tglAwal' AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai >= '$tglAwal')) OR (permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir'))";
                elseif ($jenis === 'SELESAI') $kondisi = "lipa5.tanggal_selesai >= '$tglAwal' AND lipa5.tanggal_selesai <= '$tglAkhir' AND lipa5.tanggal_selesai != '0000-00-00'";
                elseif ($jenis === 'SISA_TAHUN_INI') $kondisi = "((permohonan_eksekusi < '$tglAwal' AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai >= '$tglAwal')) OR (permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir')) AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai > '$tglAkhir')";
            }

            if ($kondisi) {
                $unions[] = "SELECT '{$s}' as satker_nama, pe.nomor_register_eksekusi AS nomor_eksekusi, pe.nomor_perkara_pn AS nomor_perkara_asal, pe.permohonan_eksekusi AS tanggal_permohonan, lipa5.tanggal_selesai, 'Putusan' AS jenis_eksekusi FROM {$db}.perkara_eksekusi pe LEFT JOIN elaporan.lipa5 ON pe.nomor_register_eksekusi = lipa5.nomor_eksekusi WHERE " . $kondisi;
                $unions[] = "SELECT '{$s}' as satker_nama, peh.eksekusi_nomor_perkara AS nomor_eksekusi, peh.nomor_perkara_pn AS nomor_perkara_asal, peh.permohonan_eksekusi AS tanggal_permohonan, lipa5.tanggal_selesai, 'Hak Tanggungan' AS jenis_eksekusi FROM {$db}.perkara_eksekusi_ht peh LEFT JOIN elaporan.lipa5 ON peh.eksekusi_nomor_perkara = lipa5.nomor_eksekusi WHERE " . str_replace('pe.', 'peh.', $kondisi);
            }
        }

        if (empty($unions)) return [];
        $sql = implode("\n UNION ALL \n", $unions) . " ORDER BY tanggal_permohonan ASC";
        return DB::connection('elaporan')->select($sql);
    }
}
