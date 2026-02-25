<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    // Nama koneksi harus sama dengan kunci di config/database.php
    protected $connection = 'db_pm_hukum';
    protected $table = 'tb_surat_masuk';

    public $timestamps = false;

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
        'lampiran'
    ];
}
