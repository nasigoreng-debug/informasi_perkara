<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeputusan extends Model
{
    protected $table = 'tb_sk'; 
    protected $fillable = [
        'no_sk',
        'tahun',
        'tgl_sk',
        'tentang',
        'dokumen',
        'konsep_sk'
    ];
}
