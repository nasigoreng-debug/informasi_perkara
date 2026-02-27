<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CourtCalendarService
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

    public function getMonitoringData($tglAwal, $tglAkhir)
    {
        $unions = [];

        foreach ($this->daftarSatker as $satker) {
            $db = strtolower($satker);

            // Query sesuai arahan Bapak (menggunakan tanggal_putusan dan rencana_tanggal)
            $sqlPart = "SELECT 
                '{$satker}' AS satker, 
                COUNT(p.perkara_id) AS jumlah
            FROM {$db}.perkara p
            JOIN {$db}.perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN {$db}.perkara_court_calendar pcc ON p.perkara_id = pcc.perkara_id
            WHERE pcc.rencana_tanggal IS NULL
            AND (p.proses_terakhir_text LIKE '%minutasi%' OR p.proses_terakhir_text LIKE '%Akta Cerai%')
            AND pp.tanggal_putusan BETWEEN '{$tglAwal}' AND '{$tglAkhir}'";

            $unions[] = $sqlPart;
        }

        // Gabungkan semua query
        $sql = "SELECT * FROM (" . implode(" UNION ALL ", $unions) . ") as hasil ORDER BY jumlah DESC";

        // KUNCI SUKSES: Gunakan koneksi 'bandung' persis seperti di SisaPanjarService
        return collect(DB::connection('bandung')->select($sql))->all();
    }
}
