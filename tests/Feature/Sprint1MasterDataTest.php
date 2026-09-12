<?php

declare(strict_types=1);

use App\Enums\KategoriArsip;
use App\Enums\LevelUnitKerja;
use App\Enums\StatusDisposisi;
use App\Models\ArsipDigital;
use App\Models\MasterNomorSurat;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    $this->seed();
});

test('database seeder initializes roles and university multi-unit hierarchy', function (): void {
    expect(Role::where('name', 'super_admin')->exists())->toBeTrue()
        ->and(Role::where('name', 'petugas_tu')->exists())->toBeTrue()
        ->and(Role::where('name', 'pimpinan_unit')->exists())->toBeTrue();

    $rektorat = UnitKerja::where('kode_unit', 'REK-01')->first();
    expect($rektorat)->not->toBeNull()
        ->and(Str::isUuid($rektorat->id))->toBeTrue()
        ->and($rektorat->level)->toBe(LevelUnitKerja::UNIVERSITAS);

    $prodi = UnitKerja::where('kode_unit', 'TI-01')->first();
    expect($prodi)->not->toBeNull()
        ->and($prodi->level)->toBe(LevelUnitKerja::PRODI)
        ->and($prodi->parent)->not->toBeNull()
        ->and($prodi->parent->kode_unit)->toBe('FTIK-01');
});

test('user authenticates and is assigned appropriate role and unit kerja', function (): void {
    $superAdmin = User::where('email', 'admin@universitas.ac.id')->first();
    expect($superAdmin)->not->toBeNull()
        ->and(Str::isUuid($superAdmin->id))->toBeTrue()
        ->and($superAdmin->hasRole('super_admin'))->toBeTrue()
        ->and($superAdmin->unitKerja)->not->toBeNull()
        ->and($superAdmin->unitKerja->kode_unit)->toBe('REK-01');

    $tuUser = User::where('email', 'tu.ftik@universitas.ac.id')->first();
    expect($tuUser)->not->toBeNull()
        ->and($tuUser->hasRole('petugas_tu'))->toBeTrue()
        ->and($tuUser->unitKerja->kode_unit)->toBe('FTIK-01');
});

test('pegawai is linked to unit kerja and can be configured as authorized signer', function (): void {
    $rektor = Pegawai::where('nip_nidn', '196508121990031002')->first();
    expect($rektor)->not->toBeNull()
        ->and(Str::isUuid($rektor->id))->toBeTrue()
        ->and($rektor->is_penandatangan)->toBeTrue()
        ->and($rektor->unitKerja->kode_unit)->toBe('REK-01');
});

test('master nomor surat formats are seeded with composite integrity', function (): void {
    $currentYear = (int) date('Y');
    $master = MasterNomorSurat::where('kode_klasifikasi', 'HM.02')
        ->where('tahun', $currentYear)
        ->first();

    expect($master)->not->toBeNull()
        ->and(Str::isUuid($master->id))->toBeTrue()
        ->and($master->format_pola)->toContain('{NOMOR}/UNIV/SK-REK/{BULAN_ROMAWI}/{TAHUN}')
        ->and($master->nomor_terakhir)->toBe(0);
});

test('surat masuk and surat keluar relations maintain referential integrity with UUIDv7', function (): void {
    $tuUser = User::where('email', 'tu.ftik@universitas.ac.id')->firstOrFail();
    $pegawai = Pegawai::where('nip_nidn', '197805192003122001')->firstOrFail();

    // Surat Masuk
    $suratMasuk = SuratMasuk::create([
        'unit_kerja_id' => $tuUser->unit_kerja_id,
        'nomor_agenda' => 'REG-SM/2026/09/0001',
        'nomor_surat' => '054/KEMENDIKBUD/IX/2026',
        'pengirim' => 'Kemendikbudristek Ditjen Dikti',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'perihal' => 'Monitoring Hibah Program Studi',
        'ringkasan' => 'Permintaan data perkembangan hibah',
        'disposisi_kepada' => $pegawai->id,
        'instruksi_disposisi' => 'Mohon ditindaklanjuti bersama tim prodi.',
        'status_disposisi' => StatusDisposisi::Menunggu,
        'created_by' => $tuUser->id,
    ]);

    expect($suratMasuk->id)->not->toBeNull()
        ->and(Str::isUuid($suratMasuk->id))->toBeTrue()
        ->and($suratMasuk->status_disposisi)->toBe(StatusDisposisi::Menunggu)
        ->and($suratMasuk->pegawaiDisposisi->id)->toBe($pegawai->id)
        ->and($suratMasuk->unitKerja->id)->toBe($tuUser->unit_kerja_id);

    // Surat Keluar
    $suratKeluar = SuratKeluar::create([
        'unit_kerja_id' => $tuUser->unit_kerja_id,
        'nomor_agenda' => 'REG-SK/2026/09/0001',
        'nomor_surat' => '001/FTIK/TU/IX/2026',
        'kode_klasifikasi' => 'TU.01',
        'tujuan' => 'Seluruh Dosen FTIK',
        'tanggal_surat' => '2026-09-12',
        'perihal' => 'Undangan Rapat Awal Semester Gasal 2026/2027',
        'ringkasan' => 'Rapat koordinasi awal perkuliahan',
        'penandatangan_id' => $pegawai->id,
        'created_by' => $tuUser->id,
    ]);

    expect($suratKeluar->id)->not->toBeNull()
        ->and(Str::isUuid($suratKeluar->id))->toBeTrue()
        ->and($suratKeluar->penandatangan->id)->toBe($pegawai->id);

    // Arsip Digital
    $arsip = ArsipDigital::create([
        'unit_kerja_id' => $tuUser->unit_kerja_id,
        'judul' => 'SK Dekan Penetapan Dosen Pembimbing Skripsi 2026',
        'kategori' => KategoriArsip::SK_DEKAN,
        'nomor_dokumen' => 'SK-012/FTIK/2026',
        'tanggal_dokumen' => '2026-09-01',
        'deskripsi' => 'Arsip SK Dosen Pembimbing Tahun Akademik 2026/2027',
        'file_path' => 'private/archives/sample.pdf',
        'file_mime' => 'application/pdf',
        'file_size' => 204800,
        'pegawai_id' => $pegawai->id,
        'created_by' => $tuUser->id,
    ]);

    expect($arsip->id)->not->toBeNull()
        ->and(Str::isUuid($arsip->id))->toBeTrue()
        ->and($arsip->kategori)->toBe(KategoriArsip::SK_DEKAN)
        ->and($arsip->pegawai->id)->toBe($pegawai->id);
});
