<?php

namespace App\Enums;

enum StatusReservasi: string
{
    case MENUNGGU = 'menunggu';
    case DI_TERIMA = 'di_terima';
    case SEDANG_DIPROSES = 'sedang_diproses';
    case SELESAI = 'selesai';
    case DIAMBIL = 'diambil';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Menunggu',
            self::DI_TERIMA => 'Diterima',
            self::SEDANG_DIPROSES => 'Sedang Diproses',
            self::SELESAI => 'Selesai',
            self::DIAMBIL => 'Diambil',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MENUNGGU => 'yellow',
            self::DI_TERIMA => 'blue',
            self::SEDANG_DIPROSES => 'purple',
            self::SELESAI => 'green',
            self::DIAMBIL => 'teal',
            self::DIBATALKAN => 'red',
        };
    }
}
