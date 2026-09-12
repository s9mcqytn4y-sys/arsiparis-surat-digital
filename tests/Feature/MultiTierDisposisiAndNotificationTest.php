<?php

declare(strict_types=1);

use App\Actions\Disposisi\DelegasikanSuratAction;
use App\Enums\LevelUnitKerja;
use App\Enums\StatusDisposisi;
use App\Livewire\Common\NotificationBadge;
use App\Livewire\SuratMasuk\DisposisiModal;
use App\Models\Pegawai;
use App\Models\RiwayatDisposisi;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use App\Notifications\DisposisiMasukNotification;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('alur disposisi berjenjang naskah dinas: Rektorat -> Dekanat -> Program Studi merekam linimasa lengkap', function (): void {
    Notification::fake();

    $unitUniv = UnitKerja::create([
        'kode_unit' => 'UNIV',
        'nama_unit' => 'Kantor Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $unitFakultas = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknik dan Informatika',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $unitProdi = UnitKerja::create([
        'kode_unit' => 'IF',
        'nama_unit' => 'Program Studi Informatika',
        'level_unit' => LevelUnitKerja::PRODI,
    ]);

    $userRektor = User::factory()->create(['unit_kerja_id' => $unitUniv->id]);
    $userDekan = User::factory()->create(['unit_kerja_id' => $unitFakultas->id]);
    $userKaprodi = User::factory()->create(['unit_kerja_id' => $unitProdi->id]);

    $pegawaiRektor = Pegawai::create([
        'nip_nidn' => '197501012000031001',
        'nama' => 'Prof. Dr. Rektor Universitas, M.Eng.',
        'jabatan' => 'Rektor',
        'email' => $userRektor->email,
        'unit_kerja_id' => $unitUniv->id,
        'is_active' => true,
    ]);

    $pegawaiDekan = Pegawai::create([
        'nip_nidn' => '198002022005011002',
        'nama' => 'Dr. Dekan Fakultas, S.T., M.Kom.',
        'jabatan' => 'Dekan FTIK',
        'email' => $userDekan->email,
        'unit_kerja_id' => $unitFakultas->id,
        'is_active' => true,
    ]);

    $pegawaiKaprodi = Pegawai::create([
        'nip_nidn' => '198503032010011003',
        'nama' => 'Dr. Kaprodi Informatika, M.Cs.',
        'jabatan' => 'Ketua Program Studi Informatika',
        'email' => $userKaprodi->email,
        'unit_kerja_id' => $unitProdi->id,
        'is_active' => true,
    ]);

    $suratMasuk = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0001',
        'nomor_surat' => '001/LLDIKTI4/IX/2026',
        'pengirim' => 'LLDIKTI Wilayah IV',
        'perihal' => 'Akreditasi Internasional Program Studi',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unitUniv->id,
        'created_by' => $userRektor->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
    ]);

    $action = app(DelegasikanSuratAction::class);

    // Jenjang 1: Rektor mendisposisikan surat ke Dekan
    $action->execute(
        suratMasuk: $suratMasuk,
        dariPegawai: $pegawaiRektor,
        kePegawai: $pegawaiDekan,
        instruksi: 'Pelajari syarat dan segera instruksikan prodi untuk persiapan dokumen.',
        catatanTindakLanjut: 'Prioritaskan akreditasi ABET.',
        tenggatWaktu: '2026-09-20'
    );

    $suratMasuk->refresh();
    expect($suratMasuk->status_disposisi)->toBe(StatusDisposisi::Diproses)
        ->and($suratMasuk->disposisi_kepada)->toBe($pegawaiDekan->id);

    // Jenjang 2: Dekan mendisposisikan surat lanjut ke Kaprodi
    $action->execute(
        suratMasuk: $suratMasuk,
        dariPegawai: $pegawaiDekan,
        kePegawai: $pegawaiKaprodi,
        instruksi: 'Bentuk task force akreditasi prodi Informatika.',
        catatanTindakLanjut: 'Rapat koordinasi tim prodi hari Senin.',
        tenggatWaktu: '2026-09-25'
    );

    $suratMasuk->refresh();
    expect($suratMasuk->disposisi_kepada)->toBe($pegawaiKaprodi->id);

    // Verifikasi total riwayat berjenjang tersimpan 2 langkah
    $riwayatList = RiwayatDisposisi::where('surat_masuk_id', $suratMasuk->id)->orderBy('created_at')->get();
    expect($riwayatList)->toHaveCount(2)
        ->and($riwayatList[0]->dari_pegawai_id)->toBe($pegawaiRektor->id)
        ->and($riwayatList[0]->ke_pegawai_id)->toBe($pegawaiDekan->id)
        ->and($riwayatList[1]->dari_pegawai_id)->toBe($pegawaiDekan->id)
        ->and($riwayatList[1]->ke_pegawai_id)->toBe($pegawaiKaprodi->id);

    // Verifikasi notifikasi terkirim ke Dekan dan Kaprodi
    Notification::assertSentTo($userDekan, DisposisiMasukNotification::class);
    Notification::assertSentTo($userKaprodi, DisposisiMasukNotification::class);
});

