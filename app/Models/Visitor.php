<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * Properti fillable memungkinkan kolom-kolom ini diisi 
     * menggunakan metode create() atau firstOrCreate().
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'visit_date'
    ];
}
