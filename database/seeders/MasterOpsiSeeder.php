<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MasterOpsi;
use Illuminate\Database\Seeder;

class MasterOpsiSeeder extends Seeder
{
    public function run(): void
    {
        $jenisSuratDefaults = [
            'Surat Undangan',
            'Surat Tugas',
            'Surat Keputusan',
            'Surat Edaran',
            'Surat Permohonan',
            'Surat Keterangan',
            'Nota Dinas',
            'Surat Pengumuman',
        ];

        foreach ($jenisSuratDefaults as $nama) {
            MasterOpsi::simpanJikaBaru('jenis_surat', $nama);
        }

        $kategoriArsipDefaults = [
            'Surat Keputusan Rektor',
            'Surat Keputusan Dekan',
            'Dokumen Akreditasi',
            'Kurikulum & Silabus',
            'Perjanjian Kerjasama (MoU/MoA)',
            'Arsip Kepegawaian',
            'Laporan Keuangan',
            'Notulen Rapat',
        ];

        foreach ($kategoriArsipDefaults as $nama) {
            MasterOpsi::simpanJikaBaru('kategori_arsip', $nama);
        }

        $pengirimDefaults = [
            'Kemendikbudristek Ditjen Dikti',
            'Lembaga Layanan Dikti (LLDIKTI) Region VI',
            'Dinas Pendidikan & Kebudayaan',
            'Rektorat Universitas',
            'Fakultas Teknologi Informasi dan Komunikasi',
            'Badan Akreditasi Nasional (BAN-PT)',
            'Mitra Industri / Perusahaan',
        ];

        foreach ($pengirimDefaults as $nama) {
            MasterOpsi::simpanJikaBaru('pengirim_surat', $nama);
        }

        $tujuanDefaults = [
            'Seluruh Dosen & Tenaga Kependidikan',
            'Dekan FTIK',
            'Wakil Rektor Bidang Akademik',
            'Ketua Program Studi',
            'Kepala Bagian Tata Usaha',
            'Mahasiswa Peserta Yudisium',
        ];

        foreach ($tujuanDefaults as $nama) {
            MasterOpsi::simpanJikaBaru('tujuan_surat', $nama);
        }
    }
}
