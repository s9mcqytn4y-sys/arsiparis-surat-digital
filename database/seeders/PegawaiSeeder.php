<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $rektorat = UnitKerja::where('kode_unit', 'REK-01')->firstOrFail();
        $biroAak = UnitKerja::where('kode_unit', 'BAAK-01')->firstOrFail();
        $fakultasTeknik = UnitKerja::where('kode_unit', 'FTIK-01')->firstOrFail();
        $prodiTi = UnitKerja::where('kode_unit', 'TI-01')->firstOrFail();

        // 1. Rektor (Penandatangan SK & Surat Penting)
        Pegawai::create([
            'unit_kerja_id' => $rektorat->id,
            'nip_nidn' => '196508121990031002',
            'nama' => 'Prof. Dr. Ir. H. Bambang Sudarsono, M.Eng.',
            'jabatan' => 'Rektor Universitas',
            'golongan' => 'IV/e',
            'no_telepon' => '081122334455',
            'email' => 'rektor@universitas.ac.id',
            'is_penandatangan' => true,
            'is_active' => true,
        ]);

        // 2. Kepala Biro AAK
        Pegawai::create([
            'unit_kerja_id' => $biroAak->id,
            'nip_nidn' => '197204151998021001',
            'nama' => 'Drs. Hendra Gunawan, M.M.',
            'jabatan' => 'Kepala Biro AAK',
            'golongan' => 'IV/b',
            'no_telepon' => '081234567890',
            'email' => 'ka.baak@universitas.ac.id',
            'is_penandatangan' => true,
            'is_active' => true,
        ]);

        // 3. Dekan Fakultas Teknik
        Pegawai::create([
            'unit_kerja_id' => $fakultasTeknik->id,
            'nip_nidn' => '197805192003122001',
            'nama' => 'Dr. Eng. Ir. Siti Nurhaliza, S.T., M.T.',
            'jabatan' => 'Dekan Fakultas Teknik & Ilmu Komputer',
            'golongan' => 'IV/a',
            'no_telepon' => '081398765432',
            'email' => 'dekan.ftik@universitas.ac.id',
            'is_penandatangan' => true,
            'is_active' => true,
        ]);

        // 4. Ketua Program Studi TI
        Pegawai::create([
            'unit_kerja_id' => $prodiTi->id,
            'nip_nidn' => '198511202010121003',
            'nama' => 'Ahmad Fauzi, S.Kom., M.Kom.',
            'jabatan' => 'Ketua Program Studi S1 Teknologi Informasi',
            'golongan' => 'III/c',
            'no_telepon' => '081512344321',
            'email' => 'kaprodi.ti@universitas.ac.id',
            'is_penandatangan' => true,
            'is_active' => true,
        ]);

        // 5. Staf Tata Usaha Fakultas Teknik
        Pegawai::create([
            'unit_kerja_id' => $fakultasTeknik->id,
            'nip_nidn' => '199203102018011005',
            'nama' => 'Budi Santoso, S.AP.',
            'jabatan' => 'Pengadministrasi Umum TU',
            'golongan' => 'III/a',
            'no_telepon' => '082155667788',
            'email' => 'tu.ftik@universitas.ac.id',
            'is_penandatangan' => false,
            'is_active' => true,
        ]);
    }
}
