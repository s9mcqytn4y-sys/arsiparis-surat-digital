<?php

declare(strict_types=1);

use App\Enums\LevelUnitKerja;
use App\Enums\StatusDisposisi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function (): void {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('ekspor CSV buku agenda menyertakan UTF-8 BOM dan kolom naskah masuk secara presisi', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Kantor Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('super_admin');

    $pegawai = Pegawai::create([
        'nip_nidn' => '197501012000031001',
        'nama' => 'Prof. Dr. Pejabat, M.Sc.',
        'jabatan' => 'Wakil Rektor I',
        'email' => 'pejabat@kampus.ac.id',
        'unit_kerja_id' => $unit->id,
        'is_active' => true,
    ]);

    $surat = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0001',
        'nomor_surat' => '045/DIKTI/IX/2026',
        'pengirim' => 'Kementerian Pendidikan Tinggi',
        'perihal' => 'Penyaluran Hibah Penelitian Kampus',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'disposisi_kepada' => $pegawai->id,
        'status_disposisi' => StatusDisposisi::Diproses,
        'catatan_disposisi' => 'Segera koordinasi LPPM',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('cetak.buku-agenda', [
            'export' => 'csv',
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-30',
        ]));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    // Tangkap streamed content
    $content = $response->streamedContent();

    // Verifikasi UTF-8 BOM untuk Microsoft Excel
    expect(str_starts_with($content, "\xEF\xBB\xBF"))->toBeTrue()
        ->and($content)->toContain('No. Agenda')
        ->and($content)->toContain('REG-SM/2026/09/0001')
        ->and($content)->toContain('045/DIKTI/IX/2026')
        ->and($content)->toContain('Kementerian Pendidikan Tinggi')
        ->and($content)->toContain('Penyaluran Hibah Penelitian Kampus')
        ->and($content)->toContain('Prof. Dr. Pejabat, M.Sc.')
        ->and($content)->toContain('Kantor Rektorat');
});

test('ekspor PDF buku agenda menghasilkan stream dokumen PDF landscape', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Kantor Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('super_admin');

    SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0002',
        'nomor_surat' => '046/LLDIKTI/IX/2026',
        'pengirim' => 'LLDIKTI Wilayah IV',
        'perihal' => 'Monitoring & Evaluasi Mutu Akademik',
        'tanggal_surat' => '2026-09-11',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('cetak.buku-agenda', [
            'export' => 'pdf',
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-30',
        ]));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
    // PDF Magic Bytes: %PDF-
    expect(str_starts_with($response->getContent(), '%PDF-'))->toBeTrue();
});

test('tampilan pratinjau cetak browser menampilkan kop surat dan tabel dinas', function (): void {
    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknik dan Informatika',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
    $user->assignRole('super_admin');

    SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0003',
        'nomor_surat' => '077/DEK/IX/2026',
        'pengirim' => 'Dekan Fakultas Kedokteran',
        'perihal' => 'Undangan Kolaborasi Riset Bioteknologi',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'status_disposisi' => StatusDisposisi::Diproses,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('cetak.buku-agenda', [
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-30',
        ]));

    $response->assertOk();
    $response->assertViewIs('cetak.buku-agenda');
    $response->assertSee('BUKU AGENDA NASKAH DINAS MASUK');
    $response->assertSee('REG-SM/2026/09/0003');
    $response->assertSee('Undangan Kolaborasi Riset Bioteknologi');
});

test('pembatasan data buku agenda berdasarkan unit kerja pengguna non-admin (unit scoping)', function (): void {
    $unitFakultas = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknik dan Informatika',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $unitRektorat = UnitKerja::create([
        'kode_unit' => 'REK',
        'nama_unit' => 'Kantor Rektorat',
        'level_unit' => LevelUnitKerja::UNIVERSITAS,
    ]);

    // Staf TU Fakultas Teknik
    $stafTuFakultas = User::factory()->create(['unit_kerja_id' => $unitFakultas->id]);
    $stafTuFakultas->assignRole('petugas_tu');

    // Surat Masuk di Rektorat
    SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0010',
        'nomor_surat' => '010/REK/IX/2026',
        'pengirim' => 'Kementerian Keuangan',
        'perihal' => 'Daftar Isian Pelaksanaan Anggaran (DIPA)',
        'tanggal_surat' => '2026-09-05',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unitRektorat->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
        'created_by' => $stafTuFakultas->id,
    ]);

    // Surat Masuk di Fakultas Teknik
    SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0011',
        'nomor_surat' => '011/FTIK/IX/2026',
        'pengirim' => 'Industri Mitra Magang',
        'perihal' => 'Penerimaan Mahasiswa Magang Industri',
        'tanggal_surat' => '2026-09-08',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unitFakultas->id,
        'status_disposisi' => StatusDisposisi::Menunggu,
        'created_by' => $stafTuFakultas->id,
    ]);

    // Request oleh Staf TU Fakultas: hanya boleh melihat surat Fakultas (0011), tidak boleh melihat surat Rektorat (0010)
    $response = $this->actingAs($stafTuFakultas)
        ->get(route('cetak.buku-agenda', [
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-30',
        ]));

    $response->assertOk();
    $response->assertSee('REG-SM/2026/09/0011');
    $response->assertSee('Penerimaan Mahasiswa Magang Industri');
    $response->assertDontSee('REG-SM/2026/09/0010');
    $response->assertDontSee('Daftar Isian Pelaksanaan Anggaran (DIPA)');
});
