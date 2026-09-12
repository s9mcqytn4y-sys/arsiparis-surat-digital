<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class NomorSuratResult
{
    public function __construct(
        public string $nomorSurat,
        public string $nomorAgenda,
        public int $urutan,
        public string $kodeKlasifikasi,
    ) {}
}
