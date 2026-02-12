<?php

namespace App\Models\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class PerkaraKasasi extends Model
{
    protected $table = 'perkara_kasasi';

    protected $primaryKey = 'perkara_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];

    protected $connection = 'mysql';

    public function perkaraBanding()
    {
        return $this->belongsTo(PerkaraBanding::class, 'perkara_id', 'perkara_id');
    }
}
