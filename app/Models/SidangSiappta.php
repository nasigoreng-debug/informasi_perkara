<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SidangSiappta extends Model
{
    use HasFactory;

    // PENTING: Kunci sambungan ke pangkalan data SIAPPTA (Server 121)
    protected $connection = 'siappta';

    // Nama jadual dalam server 121
    protected $table = 'perkara';

    protected $primaryKey = 'perkara_id';

    // Matikan timestamps jika jadual SIPP tidak mempunyai created_at/updated_at
    public $timestamps = false;

    protected $casts = [
        'tgl_sidang_pertama' => 'date',
        'tgl_putusan' => 'date',
    ];
}
