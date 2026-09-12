<?php

declare(strict_types=1);

use App\Models\ArsipDigital;
use App\Models\MasterOpsi;
use App\Models\PengaturanDokumen;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;

beforeEach(function (): void {
    $this->seed();
});

test('sekuensi url dan master opsi bekerja dengan presisi', function () {
    // Sequence 1: Dynamic Master Options
    MasterOpsi::simpanJikaBaru('jenis_surat', 'Surat Izin Penelitian');
    $opsiSurat = MasterOpsi::getOpsi('jenis_surat');
    expect($opsiSurat)->toContain('Surat Izin Penelitian');

    MasterOpsi::simpanJikaBaru('kategori_arsip', 'Dokumen Sertifikasi ISO');
    $opsiKategori = MasterOpsi::getOpsi('kategori_arsip');
    expect($opsiKategori)->toContain('Dokumen Sertifikasi ISO');

    // Sequence 2: Pengaturan Dokumen
    $setting = PengaturanDokumen::getAktif();
    expect($setting->nama_institusi)->toBe('Universitas Digital Nusantara');

    // Sequence 3: Arsip Digital CRUD & Scoping
    $admin = User::where('email', 'admin@universitas.ac.id')->firstOrFail();
    $arsip = ArsipDigital::create([
        'unit_kerja_id' => $admin->unit_kerja_id,
        'judul' => 'Sertifikat Akreditasi Unggul FTIK 2026',
        'kategori' => 'Dokumen Akreditasi',
        'nomor_dokumen' => 'AKR-2026/FTIK',
        'tanggal_dokumen' => '2026-09-12',
        'file_path' => 'arsip-digital/sample-akreditasi.pdf',
        'file_mime' => 'application/pdf',
        'file_size' => 1024500,
        'created_by' => $admin->id,
    ]);

    expect($arsip->id)->not->toBeNull()
        ->and(ArsipDigital::count())->toBeGreaterThanOrEqual(1);

    // Sequence 4: Realistic Seeders Populated
    expect(SuratMasuk::count())->toBeGreaterThan(0)
        ->and(SuratKeluar::count())->toBeGreaterThan(0);
});

test('role middleware terkonfigurasi dengan benar', function () {
    $admin = User::where('email', 'admin@universitas.ac.id')->firstOrFail();

    $this->actingAs($admin)
        ->get('/data-master')
        ->assertOk();
});
