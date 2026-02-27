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

    public function getSisaPanjarData($jenis = 'banding')
    {
        $unions = [];
        $config = [
            'pertama' => ['tahapan' => 10],
            'banding' => ['tahapan' => 20, 'notif_col' => 'pemberitahuan_putusan_banding', 'tabel' => 'perkara_banding', 'nomor_atas' => 'nomor_perkara_banding'],
            'kasasi'  => ['tahapan' => 30, 'notif_col' => 'pemberitahuan_putusan_kasasi', 'tabel' => 'perkara_kasasi', 'nomor_atas' => 'nomor_perkara_kasasi'],
            'pk'      => ['tahapan' => 40, 'notif_col' => 'pemberitahuan_putusan_pk', 'tabel' => 'perkara_pk', 'nomor_atas' => 'nomor_perkara_pk']
        ];

        $cfg = $config[$jenis];

        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);

            if ($jenis == 'pertama') {
                // Query Tingkat Pertama: Ambil nomor asli & hitung dari tanggal pemberitahuan putusan 
                $sqlPart = "SELECT 
                    '{$satker}' AS satker_key, 
                    p.nomor_perkara, 
                    p.nomor_perkara AS nomor_perkara_atas, 
                    pp.tanggal_putusan AS tgl_putusan, 
                    pbt.tgl_akhir AS tgl_notif, 
                    ROUND(DATEDIFF(CURDATE(), pbt.tgl_akhir) / 30, 1) AS selisih_bulan, 
                    (SELECT SUM(jumlah * jenis_transaksi) FROM {$db}.perkara_biaya WHERE perkara_id = p.perkara_id AND tahapan_id = 10) AS sisa 
                    FROM {$db}.perkara p 
                    JOIN {$db}.perkara_putusan pp ON p.perkara_id = pp.perkara_id
                    JOIN (
                        SELECT perkara_id, MAX(tanggal_pemberitahuan_putusan) as tgl_akhir 
                        FROM {$db}.perkara_putusan_pemberitahuan_putusan 
                        GROUP BY perkara_id
                    ) pbt ON p.perkara_id = pbt.perkara_id
                    WHERE pbt.tgl_akhir <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                    AND pbt.tgl_akhir IS NOT NULL 
                    HAVING sisa <> 0";
            } else {
                // Query Upaya Hukum: Ambil nomor pendaftaran spesifik (Banding/Kasasi/PK) 
                $sqlPart = "SELECT 
                    '{$satker}' AS satker_key, 
                    p.nomor_perkara, 
                    pb.{$cfg['nomor_atas']} AS nomor_perkara_atas, 
                    pb.putusan_{$jenis} AS tgl_putusan, 
                    pb.{$cfg['notif_col']} AS tgl_notif, 
                    ROUND(DATEDIFF(CURDATE(), pb.{$cfg['notif_col']}) / 30, 1) AS selisih_bulan, 
                    (SELECT SUM(jumlah * jenis_transaksi) FROM {$db}.perkara_biaya WHERE perkara_id = p.perkara_id AND tahapan_id = {$cfg['tahapan']}) AS sisa 
                    FROM {$db}.perkara p 
                    JOIN {$db}.{$cfg['tabel']} pb ON p.perkara_id = pb.perkara_id 
                    WHERE pb.{$cfg['notif_col']} <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                    AND pb.{$cfg['notif_col']} IS NOT NULL 
                    HAVING sisa <> 0";
            }
            $unions[] = $sqlPart;
        }

        $sql = "SELECT * FROM (" . implode(" UNION ALL ", $unions) . ") as hasil ORDER BY satker_key ASC, selisih_bulan DESC";
        return collect(DB::connection('bandung')->select($sql));
    }
}
