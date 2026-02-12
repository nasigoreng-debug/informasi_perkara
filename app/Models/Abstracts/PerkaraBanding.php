<?php

namespace App\Models\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class PerkaraBanding extends Model
{
    protected $table = 'perkara_banding';

    protected $primaryKey = 'perkara_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    protected $connection = 'mysql';

    public function perkaraKasasi()
    {
        return $this->hasOne(PerkaraKasasi::class, 'perkara_id', 'perkara_id');
    }
}
