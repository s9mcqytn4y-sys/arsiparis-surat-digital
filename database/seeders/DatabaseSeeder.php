<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UnitKerjaSeeder::class,
            PegawaiSeeder::class,
            MasterNomorSuratSeeder::class,
            MasterOpsiSeeder::class,
            PengaturanDokumenSeeder::class,
            UserSeeder::class,
            SuratMasukSeeder::class,
            SuratKeluarSeeder::class,
            ArsipDigitalSeeder::class,
        ]);
    }
}
