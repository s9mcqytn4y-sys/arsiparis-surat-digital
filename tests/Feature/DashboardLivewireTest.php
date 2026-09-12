<?php

declare(strict_types=1);

use App\Enums\LevelUnitKerja;
use App\Livewire\Dashboard\Index;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Livewire\Livewire;

test('pengguna terotentikasi dapat mengakses dasbor tata usaha', function () {
    $this->seed(RoleAndPermissionSeeder::class);

    $unit = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Rektorat Universitas',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertStatus(200)
        ->assertSee('Dashboard Tata Usaha')
        ->assertSee('Aksi Cepat')
        ->assertSee('Aktivitas Terbaru');
});

test('komponen livewire dasbor memuat statistik dan aktivitas terbaru', function () {
    $this->seed(RoleAndPermissionSeeder::class);

    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('petugas_tu');

    $pegawai = Pegawai::create([
        'unit_kerja_id' => $unit->id,
        'nip_nidn' => '198501012010121001',
        'nama' => 'Dr. Ir. Ahmad Junaedi, M.Kom.',
        'jabatan' => 'Dekan Fakultas',
        'is_active' => true,
    ]);

    $tahunIni = (int) date('Y');

    // Naskah Masuk
    SuratMasuk::create([
        'nomor_agenda' => "REG-SM/{$tahunIni}/09/0001",
        'nomor_surat' => '001/MASUK/IX/'.$tahunIni,
        'pengirim' => 'LLDIKTI Wilayah VII',
        'perihal' => 'Undangan Rapat Koordinasi MBKM',
        'tanggal_surat' => "{$tahunIni}-09-01",
        'tanggal_terima' => "{$tahunIni}-09-05",
        'unit_kerja_id' => $unit->id,
        'created_by' => $user->id,
    ]);

    // Naskah Keluar
    SuratKeluar::create([
        'nomor_agenda' => "REG-SK/{$tahunIni}/09/0001",
        'nomor_surat' => '001/KELUAR/IX/'.$tahunIni,
        'kode_klasifikasi' => 'KP.01',
        'jenis_surat' => 'Surat Pengantar',
        'tujuan' => 'Dekan Seluruh Fakultas',
        'perihal' => 'Surat Pengantar Kalender Akademik',
        'tanggal_surat' => "{$tahunIni}-09-06",
        'unit_kerja_id' => $unit->id,
        'created_by' => $user->id,
    ]);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee('001/MASUK/IX/'.$tahunIni)
        ->assertSee('Undangan Rapat Koordinasi MBKM');
});
