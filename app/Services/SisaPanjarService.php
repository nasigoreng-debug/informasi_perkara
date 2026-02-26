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
            'banding' => ['tabel' => 'perkara_banding', 'nomor' => 'nomor_perkara_banding', 'putusan' => 'putusan_banding', 'notif' => 'pemberitahuan_putusan_banding', 'tahapan' => 20],
            'kasasi'  => ['tabel' => 'perkara_kasasi', 'nomor' => 'nomor_perkara_kasasi', 'putusan' => 'putusan_kasasi', 'notif' => 'pemberitahuan_putusan_kasasi', 'tahapan' => 30],
            'pk'      => ['tabel' => 'perkara_pk', 'nomor' => 'nomor_perkara_pk', 'putusan' => 'putusan_pk', 'notif' => 'pemberitahuan_putusan_pk', 'tahapan' => 40]
        ][$jenis];

        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);
            // Tambahkan pb.{$config['putusan']} AS tgl_putusan di bawah ini:
            $unions[] = "SELECT 
            '{$satker}' AS satker_key, 
            p.nomor_perkara, 
            pb.{$config['nomor']} AS nomor_perkara_atas, 
            pb.{$config['putusan']} AS tgl_putusan, 
            pb.{$config['notif']} AS tgl_notif, 
            ROUND(DATEDIFF(CURDATE(), pb.{$config['notif']}) / 30, 1) AS selisih_bulan, 
            COALESCE((SELECT SUM(jumlah * jenis_transaksi) FROM {$db}.perkara_biaya WHERE perkara_id = p.perkara_id AND tahapan_id = {$config['tahapan']}), 0) AS sisa 
            FROM {$db}.perkara p 
            JOIN {$db}.{$config['tabel']} pb ON p.perkara_id = pb.perkara_id 
            WHERE pb.{$config['notif']} <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
            AND pb.{$config['notif']} IS NOT NULL 
            HAVING sisa <> 0";
        }

        $sql = "SELECT * FROM (" . implode(" UNION ALL ", $unions) . ") as hasil ORDER BY satker_key ASC, selisih_bulan DESC";
        return collect(DB::connection('bandung')->select($sql));
    }
}
