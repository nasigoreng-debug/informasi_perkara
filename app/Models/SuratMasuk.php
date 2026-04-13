<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $connection = 'db_pm_hukum';
    protected $table = 'tb_surat_masuk';
    public $timestamps = true;

    protected $fillable = [
        'no_indeks',
        'no_surat',
        'tgl_surat',
        'asal_surat',
        'perihal',
        'disposisi',
        'keterangan',
        'tgl_masuk_pan',
        'tgl_masuk_umum',
        'lampiran',
        'created_by',
        'updated_by'
    ];

    // Relasi ke User (pembuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke User (terakhir diupdate)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
