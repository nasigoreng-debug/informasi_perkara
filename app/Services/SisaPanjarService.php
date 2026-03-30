<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SisaPanjarService
{
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

    public function getSisaPanjarData($jenis)
    {
        $hasilAkhir = [];
        $config = [
            'pertama' => ['tahapan' => 10, 'tabel' => 'perkara_putusan', 'nomor_atas' => 'nomor_perkara'],
            'banding' => ['tahapan' => 20, 'notif_col' => 'pemberitahuan_putusan_banding', 'tabel' => 'perkara_banding', 'nomor_atas' => 'nomor_perkara_banding'],
            'kasasi'  => ['tahapan' => 30, 'notif_col' => 'pemberitahuan_putusan_kasasi', 'tabel' => 'perkara_kasasi', 'nomor_atas' => 'nomor_perkara_kasasi'],
            'pk'      => ['tahapan' => 40, 'notif_col' => 'pemberitahuan_putusan_pk', 'tabel' => 'perkara_pk', 'nomor_atas' => 'nomor_perkara_pk']
        ];

        if (!isset($config[$jenis])) return collect([]);
        $cfg = $config[$jenis];

        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);

            // Kolom p.proses_terakhir_text disesuaikan dengan struktur tabel Abang
            $sql = "SELECT 
                        '{$satker}' AS satker_key, 
                        p.nomor_perkara, 
                        p.jenis_perkara_nama, 
                        p.proses_terakhir_text, 
                        " . ($jenis == 'pertama' ? "p.nomor_perkara" : "pb.{$cfg['nomor_atas']}") . " AS nomor_perkara_atas, 
                        " . ($jenis == 'pertama' ? "pp.tanggal_putusan" : "pb.putusan_{$jenis}") . " AS tgl_putusan, 
                        " . ($jenis == 'pertama' ? "pbt.tgl_akhir" : "pb.{$cfg['notif_col']}") . " AS tgl_notif, 
                        ROUND(DATEDIFF(CURDATE(), " . ($jenis == 'pertama' ? "pbt.tgl_akhir" : "pb.{$cfg['notif_col']}") . ") / 30, 1) AS selisih_bulan, 
                        biaya.total_sisa AS sisa 
                    FROM {$db}.perkara p 
                    JOIN {$db}." . ($jenis == 'pertama' ? "perkara_putusan pp ON p.perkara_id = pp.perkara_id" : "{$cfg['tabel']} pb ON p.perkara_id = pb.perkara_id") . "
                    " . ($jenis == 'pertama' ? "JOIN (SELECT perkara_id, MAX(tanggal_pemberitahuan_putusan) as tgl_akhir FROM {$db}.perkara_putusan_pemberitahuan_putusan GROUP BY perkara_id) pbt ON p.perkara_id = pbt.perkara_id" : "") . "
                    JOIN (
                        SELECT perkara_id, SUM(jumlah * jenis_transaksi) as total_sisa 
                        FROM {$db}.perkara_biaya 
                        WHERE tahapan_id = {$cfg['tahapan']} 
                        GROUP BY perkara_id
                    ) biaya ON p.perkara_id = biaya.perkara_id
                    
                    WHERE biaya.total_sisa > 0
                    AND " . ($jenis == 'pertama' ? "pbt.tgl_akhir" : "pb.{$cfg['notif_col']}") . " <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";

            if ($jenis == 'pertama') {
                $sql .= " AND (
                            (p.jenis_perkara_nama LIKE '%Cerai%' AND p.proses_terakhir_text LIKE '%Akta Cerai%')
                            OR 
                            (p.jenis_perkara_nama NOT LIKE '%Cerai%' AND p.proses_terakhir_text LIKE '%Minutasi%')
                        )";
                $sql .= " AND p.perkara_id NOT IN (SELECT perkara_id FROM {$db}.perkara_banding)";
            }

            try {
                $res = DB::connection('bandung')->select($sql);
                if (count($res) > 0) echo "   [OK] {$satker}: " . count($res) . " data.\n";
                $hasilAkhir = array_merge($hasilAkhir, $res);
            } catch (\Exception $e) {
                echo "   [!] {$satker} ERROR: Nama kolom mungkin salah atau DB tidak ada.\n";
                continue;
            }
        }

        return collect($hasilAkhir);
    }
}
