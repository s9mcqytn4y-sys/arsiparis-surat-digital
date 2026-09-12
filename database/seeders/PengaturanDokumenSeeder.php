<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PengaturanDokumen;
use Illuminate\Database\Seeder;

class PengaturanDokumenSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanDokumen::getAktif();
    }
}
