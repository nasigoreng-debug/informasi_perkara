<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'password', 'satker_id', 'role_id'];

    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class, 'satker_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // ============ 4 LAPIS ROLE CHECK METHODS ============

    public function isSuperAdmin(): bool
    {
        return $this->role_id == 1;
    }

    public function isAdmin(): bool
    {
        return $this->role_id == 2;
    }

    public function isUser(): bool
    {
        return $this->role_id == 3;
    }

    public function isViewer(): bool
    {
        return $this->role_id == 4;
    }

    // ============ PERMISSION METHODS UNTUK MENU ============

    /**
     * Bisa melihat SEMUA menu (Super Admin & Admin)
     */
    public function canSeeAllMenus(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Bisa melihat menu Monitoring Kasasi saja (User)
     * User TIDAK bisa melihat Laporan
     */
    public function canSeeKasasiOnly(): bool
    {
        return $this->isUser();
    }

    /**
     * Bisa melihat semua menu Monitoring (Viewer)
     */
    public function canSeeAllMonitoring(): bool
    {
        return $this->isViewer();
    }

    /**
     * Bisa mengakses menu Administrasi (Super Admin & Admin)
     * User TIDAK bisa
     */
    public function canAccessAdministration(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Bisa mengakses menu Laporan (HANYA Super Admin & Admin)
     * User TIDAK bisa - User hanya bisa monitoring kasasi
     * Viewer TIDAK bisa
     */
    public function canAccessReports(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Bisa mengakses menu Monitoring (Semua role bisa)
     * Tapi User hanya bisa melihat kasasi saja (dibatasi di blade)
     */
    public function canAccessMonitoring(): bool
    {
        return true;
    }

    /**
     * Bisa mengelola user (Hanya Super Admin)
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Bisa mengakses Sync Control (Hanya Super Admin)
     */
    public function canAccessSyncControl(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Bisa melihat semua data (Super Admin & Admin)
     * User TIDAK bisa
     */
    public function canSeeAllData(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Bisa mengedit data (Super Admin & Admin)
     * User TIDAK bisa edit
     * Viewer TIDAK bisa
     */
    public function canEditData(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    // ============ HELPER METHODS ============

    public function getRoleName(): string
    {
        if ($this->role && $this->role->name) {
            return $this->role->name;
        }

        return match ($this->role_id) {
            1 => 'super_admin',
            2 => 'admin',
            3 => 'user',
            4 => 'viewer',
            default => 'viewer'
        };
    }

    public function getRoleLabel(): string
    {
        return match ($this->getRoleName()) {
            'super_admin' => 'SUPER ADMINISTRATOR',
            'admin' => 'ADMINISTRATOR',
            'user' => 'USER',
            'viewer' => 'VIEWER',
            default => 'VIEWER'
        };
    }

    public function getRoleBadgeColor(): string
    {
        return match ($this->getRoleName()) {
            'super_admin' => 'danger',
            'admin' => 'warning',
            'user' => 'info',
            'viewer' => 'secondary',
            default => 'secondary'
        };
    }

    public function getRoleIcon(): string
    {
        return match ($this->getRoleName()) {
            'super_admin' => 'fa-crown',
            'admin' => 'fa-user-shield',
            'user' => 'fa-user',
            'viewer' => 'fa-eye',
            default => 'fa-user'
        };
    }
}
