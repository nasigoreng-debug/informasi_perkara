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
            // Gunakan IFNULL pada kolom tanggal agar query tidak pecah jika data corrupt
            $unions[] = "SELECT '{$satker}' AS satker, pe.permohonan_eksekusi, IFNULL(lipa5.tanggal_selesai, '0000-00-00') as tanggal_selesai FROM {$db}.perkara_eksekusi pe LEFT JOIN elaporan.lipa5 ON pe.nomor_register_eksekusi = lipa5.nomor_eksekusi";
            $unions[] = "SELECT '{$satker}' AS satker, peh.permohonan_eksekusi, IFNULL(lipa5.tanggal_selesai, '0000-00-00') as tanggal_selesai FROM {$db}.perkara_eksekusi_ht peh LEFT JOIN elaporan.lipa5 ON peh.eksekusi_nomor_perkara = lipa5.nomor_eksekusi";
        }

        $gabunganSatkerSql = implode("\n UNION ALL \n", $unions);

        $sql = "
            SELECT 
                IFNULL(satker, 'TOTAL SEMUA SATKER') AS satker, 
                CAST(IFNULL(SISA, 0) AS UNSIGNED) as SISA, 
                CAST(IFNULL(DITERIMA, 0) AS UNSIGNED) as DITERIMA, 
                CAST(IFNULL(BEBAN, 0) AS UNSIGNED) as BEBAN, 
                CAST(IFNULL(SELESAI, 0) AS UNSIGNED) as SELESAI, 
                CAST(IFNULL(SISA_TAHUN_INI, 0) AS SIGNED) as SISA_TAHUN_INI
            FROM (
                SELECT satker,
                    SUM(CASE WHEN permohonan_eksekusi < '$tglAwal' AND (tanggal_selesai IS NULL OR tanggal_selesai = '0000-00-00' OR tanggal_selesai >= '$tglAwal') THEN 1 ELSE 0 END) AS SISA,
                    SUM(CASE WHEN permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir' THEN 1 ELSE 0 END) AS DITERIMA,
                    (SUM(CASE WHEN permohonan_eksekusi < '$tglAwal' AND (tanggal_selesai IS NULL OR tanggal_selesai = '0000-00-00' OR tanggal_selesai >= '$tglAwal') THEN 1 ELSE 0 END) + 
                     SUM(CASE WHEN permohonan_eksekusi >= '$tglAwal' AND permohonan_eksekusi <= '$tglAkhir' THEN 1 ELSE 0 END)) AS BEBAN,
                    SUM(CASE WHEN tanggal_selesai >= '$tglAwal' AND tanggal_selesai <= '$tglAkhir' AND tanggal_selesai != '0000-00-00' THEN 1 ELSE 0 END) AS SELESAI,
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
        // Pastikan $this->barisTotal tidak null
        if ($this->barisTotal) return $this->barisTotal;

        $summary = ['SISA' => 0, 'DITERIMA' => 0, 'BEBAN' => 0, 'SELESAI' => 0, 'SISA_TAHUN_INI' => 0];
        foreach ($data as $row) {
            $summary['SISA'] += $row->SISA ?? 0;
            $summary['DITERIMA'] += $row->DITERIMA ?? 0;
            $summary['BEBAN'] += $row->BEBAN ?? 0;
            $summary['SELESAI'] += $row->SELESAI ?? 0;
            $summary['SISA_TAHUN_INI'] += $row->SISA_TAHUN_INI ?? 0;
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
        $si = $res->total_sisa ?? 0;

        return [
            'diterima' => $d,
            'selesai' => $s,
            'sisa' => $si,
            'persentase' => $d > 0 ? round(($s / $d) * 100, 2) : 0
        ];
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
                if ($jenis === 'SISA')
                    $kondisi = "pe.permohonan_eksekusi < '$tglAwal' AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai >= '$tglAwal')";
                elseif ($jenis === 'DITERIMA')
                    $kondisi = "pe.permohonan_eksekusi >= '$tglAwal' AND pe.permohonan_eksekusi <= '$tglAkhir'";
                elseif ($jenis === 'BEBAN')
                    $kondisi = "((pe.permohonan_eksekusi < '$tglAwal' AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai >= '$tglAwal')) OR (pe.permohonan_eksekusi >= '$tglAwal' AND pe.permohonan_eksekusi <= '$tglAkhir'))";
                elseif ($jenis === 'SELESAI')
                    $kondisi = "lipa5.tanggal_selesai >= '$tglAwal' AND lipa5.tanggal_selesai <= '$tglAkhir' AND lipa5.tanggal_selesai != '0000-00-00'";
                elseif ($jenis === 'SISA_TAHUN_INI')
                    $kondisi = "((pe.permohonan_eksekusi < '$tglAwal' AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai >= '$tglAwal')) OR (pe.permohonan_eksekusi >= '$tglAwal' AND pe.permohonan_eksekusi <= '$tglAkhir')) AND (lipa5.tanggal_selesai IS NULL OR lipa5.tanggal_selesai = '0000-00-00' OR lipa5.tanggal_selesai > '$tglAkhir')";
            }

            if ($kondisi) {
                $unions[] = "
                    SELECT '{$s}' as satker_nama, pe.nomor_register_eksekusi AS nomor_eksekusi, pe.nomor_perkara_pn AS nomor_perkara_asal, pe.permohonan_eksekusi AS tanggal_permohonan, lipa5.tanggal_selesai, lipa5.keterangan, 
                    COALESCE((SELECT SUM(pb.jenis_transaksi * pb.jumlah) FROM {$db}.perkara_biaya pb WHERE pb.perkara_id = pe.perkara_id AND pb.tahapan_id = 50), 0) AS sisa_biaya, 
                    'Putusan' AS jenis_eksekusi 
                    FROM {$db}.perkara_eksekusi pe 
                    LEFT JOIN elaporan.lipa5 ON pe.nomor_register_eksekusi = lipa5.nomor_eksekusi 
                    WHERE " . $kondisi;

                $kondisiHt = str_replace('pe.', 'peh.', $kondisi);
                $unions[] = "
                    SELECT '{$s}' as satker_nama, peh.eksekusi_nomor_perkara AS nomor_eksekusi, peh.nomor_perkara_pn AS nomor_perkara_asal, peh.permohonan_eksekusi AS tanggal_permohonan, lipa5.tanggal_selesai, lipa5.keterangan, 
                    COALESCE((SELECT SUM(CASE WHEN pbh.jenis_transaksi = 1 THEN pbh.jumlah ELSE -pbh.jumlah END) FROM {$db}.perkara_biaya_ht pbh WHERE pbh.ht_id = peh.ht_id), 0) AS sisa_biaya, 
                    'Hak Tanggungan' AS jenis_eksekusi 
                    FROM {$db}.perkara_eksekusi_ht peh 
                    LEFT JOIN elaporan.lipa5 ON peh.eksekusi_nomor_perkara = lipa5.nomor_eksekusi 
                    WHERE " . $kondisiHt;
            }
        }

        if (empty($unions)) return [];
        $sql = implode("\n UNION ALL \n", $unions) . " ORDER BY tanggal_permohonan ASC";
        return DB::connection('elaporan')->select($sql);
    }
}
