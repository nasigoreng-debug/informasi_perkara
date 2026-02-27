<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    // Nama tabel di database
    protected $table = 'activity_logs';

    // Kolom yang boleh diisi otomatis
    protected $fillable = [
        'user_id',
        'activity',
        'model',
        'description',
        'ip_address',
        'user_agent'
    ];

    /**
     * Relasi: Log ini milik siapa?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mesin Pencatat Otomatis
     */
    public static function record($activity, $model = null, $description = null)
    {
        return self::create([
            'user_id'     => auth()->id(),
            'activity'    => $activity,
            'model'       => $model,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
