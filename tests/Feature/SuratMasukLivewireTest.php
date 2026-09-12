<?php

declare(strict_types=1);

use App\Enums\LevelUnitKerja;
use App\Livewire\SuratMasuk\Create;
use App\Livewire\SuratMasuk\Index;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('staf TU dapat mengakses daftar surat masuk pada unit kerjanya', function () {
    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $this->seed(RoleAndPermissionSeeder::class);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('petugas_tu');

    SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0001',
        'nomor_surat' => '099/REK/IX/2026',
        'pengirim' => 'Rektorat',
        'perihal' => 'Pengumuman Libur Nasional',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'created_by' => $user->id,
    ]);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee('099/REK/IX/2026')
        ->assertSee('Pengumuman Libur Nasional');
});

test('formulir pencatatan surat masuk memvalidasi input wajib dan mengunggah berkas PDF', function () {
    $this->seed(RoleAndPermissionSeeder::class);
    Storage::fake('local');

    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('petugas_tu');
    $this->actingAs($user);

    $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Title (Dokumen Masuk) >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
    $fakePdf = UploadedFile::fake()->createWithContent('naskah_resmi.pdf', $pdfContent);

    Livewire::test(Create::class)
        ->set('nomorSurat', '123/KEMDIKBUD/IX/2026')
        ->set('pengirim', 'Kementerian Pendidikan')
        ->set('perihal', 'Pedoman MBKM 2026')
        ->set('tanggalSurat', '2026-09-11')
        ->set('tanggalTerima', '2026-09-12')
        ->set('unitKerjaId', $unit->id)
        ->set('berkas', $fakePdf)
        ->call('simpan')
        ->assertHasNoErrors()
        ->assertRedirect(route('surat-masuk.index'));

    $this->assertDatabaseHas('surat_masuk', [
        'nomor_surat' => '123/KEMDIKBUD/IX/2026',
        'pengirim' => 'Kementerian Pendidikan',
        'perihal' => 'Pedoman MBKM 2026',
        'unit_kerja_id' => $unit->id,
    ]);
});
