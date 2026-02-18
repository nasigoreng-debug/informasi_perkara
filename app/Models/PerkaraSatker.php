<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraSatker extends Model
{
    protected $guarded = [];

    /**
     * Fungsi untuk pindah koneksi secara instan
     */
    public static function konekKe($koneksi, $tabel = 'perkara')
    {
        $instance = new static;
        $instance->setConnection($koneksi); // Pindah pintu pangkalan data
        $instance->setTable($tabel);        // Pindah tabel
        return $instance->newQuery();
    }
}