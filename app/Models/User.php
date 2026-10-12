<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'ms_user';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'id_role',
        'role',
        'google_id',
        'no_telp',
        'alamat',
        'status',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id_role' => 'integer',
    ];

    public function getIdRoleAttribute($value): int
    {
        $role = strtolower((string) ($this->attributes['role'] ?? ''));

        return match ($role) {
            'superadmin', 'super_admin', 'super admin' => 1,
            'admin' => 2,
            'pelanggan', 'customer' => 3,
            default => (int) $value,
        };
    }

    public function setIdRoleAttribute($value): void
    {
        $this->attributes['role'] = match ((int) $value) {
            1 => 'superadmin',
            2 => 'admin',
            default => 'pelanggan',
        };
    }

    public function getRoleAttribute($value): string
    {
        return $value ?: match ((int) ($this->attributes['id_role'] ?? 3)) {
            1 => 'superadmin',
            2 => 'admin',
            default => 'pelanggan',
        };
    }

    /**
     * Get all reservations for this user
     */
    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class, 'id_user', 'id_user');
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->id_role === 1;
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->id_role === 2;
    }

    /**
     * Check if user is Pelanggan
     */
    public function isPelanggan(): bool
    {
        return $this->id_role === 3;
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->id_role) {
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'Pelanggan',
            default => 'Unknown',
        };
    }

    public function hasAnyRole(array $roles): bool
    {
        $normalizedRoles = array_map(
            fn (string $role): string => strtolower(str_replace([' ', '_'], '', $role)),
            $roles
        );

        $currentRoles = [
            (string) $this->id_role,
            strtolower(str_replace([' ', '_'], '', $this->role_label)),
        ];

        return count(array_intersect($normalizedRoles, $currentRoles)) > 0;
    }
}
