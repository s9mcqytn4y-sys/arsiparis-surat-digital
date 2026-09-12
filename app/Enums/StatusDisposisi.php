<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusDisposisi: string
{
    case Menunggu = 'menunggu';
    case Diproses = 'diproses';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu Disposisi',
            self::Diproses => 'Sedang Diproses',
            self::Selesai => 'Selesai',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Menunggu => 'amber',
            self::Diproses => 'sky',
            self::Selesai => 'emerald',
        };
    }
}
