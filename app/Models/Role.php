<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level', 'description'];

    /**
     * Relasi ke User
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    // Helper methods untuk cek role
    public function isSuperAdmin()
    {
        return $this->name === 'super_admin' || $this->level === 1;
    }

    public function isAdmin()
    {
        return $this->name === 'admin' || $this->level === 2;
    }

    public function isUser()
    {
        return $this->name === 'user' || $this->level === 3;
    }

    public function isViewer()
    {
        return $this->name === 'viewer' || $this->level === 4;
    }
}
