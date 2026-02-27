<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satker extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'satker';

    /**
     * Karena ID Satker Anda bukan auto-increment (menggunakan kode 400xxx),
     * kita harus mematikan fitur incrementing agar ID tidak dianggap sebagai 1, 2, 3.
     */
    public $incrementing = false;
    protected $keyType = 'int';

    // Daftar kolom yang boleh diisi (Mass Assignment) 
    protected $fillable = [
        'id',
        'nama',
        'nama_singkat',
        'tabel',
        'kode',
        'logo_pa',
        'urutan',
        'namapa'
    ];

    /**
     * Relasi ke Tabel Users: Satu Satker bisa memiliki banyak User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'satker_id', 'id');
    }
}