test('komponen Livewire DisposisiModal memvalidasi form dan memproses penyimpanan instruksi', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknik',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('super_admin');

    $pegawaiPengirim = Pegawai::create([
        'nip_nidn' => '197001011995031001',
        'nama' => 'Prof. Dr. Ir. Pimpinan, M.Sc.',
        'jabatan' => 'Wakil Rektor I',
        'email' => $user->email,
        'unit_kerja_id' => $unit->id,
        'is_active' => true,
    ]);

    $pegawaiPenerima = Pegawai::create([
        'nip_nidn' => '198202022008011002',
        'nama' => 'Dr. Staf Ahli, S.T., M.T.',
        'jabatan' => 'Ketua Lembaga Penjaminan Mutu',
        'email' => 'staf.ahli@kampus.ac.id',
        'unit_kerja_id' => $unit->id,
        'is_active' => true,
    ]);

    $suratMasuk = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0005',
        'nomor_surat' => '055/DIRJEN/IX/2026',
        'pengirim' => 'Direktorat Jenderal Diktiristek',
        'perihal' => 'Undangan Sosialisasi Instrumen Baru',
        'tanggal_surat' => '2026-09-11',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'created_by' => $user->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
    ]);

    $this->actingAs($user);

    // Validasi input kosong
    Livewire::test(DisposisiModal::class, ['suratId' => $suratMasuk->id])
        ->set('disposisiKepada', '')
        ->set('instruksiDisposisi', '')
        ->call('simpanDisposisi')
        ->assertHasErrors(['disposisiKepada', 'instruksiDisposisi']);

    // Validasi submit sukses
    Livewire::test(DisposisiModal::class, ['suratId' => $suratMasuk->id])
        ->set('disposisiKepada', $pegawaiPenerima->id)
        ->set('instruksiDisposisi', 'Mohon hadiri undangan mewakili universitas.')
        ->set('statusDisposisi', StatusDisposisi::Diproses->value)
        ->call('simpanDisposisi')
        ->assertHasNoErrors()
        ->assertDispatched('disposisi-tersimpan');

    $suratMasuk->refresh();
    expect($suratMasuk->disposisi_kepada)->toBe($pegawaiPenerima->id)
        ->and($suratMasuk->instruksi_disposisi)->toBe('Mohon hadiri undangan mewakili universitas.')
        ->and($suratMasuk->status_disposisi)->toBe(StatusDisposisi::Diproses);
});

test('komponen Livewire NotificationBadge menampilkan counter notifikasi belum dibaca dan menandai telah dibaca', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('pimpinan_unit');

    $suratMasuk = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0009',
        'nomor_surat' => '099/LLDIKTI/IX/2026',
        'pengirim' => 'LLDIKTI',
        'perihal' => 'Pemberitahuan Audit Kearsipan',
        'tanggal_surat' => '2026-09-12',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'created_by' => $user->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
    ]);

    $notification = new DisposisiMasukNotification(
        suratMasuk: $suratMasuk,
        pemberiNama: 'Sekretaris Universitas',
        instruksi: 'Tolong siapkan arsip berkas surat masuk tahun 2026.'
    );

    $user->notify($notification);

    $this->actingAs($user);

    expect($user->unreadNotifications()->count())->toBe(1);

    // Test render badge awal
    $test = Livewire::test(NotificationBadge::class)
        ->assertStatus(200)
        ->assertSee('1')
        ->assertSee('REG-SM/2026/09/0009')
        ->assertSee('Pemberitahuan Audit Kearsipan');

    // Test toggle dropdown
    $test->call('toggleDropdown')
        ->assertSet('isOpen', true);

    // Test markAllAsRead
    $test->call('markAllAsRead');

    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});
