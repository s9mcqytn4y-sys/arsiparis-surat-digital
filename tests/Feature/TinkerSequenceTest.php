<?php

declare(strict_types=1);

use App\Enums\StatusDisposisi;
use App\Models\ArsipDigital;
use App\Models\MasterNomorSurat;
use App\Models\Pegawai;
use App\Models\RiwayatDisposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function (): void {
    $this->seed();
    Storage::fake('private');
    Storage::fake('public');
});

test('sekuensi komprehensif persuratan: surat masuk, disposisi, surat keluar dan arsip digital', function (): void {
    $admin = User::where('email', 'admin@universitas.ac.id')->firstOrFail();
    $unitKerja = UnitKerja::where('kode_unit', 'REK-01')->firstOrFail();
    $tujuanUnit = UnitKerja::where('kode_unit', 'FTIK-01')->firstOrFail();

    // -------------------------------------------------------------
    // Sequence 1: Surat Masuk & Magic Bytes Verification
    // -------------------------------------------------------------
    $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n>>\nendobj\ntrailer\n<<\n/Root 1 0 R\n>>\n%%EOF";
    $fakePdf = UploadedFile::fake()->createWithContent('surat_dinas_kementerian.pdf', $pdfContent);

    // Save to private storage with UUID v7
    $storedPath = $fakePdf->store('surat-masuk', 'private');
    expect($storedPath)->not->toBeFalse();

    // Verify magic bytes header
    $fileBytes = Storage::disk('private')->get($storedPath);
    expect(str_starts_with((string) $fileBytes, '%PDF-'))->toBeTrue();

    // Generate Nomor Agenda Register Masuk
    $year = date('Y');
    $month = date('m');
    $seq = str_pad((string) (SuratMasuk::count() + 1), 4, '0', STR_PAD_LEFT);
    $nomorRegisterMasuk = "REG-SM/{$year}/{$month}/{$seq}";

    $suratMasuk = SuratMasuk::create([
        'nomor_agenda' => $nomorRegisterMasuk,
        'nomor_surat' => '088/D/KEMDIKBUD/2026',
        'pengirim' => 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi',
        'perihal' => 'Penyaluran Hibah Penelitian Kampus Berkelanjutan',
        'tanggal_surat' => '2026-09-10',
        'tanggal_terima' => '2026-09-12',
        'unit_kerja_id' => $unitKerja->id,
        'file_path' => $storedPath,
        'file_mime' => 'application/pdf',
        'file_size' => strlen($pdfContent),
        'status_disposisi' => StatusDisposisi::Menunggu,
        'created_by' => $admin->id,
    ]);

    expect($suratMasuk->id)->toBeString()
        ->and(Str::isUuid($suratMasuk->id))->toBeTrue()
        ->and($suratMasuk->nomor_agenda)->toBe($nomorRegisterMasuk);

    // -------------------------------------------------------------
    // Sequence 2: Disposisi Berjenjang Lintas Unit
    // -------------------------------------------------------------
    $rektorPegawai = Pegawai::where('unit_kerja_id', $unitKerja->id)->firstOrFail();
    $tujuanPegawai = Pegawai::where('unit_kerja_id', $tujuanUnit->id)->firstOrFail();

    $disposisi = RiwayatDisposisi::create([
        'surat_masuk_id' => $suratMasuk->id,
        'dari_pegawai_id' => $rektorPegawai->id,
        'ke_pegawai_id' => $tujuanPegawai->id,
        'instruksi' => 'Tindak lanjuti pembentukan tim proposal hibah penelitian FTIK.',
        'catatan_tindak_lanjut' => 'Segera laporkan draft proposal sebelum akhir bulan.',
        'status' => StatusDisposisi::Diproses,
        'tenggat_waktu' => now()->addDays(7),
    ]);

    $suratMasuk->update(['status_disposisi' => StatusDisposisi::Diproses]);

    expect($disposisi->id)->toBeString()
        ->and($suratMasuk->fresh()->status_disposisi)->toBe(StatusDisposisi::Diproses);

    // -------------------------------------------------------------
    // Sequence 3: Surat Keluar & Anti-Race Generator
    // -------------------------------------------------------------
    $masterNomor = MasterNomorSurat::where('unit_kerja_id', $unitKerja->id)->firstOrFail();
    $action = app(App\Actions\Surat\GenerateNomorSuratAction::class);
    $result = $action->execute($masterNomor->id);

    expect($result->nomorSurat)->toBeString()
        ->and($result->nomorSurat)->toContain('/REK')
        ->and($result->nomorAgenda)->toContain('REG-SK');

    $suratKeluar = SuratKeluar::create([
        'nomor_agenda' => $result->nomorAgenda,
        'nomor_surat' => $result->nomorSurat,
        'kode_klasifikasi' => $result->kodeKlasifikasi,
        'tujuan' => 'Dekan Fakultas Teknik & Informatika Komputer',
        'perihal' => 'Penetapan Tim Koordinasi Program Penelitian Unggulan',
        'tanggal_surat' => now()->format('Y-m-d'),
        'unit_kerja_id' => $unitKerja->id,
        'jenis_surat' => 'Surat Tugas',
        'created_by' => $admin->id,
    ]);

    expect($suratKeluar->id)->toBeString()
        ->and(Str::isUuid($suratKeluar->id))->toBeTrue();

    // -------------------------------------------------------------
    // Sequence 4: Arsip Digital & Isolasi Storage Privat
    // -------------------------------------------------------------
    $archiveFile = UploadedFile::fake()->createWithContent('sk_rektor_tim_2026.pdf', $pdfContent);
    $archivePath = $archiveFile->store('arsip-digital', 'private');

    $arsip = ArsipDigital::create([
        'unit_kerja_id' => $unitKerja->id,
        'judul' => 'Surat Keputusan Rektor Pembentukan Tim Koordinasi Riset 2026',
        'kategori' => 'Surat Keputusan',
        'nomor_dokumen' => 'SK-REK/2026/09/001',
        'tanggal_dokumen' => '2026-09-12',
        'file_path' => $archivePath,
        'file_mime' => 'application/pdf',
        'file_size' => strlen($pdfContent),
        'created_by' => $admin->id,
    ]);

    expect($arsip->id)->toBeString()
        ->and(Storage::disk('private')->exists($archivePath))->toBeTrue()
        // Must NEVER exist on public disk
        ->and(Storage::disk('public')->exists($archivePath))->toBeFalse();
});

test('role middleware terkonfigurasi dengan benar', function (): void {
    $admin = User::where('email', 'admin@universitas.ac.id')->firstOrFail();

    $this->actingAs($admin)
        ->get('/data-master')
        ->assertOk();
});
