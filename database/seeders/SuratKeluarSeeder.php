<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SuratKeluar;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuratKeluarSeeder extends Seeder
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
                'nomor_agenda' => 'REG-SK/2026/01/0001',
                'nomor_surat' => '001/UDN/REK/I/2026',
                'kode_klasifikasi' => 'UDN/REK',
                'jenis_surat' => 'Surat Keputusan',
                'tujuan' => 'Seluruh Dekan dan Kepala Lembaga',
                'tanggal_surat' => '2026-01-15',
                'perihal' => 'SK Rektor tentang Kalender Akademik Tahun Ajaran 2026/2027',
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'nomor_agenda' => 'REG-SK/2026/02/0002',
                'nomor_surat' => '015/FTIK/UDN/II/2026',
                'kode_klasifikasi' => 'FTIK/UDN',
                'jenis_surat' => 'Surat Tugas',
                'tujuan' => 'Dosen Pendamping Lapangan MBKM',
                'tanggal_surat' => '2026-02-10',
                'perihal' => 'Surat Tugas Pelaksanaan Monitoring Magang Industri Industri Digital',
            ],
            [
                'unit_kerja_id' => $unitRektorat->id,
                'nomor_agenda' => 'REG-SK/2026/03/0003',
                'nomor_surat' => '042/UDN/REK/III/2026',
                'kode_klasifikasi' => 'UDN/REK',
                'jenis_surat' => 'Surat Undangan',
                'tujuan' => 'Kepala LLDIKTI Wilayah VI',
                'tanggal_surat' => '2026-03-20',
                'perihal' => 'Undangan Wisuda Perdana Wisudawan Sarjana dan Magister 2026',
            ],
            [
                'unit_kerja_id' => $unitFTIK->id,
                'nomor_agenda' => 'REG-SK/2026/04/0004',
                'nomor_surat' => '088/FTIK/UDN/IV/2026',
                'kode_klasifikasi' => 'FTIK/UDN',
                'jenis_surat' => 'Surat Pengantar',
                'tujuan' => 'Direktur PT Industri Digital Indonesia',
                'tanggal_surat' => '2026-04-18',
                'perihal' => 'Pengantar Berkas Kerjasama Memorandum of Understanding (MoU)',
            ],
        ];

        foreach ($items as $item) {
            SuratKeluar::firstOrCreate(
                ['nomor_agenda' => $item['nomor_agenda']],
                array_merge($item, [
                    'file_path' => 'documents/sample_surat_keluar.pdf',
                    'file_mime' => 'application/pdf',
                    'file_size' => 153600,
                    'created_by' => $user->id,
                ])
            );
        }
    }
}
