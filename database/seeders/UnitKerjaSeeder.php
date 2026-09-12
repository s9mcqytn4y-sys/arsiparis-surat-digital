<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\LevelUnitKerja;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Level Universitas (Rektorat)
        $rektorat = UnitKerja::create([
            'kode_unit' => 'REK-01',
            'nama_unit' => 'Rektorat Universitas',
            'level' => LevelUnitKerja::UNIVERSITAS,
            'singkatan' => 'REK',
            'kepala_nama' => 'Prof. Dr. Ir. H. Bambang Sudarsono, M.Eng.',
            'kepala_nip' => '196508121990031002',
            'is_active' => true,
        ]);

        // 2. Biro Administrasi Akademik & Kemahasiswaan
        $biroAak = UnitKerja::create([
            'parent_id' => $rektorat->id,
            'kode_unit' => 'BAAK-01',
            'nama_unit' => 'Biro Administrasi Akademik dan Kemahasiswaan',
            'level' => LevelUnitKerja::BIRO,
            'singkatan' => 'BAAK',
            'kepala_nama' => 'Drs. Hendra Gunawan, M.M.',
            'kepala_nip' => '197204151998021001',
            'is_active' => true,
        ]);

        // 3. Fakultas Teknik & Ilmu Komputer
        $fakultasTeknik = UnitKerja::create([
            'parent_id' => $rektorat->id,
            'kode_unit' => 'FTIK-01',
            'nama_unit' => 'Fakultas Teknik dan Ilmu Komputer',
            'level' => LevelUnitKerja::FAKULTAS,
            'singkatan' => 'FTIK',
            'kepala_nama' => 'Dr. Eng. Ir. Siti Nurhaliza, S.T., M.T.',
            'kepala_nip' => '197805192003122001',
            'is_active' => true,
        ]);

        // 4. Program Studi Teknologi Informasi
        $prodiTi = UnitKerja::create([
            'parent_id' => $fakultasTeknik->id,
            'kode_unit' => 'TI-01',
            'nama_unit' => 'Program Studi S1 Teknologi Informasi',
            'level' => LevelUnitKerja::PRODI,
            'singkatan' => 'PSTI',
            'kepala_nama' => 'Ahmad Fauzi, S.Kom., M.Kom.',
            'kepala_nip' => '198511202010121003',
            'is_active' => true,
        ]);

        // 5. Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)
        UnitKerja::create([
            'parent_id' => $rektorat->id,
            'kode_unit' => 'LPPM-01',
            'nama_unit' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat',
            'level' => LevelUnitKerja::LEMBAGA,
            'singkatan' => 'LPPM',
            'kepala_nama' => 'Dr. Rahmat Hidayat, M.Sc.',
            'kepala_nip' => '197509142001121002',
            'is_active' => true,
        ]);

    }
}
