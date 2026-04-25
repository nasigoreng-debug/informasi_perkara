<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'tb_pengaduan';
    protected $guarded = ['id'];
    protected $fillable = [
        'tgl_terima_pgd',
        'no_pgd',
        'no_surat_pgd',
        'pelapor',
        'terlapor',
        'uraian_pgd',
        'ditangani_oleh',
        'dis_pm_hk',
        'dis_kpta',
        'dis_wkpta',
        'dis_hatiwasda',
        'tgl_tindak_lanjut',
        'status_pgd',
        'status_berkas',
        'tgl_selesai_pgd',
        'tgl_lhp',
        'surat_pgd',
        'lampiran'
    ];
}
