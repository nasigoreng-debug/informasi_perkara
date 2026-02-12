<?php

namespace App\Models\Satker;

use App\Models\Abstracts\PerkaraKasasi;
use App\Traits\MultiDatabaseTrait;

class PerkaraKasasiCianjur extends PerkaraKasasi
{
    use MultiDatabaseTrait;
    
    protected $connection = 'cianjur';
    
    protected $table = 'perkara_kasasi';
    
    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_pendaftaran_kasasi', $tahun);
    }
    
    /**
     * Get tanggal format Indonesia
     */
    public function getTanggalIndonesiaAttribute()
    {
        if (!$this->tanggal_pendaftaran_kasasi) {
            return '-';
        }
        
        return \Carbon\Carbon::parse($this->tanggal_pendaftaran_kasasi)
            ->locale('id')
            ->isoFormat('D MMMM Y');
    }
}