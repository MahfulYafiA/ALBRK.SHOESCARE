<?php

namespace App\Enums;

enum UserRole: int
{
    case SUPERADMIN = 1;
    case ADMIN = 2;
    case PELANGGAN = 3;

    public function label(): string
    {
        return match ($this) {
            self::SUPERADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::PELANGGAN => 'Pelanggan',
        };
    }

    public static function fromId(int $id): self
    {
        return match ($id) {
            1 => self::SUPERADMIN,
            2 => self::ADMIN,
            3 => self::PELANGGAN,
            default => self::PELANGGAN,
        };
    }
}
