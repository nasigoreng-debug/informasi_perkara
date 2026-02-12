<?php

namespace App\Models\Satker;

use App\Models\Abstracts\PerkaraBanding;
use App\Traits\MultiDatabaseTrait;

class PerkaraBandingSubang extends PerkaraBanding
{
    use MultiDatabaseTrait;
    
    protected $connection = 'subang';
    
    protected $table = 'perkara_banding';
    
    /**
     * Get nama pengadilan agama
     */
    public static function getNamaSatker(): string
    {
        return 'SUBANG';
    }
    
    /**
     * Get nomor urut
     */
    public static function getNomorUrut(): int
    {
        return 9;
    }
    
    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('permohonan_banding', $tahun);
    }
    
    /**
     * Scope untuk perkara yang sudah kasasi
     */
    public function scopeSudahKasasi($query)
    {
        return $query->whereHas('perkaraKasasi', function($q) {
            $q->whereNotNull('nomor_perkara_kasasi');
        });
    }
}