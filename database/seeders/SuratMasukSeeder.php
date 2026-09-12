<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StatusDisposisi;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SuratMasukSeeder extends Seeder
{
    public function run(): void
    {
        $unitRektorat = UnitKerja::where('kode_unit', 'REK')->first() ?? UnitKerja::first();
        $unitFTIK = UnitKerja::where('kode_unit', 'FTIK')->first() ?? UnitKerja::first();
        $user = User::first();

        if (! $unitRektorat || ! $user) {
            return;
        }

        $items = [
            [
                'unit_kerja_id' => $unitRektorat->id,
                'nomor_agenda' => 'REG-SM/2026/01/0001',
                'nomor_surat' => '102/B/DIKTI/I/2026',
                'pengirim' => 'Ditjen Pendidikan Tinggi Kemendikbudristek',
                'tanggal_surat' => '2026-01-10',
                'tanggal_terima' => '2026-01-12',
                'perihal' => 'Undangan Monitoring Kearsipan Digital dan Akreditasi Perguruan Tinggi',
                'status_disposisi' => StatusDisposisi::Selesai,
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'nomor_agenda' => 'REG-SM/2026/02/0002',
                'nomor_surat' => '045/LL6/KL/2026',
                'pengirim' => 'LLDIKTI Wilayah VI Jawa Tengah',
                'tanggal_surat' => '2026-02-05',
                'tanggal_terima' => '2026-02-07',
                'perihal' => 'Pemberitahuan Program Beasiswa Unggulan Dosen dan Tenaga Kependidikan',
                'status_disposisi' => StatusDisposisi::Selesai,
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'nomor_agenda' => 'REG-SM/2026/03/0003',
                'nomor_surat' => '118/BAN-PT/SK/2026',
                'pengirim' => 'Badan Akreditasi Nasional Perguruan Tinggi (BAN-PT)',
                'tanggal_surat' => '2026-03-15',
                'tanggal_terima' => '2026-03-18',
                'perihal' => 'Penyampaian Sertifikat Akreditasi Unggul Program Studi Teknik Informatika',
                'status_disposisi' => StatusDisposisi::Selesai,
            ],
            [
                'unit_kerja_id' => $unitRektorat->id,
                'nomor_agenda' => 'REG-SM/2026/04/0004',
                'nomor_surat' => '089/PEMPROV/IV/2026',
                'pengirim' => 'Pemerintah Provinsi Jawa Tengah',
                'tanggal_surat' => '2026-04-02',
                'tanggal_terima' => '2026-04-04',
                'perihal' => 'Tawaran Kerjasama Riset Pengabdian Masyarakat Berbasis Teknologi Informasi',
                'status_disposisi' => StatusDisposisi::Diproses,
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'nomor_agenda' => 'REG-SM/2026/05/0005',
                'nomor_surat' => '210/PT-IND/V/2026',
                'pengirim' => 'PT Industri Digital Indonesia',
                'tanggal_surat' => '2026-05-12',
                'tanggal_terima' => '2026-05-14',
                'perihal' => 'Permohonan Alokasi Mahasiswa Magang MBKM Semester Gasal 2026/2027',
                'status_disposisi' => StatusDisposisi::Menunggu,
            ],
        ];

        foreach ($items as $item) {
            SuratMasuk::firstOrCreate(
                ['nomor_agenda' => $item['nomor_agenda']],
                array_merge($item, [
                    'file_path' => 'documents/sample_surat_masuk.pdf',
                    'file_mime' => 'application/pdf',
                    'file_size' => 204800,
                    'created_by' => $user->id,
                ])
            );
        }
    }
}
