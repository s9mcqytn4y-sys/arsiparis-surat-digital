<?php

declare(strict_types=1);

use App\Enums\LevelUnitKerja;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

test('akses streaming dokumen tanpa tanda tangan URL yang sah ditolak dengan status 403', function () {
    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);

    $surat = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0001',
        'nomor_surat' => '001/REK/IX/2026',
        'pengirim' => 'Rektorat Universitas',
        'perihal' => 'Undangan Rapat Kerja',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'file_path' => 'documents/test.pdf',
        'created_by' => $user->id,
    ]);

    // Akses langsung tanpa signature HMAC
    $response = $this->actingAs($user)->get("/documents/stream/{$surat->id}");

    $response->assertStatus(403);
});

test('akses streaming lintas unit kerja ditolak oleh Gate Policy (Anti-BOLA/IDOR)', function () {
    Storage::fake('local');
    Storage::disk('local')->put('documents/test.pdf', '%PDF-1.4 dummy');

    $unitA = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $unitB = UnitKerja::create([
        'kode_unit' => 'FEB',
        'nama_unit' => 'Fakultas Ekonomi dan Bisnis',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $userA = User::factory()->create(['unit_kerja_id' => $unitA->id]);
    $userB = User::factory()->create(['unit_kerja_id' => $unitB->id]);

    // Surat milik Unit B
    $suratUnitB = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0002',
        'nomor_surat' => '002/FEB/IX/2026',
        'pengirim' => 'Dekanat FEB',
        'perihal' => 'Pengajuan Anggaran FEB',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unitB->id,
        'file_path' => 'documents/test.pdf',
        'created_by' => $userB->id,
    ]);

    // Generate signed URL
    $signedUrl = URL::temporarySignedRoute(
        'documents.stream',
        now()->addMinutes(15),
        ['document' => $suratUnitB->id]
    );

    // User A mencoba mengakses surat milik Unit B
    $response = $this->actingAs($userA)->get($signedUrl);

    $response->assertStatus(403);
});

test('staf unit yang berhak dengan Signed URL valid berhasil menerima stream PDF terproteksi', function () {
    Storage::fake('local');
    $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Title (Dokumen Sah) >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
    Storage::disk('local')->put('documents/sah.pdf', $pdfContent);

    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $user = User::factory()->create(['unit_kerja_id' => $unit->id]);

    $surat = SuratMasuk::create([
        'nomor_agenda' => 'REG-SM/2026/09/0003',
        'nomor_surat' => '003/FTIK/IX/2026',
        'pengirim' => 'Prodi TI',
        'perihal' => 'Laporan Akreditasi',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unit->id,
        'file_path' => 'documents/sah.pdf',
        'created_by' => $user->id,
    ]);

    $signedUrl = URL::temporarySignedRoute(
        'documents.stream',
        now()->addMinutes(15),
        ['document' => $surat->id]
    );

    $response = $this->actingAs($user)->get($signedUrl);

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
});
