<?php

declare(strict_types=1);

use App\Actions\Disposisi\DelegasikanSuratAction;
use App\Enums\LevelUnitKerja;
use App\Enums\StatusDisposisi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use App\Notifications\DisposisiMasukNotification;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('pendelegasian surat masuk merekam riwayat, memperbarui status, dan mengirimkan notifikasi in-app', function () {
    Notification::fake();
    $this->seed(RoleAndPermissionSeeder::class);

    $unitRektorat = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Rektorat Universitas',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $unitFakultas = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $userPembuat = User::factory()->create(['unit_kerja_id' => $unitRektorat->id]);
    $userPenerima = User::factory()->create(['unit_kerja_id' => $unitFakultas->id]);

    $pegawaiRektor = Pegawai::create([
        'nip_nidn' => '197501012000031001',
        'nama' => 'Prof. Dr. Ir. H. Ahmad Rektor, M.T.',
        'jabatan' => 'Rektor',
        'email' => $userPembuat->email,
        'unit_kerja_id' => $unitRektorat->id,
        'is_active' => true,
    ]);

    $pegawaiDekan = Pegawai::create([
        'nip_nidn' => '198002022005011002',
        'nama' => 'Dr. Eng. Ir. Budi Dekan, S.Kom., M.Kom.',
        'jabatan' => 'Dekan FTIK',
        'email' => $userPenerima->email,
        'unit_kerja_id' => $unitFakultas->id,
        'is_active' => true,
    ]);

    $suratMasuk = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0010',
        'nomor_surat' => '101/KEMENDIKBUD/IX/2026',
        'pengirim' => 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi',
        'perihal' => 'Monitoring Hibah Riset Kampus Berdampak',
        'tanggal_surat' => '2026-09-08',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unitRektorat->id,
        'created_by' => $userPembuat->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
    ]);

    $action = app(DelegasikanSuratAction::class);

    $riwayat = $action->execute(
        suratMasuk: $suratMasuk,
        dariPegawai: $pegawaiRektor,
        kePegawai: $pegawaiDekan,
        instruksi: 'Tindak lanjuti segera dan koordinasikan dengan ketua program studi.',
        catatanTindakLanjut: 'Mohon laporan hasil koordinasi paling lambat lusa.',
        tenggatWaktu: '2026-09-15'
    );

    // 1. Verifikasi data riwayat tersimpan
    expect($riwayat->surat_masuk_id)->toBe($suratMasuk->id)
        ->and($riwayat->dari_pegawai_id)->toBe($pegawaiRektor->id)
        ->and($riwayat->ke_pegawai_id)->toBe($pegawaiDekan->id)
        ->and($riwayat->instruksi)->toBe('Tindak lanjuti segera dan koordinasikan dengan ketua program studi.')
        ->and($riwayat->status)->toBe(StatusDisposisi::Diproses);

    // 2. Verifikasi status surat masuk ter-update
    $suratMasuk->refresh();
    expect($suratMasuk->disposisi_kepada)->toBe($pegawaiDekan->id)
        ->and($suratMasuk->status_disposisi)->toBe(StatusDisposisi::Diproses)
        ->and($suratMasuk->instruksi_disposisi)->toBe('Tindak lanjuti segera dan koordinasikan dengan ketua program studi.');

    // 3. Verifikasi notifikasi in-app terkirim ke user pegawai tujuan
    Notification::assertSentTo(
        $userPenerima,
        DisposisiMasukNotification::class,
        function (DisposisiMasukNotification $notification) use ($suratMasuk, $pegawaiRektor) {
            $data = $notification->toArray(new stdClass);

            return $data['surat_masuk_id'] === $suratMasuk->id
                && $data['nomor_agenda'] === $suratMasuk->nomor_agenda
                && $data['pemberi_nama'] === $pegawaiRektor->nama;
        }
    );
});
