<?php

declare(strict_types=1);

namespace App\Enums;

enum LevelUnitKerja: string
{
    case UNIVERSITAS = 'universitas';
    case FAKULTAS = 'fakultas';
    case PRODI = 'prodi';
    case LEMBAGA = 'lembaga';
    case BIRO = 'biro';

    public function label(): string
    {
        return match ($this) {
            self::UNIVERSITAS => 'Tingkat Universitas / Rektorat',
            self::FAKULTAS => 'Tingkat Fakultas / Dekanat',
            self::PRODI => 'Program Studi',
            self::LEMBAGA => 'Lembaga (LPPM / LPM)',
            self::BIRO => 'Biro Administrasi (BAAK / BAUK)',
        };
    }
}
