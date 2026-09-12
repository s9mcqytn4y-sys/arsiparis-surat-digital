<?php

declare(strict_types=1);

use App\Actions\Surat\GenerateNomorSuratAction;
use App\Enums\LevelUnitKerja;
use App\Livewire\SuratKeluar\Create;
use App\Models\MasterNomorSurat;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('generator nomor surat menghasilkan penomoran berurutan dan format bulan romawi dengan benar', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Kantor Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $master = MasterNomorSurat::create([
        'kode_klasifikasi' => 'B/KP.01.00',
        'nama_klasifikasi' => 'Kepegawaian & Persuratan Rektorat',
        'format_pola' => '{nomor}/{kode}/{unit}/{bulan_romawi}/{tahun}',
        'nomor_terakhir' => 0,
        'tahun' => 2026,
        'is_active' => true,
        'unit_kerja_id' => $unit->id,
    ]);

    $action = app(GenerateNomorSuratAction::class);

    // Bulan 9 (September) harus menghasilkan IX
    $result1 = $action->execute(masterNomorId: $master->id, tahun: 2026, bulan: 9);

    expect($result1->nomorSurat)->toBe('0001/B/KP.01.00/REK/IX/2026')
        ->and($result1->urutan)->toBe(1)
        ->and($result1->nomorAgenda)->toBe('REG-SK/2026/09/0001')
        ->and($result1->kodeKlasifikasi)->toBe('B/KP.01.00');

    // Naskah kedua pada bulan 12 (Desember) harus menghasilkan XII dengan urutan bertambah
    $result2 = $action->execute(masterNomorId: $master->id, tahun: 2026, bulan: 12);

    expect($result2->nomorSurat)->toBe('0002/B/KP.01.00/REK/XII/2026')
        ->and($result2->urutan)->toBe(2)
        ->and($result2->nomorAgenda)->toBe('REG-SK/2026/12/0002');
});

test('penomoran surat berurutan secara atomik tanpa duplikasi pada unit yang sama', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknik dan Informatika',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $master = MasterNomorSurat::create([
        'kode_klasifikasi' => 'TU.02.01',
        'nama_klasifikasi' => 'Akademik dan Kemahasiswaan FTIK',
        'format_pola' => '{nomor}/{kode}/{unit}/{tahun}',
        'nomor_terakhir' => 10,
        'tahun' => 2026,
        'is_active' => true,
        'unit_kerja_id' => $unit->id,
    ]);

    $action = app(GenerateNomorSuratAction::class);

    $generatedNumbers = [];
    for ($i = 1; $i <= 5; $i++) {
        $res = $action->execute(masterNomorId: $master->id, tahun: 2026, bulan: 9);
        $generatedNumbers[] = $res->nomorSurat;
    }

    // Pastikan tidak ada satupun nomor duplikat dan berurutan dari 11 ke 15
    expect(count(array_unique($generatedNumbers)))->toBe(5)
        ->and($generatedNumbers[0])->toBe('0011/TU.02.01/FTIK/2026')
        ->and($generatedNumbers[4])->toBe('0015/TU.02.01/FTIK/2026');

    $master->refresh();
    expect($master->nomor_terakhir)->toBe(15);
});

test('komponen Livewire SuratKeluar Create memvalidasi input wajib dan menyimpan naskah keluar', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('super_admin');

    $master = MasterNomorSurat::create([
        'kode_klasifikasi' => 'HM.01.02',
        'nama_klasifikasi' => 'Hubungan Masyarakat',
        'format_pola' => '{nomor}/{kode}/{unit}/{bulan_romawi}/{tahun}',
        'nomor_terakhir' => 0,
        'tahun' => 2026,
        'is_active' => true,
        'unit_kerja_id' => $unit->id,
    ]);

    $pejabat = Pegawai::create([
        'nip_nidn' => '197605052002121001',
        'nama' => 'Prof. Dr. Pejabat Rektorat, S.H., M.H.',
        'jabatan' => 'Wakil Rektor Bidang Akademik',
        'email' => 'warek1@kampus.ac.id',
        'unit_kerja_id' => $unit->id,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    // 1. Validasi error jika field wajib kosong
    Livewire::test(Create::class)
        ->set('masterNomorSuratId', '')
        ->set('jenisSurat', '')
        ->set('tujuanSurat', '')
        ->set('perihal', '')
        ->call('simpan')
        ->assertHasErrors(['masterNomorSuratId', 'jenisSurat', 'tujuanSurat', 'perihal']);

    // 2. Submit data valid
    Livewire::test(Create::class)
        ->set('masterNomorSuratId', $master->id)
        ->set('jenisSurat', 'Surat Tugas')
        ->set('tujuanSurat', 'Kepala LLDIKTI Wilayah IV')
        ->set('tanggalSurat', '2026-09-12')
        ->set('perihal', 'Laporan Pembukaan Program Studi Baru')
        ->call('simpan')
        ->assertHasNoErrors()
        ->assertSessionHas('status', 'Naskah surat keluar berhasil diterbitkan dengan nomor resmi.')
        ->assertRedirect(route('surat-keluar.index'));

    // Verifikasi data tersimpan di database
    $suratKeluar = SuratKeluar::where('jenis_surat', 'Surat Tugas')->first();
    expect($suratKeluar)->not->toBeNull()
        ->and($suratKeluar->nomor_surat)->toBe('0001/HM.01.02/REK/IX/2026')
        ->and($suratKeluar->nomor_agenda)->toBe('REG-SK/2026/09/0001')
        ->and($suratKeluar->tujuan)->toBe('Kepala LLDIKTI Wilayah IV')
        ->and($suratKeluar->created_by)->toBe($user->id);
});
