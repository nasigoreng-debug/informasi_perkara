<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perkara extends Model
{
    use HasFactory;

    protected $table = 'perkara';
    protected $primaryKey = 'perkara_id';

    // Field yang bisa diisi
    protected $fillable = [
        'nomor_perkara_banding',
        'jenis_perkara',
        'tgl_register',
        'tgl_sidang_pertama',
        'tgl_penetapan_majelis',
        'tgl_putusan',
        'tgl_upload',
        'tgl_arsip',
        'km_id',
        'nama_km',
        'pp_id',
        'nama_pp',
        'jenis_putus_id',
        'jenis_putus_text',
        'nama_satker',
        'tabayun',
    ];

    // Casting tipe data
    protected $casts = [
        'tgl_register' => 'date',
        'tgl_sidang_pertama' => 'date',
        'tgl_penetapan_majelis' => 'date',
        'tgl_putusan' => 'date',
        'tgl_upload' => 'datetime',
        'tgl_arsip' => 'date',
        'tabayun' => 'boolean',
    ];

    /**
     * Scope untuk filter (akan sering digunakan)
     */
    public function scopeFilter($query, $filters)
    {
        // Filter tahun
        if (isset($filters['tahun'])) {
            $query->whereYear('tgl_register', $filters['tahun']);
        }

        // Filter jenis perkara
        if (isset($filters['jenis_perkara'])) {
            $query->where('jenis_perkara', $filters['jenis_perkara']);
        }

        // Filter majelis hakim
        if (isset($filters['km_id'])) {
            $query->where('km_id', $filters['km_id']);
        }

        // Filter panitera
        if (isset($filters['pp_id'])) {
            $query->where('pp_id', $filters['pp_id']);
        }

        // Filter satker
        if (isset($filters['nama_satker'])) {
            $query->where('nama_satker', $filters['nama_satker']);
        }

        return $query;
    }

    /**
     * Scope untuk perkara belum diputus
     */
    public function scopeBelumDiputus($query)
    {
        return $query->whereNull('tgl_putusan');
    }

    /**
     * Scope untuk perkara sudah diputus
     */
    public function scopeSudahDiputus($query)
    {
        return $query->whereNotNull('tgl_putusan');
    }
}
