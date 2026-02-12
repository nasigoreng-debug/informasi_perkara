<?php

namespace App\Config;

class SatkerConfig
{
    /**
     * Daftar 26 satker dengan urutan sesuai permintaan
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
        'tasikkota' => 'KOTA TASIKMALAYA',
        'banjar' => 'KOTA BANJAR',
        'soreang' => 'SOREANG',
        'ngamprah' => 'NGAMPRAH'
    ];

    /**
     * Get nomor urut berdasarkan database
     */
    public static function getNomorUrut($database): int
    {
        $nomor = [
            'bandung' => 1,
            'indramayu' => 2,
            'majalengka' => 3,
            'sumber' => 4,
            'ciamis' => 5,
            'tasikmalaya' => 6,
            'karawang' => 7,
            'cimahi' => 8,
            'subang' => 9,
            'sumedang' => 10,
            'purwakarta' => 11,
            'sukabumi' => 12,
            'cianjur' => 13,
            'kuningan' => 14,
            'cibadak' => 15,
            'cirebon' => 16,
            'garut' => 17,
            'bogor' => 18,
            'bekasi' => 19,
            'cibinong' => 20,
            'cikarang' => 21,
            'depok' => 22,
            'tasikkota' => 23,
            'banjar' => 24,
            'soreang' => 25,
            'ngamprah' => 26
        ];

        return $nomor[$database] ?? 0;
    }

    /**
     * Get daftar koneksi database
     */
    public static function getConnections(): array
    {
        return array_keys(self::SATKERS);
    }

    /**
     * Get nama satker berdasarkan database
     */
    public static function getNamaSatker($database): string
    {
        return self::SATKERS[$database] ?? strtoupper($database);
    }

    /**
     * Get total satker
     */
    public static function getTotalSatker(): int
    {
        return count(self::SATKERS);
    }
}
