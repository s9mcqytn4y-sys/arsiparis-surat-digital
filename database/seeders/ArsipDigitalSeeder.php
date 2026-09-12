<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ArsipDigital;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArsipDigitalSeeder extends Seeder
{
    public function run(): void
    {
        $unitFTIK = UnitKerja::where('kode_unit', 'FTIK')->first() ?? UnitKerja::first();
        $user = User::first();

        if (! $unitFTIK || ! $user) {
            return;
        }

        $items = [
            [
                'unit_kerja_id' => $unitFTIK->id,
                'judul' => 'Sertifikat Akreditasi Unggul Prodi Teknik Informatika BAN-PT 2026',
                'nomor_dokumen' => '118/BAN-PT/SK/2026',
                'kategori' => 'Dokumen Akreditasi',
                'tanggal_dokumen' => '2026-03-15',
                'deskripsi' => 'Sertifikat resmi akreditasi tingkat unggul untuk jangka waktu 5 tahun.',
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'judul' => 'Kurikulum Operasional Prodi Sistem Informasi Berbasis Kerangka OBE 2026',
                'nomor_dokumen' => 'SK-DEKAN/04/2026',
                'kategori' => 'Kurikulum & Silabus',
                'tanggal_dokumen' => '2026-02-01',
                'deskripsi' => 'Dokumen struktur mata kuliah, capaian pembelajaran, dan silabus resmi.',
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'judul' => 'Memorandum of Agreement (MoA) Kerjasama Riset AI dengan PT Industri Digital',
                'nomor_dokumen' => 'MoA/FTIK/2026/002',
                'kategori' => 'Perjanjian Kerjasama (MoU/MoA)',
                'tanggal_dokumen' => '2026-04-20',
                'deskripsi' => 'Naskah perjanjian tingkat fakultas tentang riset kecerdasan buatan terapan.',
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'judul' => 'Notulen Rapat Senat Fakultas Pembentukan Program Studi Cyber Security',
                'nomor_dokumen' => 'NOTULEN/SENAT/05/2026',
                'kategori' => 'Notulen Rapat',
                'tanggal_dokumen' => '2026-05-10',
                'deskripsi' => 'Berita acara dan daftar hadir rapat pleno senat pembukaan prodi baru.',
            ],
        ];

        foreach ($items as $item) {
            ArsipDigital::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge($item, [
                    'file_path' => 'documents/sample_arsip_digital.pdf',
                    'file_mime' => 'application/pdf',
                    'file_size' => 307200,
                    'created_by' => $user->id,
                ])
            );
        }
    }
}
