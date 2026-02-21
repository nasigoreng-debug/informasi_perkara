<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapEksekusi extends Model
{
    protected $table = null; // Tidak menggunakan table specific

    /**
     * Daftar satker dan nama database
     */
    public const SATKER_DATABASE = [
        'BANDUNG' => 'bandung',
        'INDRAMAYU' => 'indramayu',
        'MAJALENGKA' => 'majalengka',
        'SUMBER' => 'sumber',
        'CIAMIS' => 'ciamis',
        'TASIKMALAYA' => 'tasikmalaya',
        'KARAWANG' => 'karawang',
        'CIMAHI' => 'cimahi',
        'SUBANG' => 'subang',
        'SUMEDANG' => 'sumedang',
        'PURWAKARTA' => 'purwakarta',
        'SUKABUMI' => 'sukabumi',
        'CIANJUR' => 'cianjur',
        'KUNINGAN' => 'kuningan',
        'CIBADAK' => 'cibadak',
        'CIREBON' => 'cirebon',
        'GARUT' => 'garut',
        'BOGOR' => 'bogor',
        'BEKASI' => 'bekasi',
        'CIBINONG' => 'cibinong',
        'CIKARANG' => 'cikarang',
        'DEPOK' => 'depok',
        'TASIKKOTA' => 'tasikkota',
        'BANJAR' => 'banjar',
        'SOREANG' => 'soreang',
        'NGAMPRAH' => 'ngamprah',
    ];

    /**
     * Ambil rekap data berdasarkan tahun
     */
    public static function getRekapByTahun(int $tahun): array
    {
        $unionQueries = [];
        $bindings = [];

        foreach (self::SATKER_DATABASE as $satker => $database) {
            // Query untuk perkara_eksekusi
            $unionQueries[] = "SELECT ? AS satker, pe.permohonan_eksekusi AS permohonan_eksekusi, lipa5.tanggal_selesai AS tanggal_selesai
                FROM {$database}.perkara_eksekusi pe 
                LEFT JOIN elaporan.lipa5 ON pe.nomor_register_eksekusi = lipa5.nomor_eksekusi";

            $bindings[] = $satker;

            // Query untuk perkara_eksekusi_ht
            $unionQueries[] = "SELECT ? AS satker, peh.permohonan_eksekusi AS permohonan_eksekusi, lipa5.tanggal_selesai AS tanggal_selesai
                FROM {$database}.perkara_eksekusi_ht peh 
                LEFT JOIN elaporan.lipa5 ON peh.eksekusi_nomor_perkara = lipa5.nomor_eksekusi";

            $bindings[] = $satker;
        }

        $unionSql = implode(" UNION ALL ", $unionQueries);

        $sql = "
            SELECT 
                IFNULL(satker, 'TOTAL SEMUA SATKER') AS satker,
                SISA,
                DITERIMA,
                BEBAN,
                SELESAI,
                `SISA TAHUN INI`
            FROM (
                SELECT 
                    satker,
                    SUM(CASE 
                        WHEN YEAR(permohonan_eksekusi) < ? 
                             AND (tanggal_selesai IS NULL OR YEAR(tanggal_selesai) = ?)
                        THEN 1 ELSE 0 
                    END) AS SISA,
                    SUM(CASE 
                        WHEN YEAR(permohonan_eksekusi) = ? THEN 1 ELSE 0 
                    END) AS DITERIMA,
                    SUM(CASE 
                        WHEN YEAR(permohonan_eksekusi) < ? 
                             AND (tanggal_selesai IS NULL OR YEAR(tanggal_selesai) = ?)
                        THEN 1 ELSE 0 
                    END) + SUM(CASE 
                        WHEN YEAR(permohonan_eksekusi) = ? THEN 1 ELSE 0 
                    END) AS BEBAN,
                    SUM(CASE 
                        WHEN YEAR(tanggal_selesai) = ? THEN 1 ELSE 0 
                    END) AS SELESAI,
                    (SUM(CASE 
                        WHEN YEAR(permohonan_eksekusi) < ? 
                             AND (tanggal_selesai IS NULL OR YEAR(tanggal_selesai) = ?)
                        THEN 1 ELSE 0 
                    END) + SUM(CASE 
                        WHEN YEAR(permohonan_eksekusi) = ? THEN 1 ELSE 0 
                    END)) - SUM(CASE 
                        WHEN YEAR(tanggal_selesai) = ? THEN 1 ELSE 0 
                    END) AS `SISA TAHUN INI`
                    
                FROM (
                    {$unionSql}
                ) AS data_satker
                GROUP BY satker WITH ROLLUP
            ) AS hasil
            ORDER BY 
                CASE WHEN satker IS NULL THEN 1 ELSE 0 END,
                satker
        ";

        // Tambahkan bindings untuk tahun (12 kali)
        for ($i = 0; $i < 12; $i++) {
            $bindings[] = $tahun;
        }

        return DB::select($sql, $bindings);
    }
}
