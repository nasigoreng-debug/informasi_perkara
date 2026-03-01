<?php

namespace App\Config;

class SatkerConfig
{
    /**
     * Daftar Satker se-Wilayah PTA Bandung
     * Key: Nama Database, Value: Nama Tampilan
     */
    public const SATKERS = [
        'bandung' => 'BANDUNG',
        'indramayu' => 'INDRAMAYU',
        'majalengka' => 'MAJALENGKA',
        'sumber' => 'SUMBER',
        'ciamis' => 'CIAMIS',
        'tasikmalaya' => 'TASIKMALAYA',
        'karawang' => 'KARAWANG',
        'cimahi' => 'CIMAHI',
        'subang' => 'SUBANG',
        'sumedang' => 'SUMEDANG',
        'purwakarta' => 'PURWAKARTA',
        'sukabumi' => 'SUKABUMI',
        'cianjur' => 'CIANJUR',
        'kuningan' => 'KUNINGAN',
        'cibadak' => 'CIBADAK',
        'cirebon' => 'CIREBON',
        'garut' => 'GARUT',
        'bogor' => 'BOGOR',
        'bekasi' => 'BEKASI',
        'cibinong' => 'CIBINONG',
        'cikarang' => 'CIKARANG',
        'depok' => 'DEPOK',
        'tasikkota' => 'TASIKMALAYA KOTA',
        'banjar' => 'BANJAR',
        'soreang' => 'SOREANG',
        'ngamprah' => 'NGAMPRAH'
    ];

    /**
     * Mendapatkan nomor urut satker (untuk keperluan sorting standar)
     */
    public static function getNomorUrut($db)
    {
        $keys = array_keys(self::SATKERS);
        $index = array_search(strtolower($db), $keys);
        return ($index === false) ? 99 : $index + 1;
    }

    /**
     * Mencari nama database asli berdasarkan input nama tampilan
     */
    public static function getDbName($namaTampilan)
    {
        $cleanName = strtoupper(trim(str_replace('%20', ' ', $namaTampilan)));
        $dbName = array_search($cleanName, self::SATKERS);
        return $dbName ?: strtolower(str_replace(' ', '', $namaTampilan));
    }
}
