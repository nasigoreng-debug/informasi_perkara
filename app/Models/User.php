<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan role_id ke dalam kolom yang boleh diisi
    protected $fillable = ['name', 'username', 'password', 'satker_id', 'role_id'];

    /**
     * Relasi ke Satker
     */
    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class, 'satker_id', 'id');
    }

    /**
     * Relasi ke Role (PENTING: Menggunakan role_id)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Helper untuk cek Admin
    public function isAdmin()
    {
        return $this->role_id == 1;
    }

    // Helper untuk cek Manager ke atas
    public function canSeeAllData()
    {
        return in_array($this->role_id, [1, 2]);
    }
}
