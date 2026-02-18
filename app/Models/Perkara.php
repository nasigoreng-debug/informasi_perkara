<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perkara extends Model
{
    protected $table = 'perkara';
    protected $primaryKey = 'perkara_id';
    // JANGAN ADA protected $connection di sini
}
