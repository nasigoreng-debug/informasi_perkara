<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    use HasFactory;
    protected $table = 'tb_jdih';
    protected $fillable = [
        'jenis_peraturan',
        'no_peraturan',
        'tahun',
        'tgl_peraturan',
        'tentang',
        'dokumen'
    ];
}
