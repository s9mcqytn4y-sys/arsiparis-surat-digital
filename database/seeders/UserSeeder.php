<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $rektorat = UnitKerja::where('kode_unit', 'REK-01')->firstOrFail();
        $fakultasTeknik = UnitKerja::where('kode_unit', 'FTIK-01')->firstOrFail();

        // 1. Super Admin (Biro TIK / Administrator Utama)
        $admin = User::create([
            'name' => 'Administrator Sistem TU',
            'email' => 'admin@universitas.ac.id',
            'password' => Hash::make('password'),
            'unit_kerja_id' => $rektorat->id,
            'role' => 'super_admin',
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        // 2. Petugas Tata Usaha Fakultas Teknik
        $tuFtik = User::create([
            'name' => 'Staf Tata Usaha FTIK',
            'email' => 'tu.ftik@universitas.ac.id',
            'password' => Hash::make('password'),
            'unit_kerja_id' => $fakultasTeknik->id,
            'role' => 'petugas_tu',
            'is_active' => true,
        ]);
        $tuFtik->assignRole('petugas_tu');

        // 3. Pimpinan Unit (Dekan FTIK)
        $dekanFtik = User::create([
            'name' => 'Dekan FTIK',
            'email' => 'dekan.ftik@universitas.ac.id',
            'password' => Hash::make('password'),
            'unit_kerja_id' => $fakultasTeknik->id,
            'role' => 'pimpinan_unit',
            'is_active' => true,
        ]);
        $dekanFtik->assignRole('pimpinan_unit');
    }
}
