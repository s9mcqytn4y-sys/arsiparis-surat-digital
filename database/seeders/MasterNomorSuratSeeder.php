<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MasterNomorSurat;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class MasterNomorSuratSeeder extends Seeder
{
    public function run(): void
    {
        $rektorat = UnitKerja::where('kode_unit', 'REK-01')->firstOrFail();
        $biroAak = UnitKerja::where('kode_unit', 'BAAK-01')->firstOrFail();
        $fakultasTeknik = UnitKerja::where('kode_unit', 'FTIK-01')->firstOrFail();
        $prodiTi = UnitKerja::where('kode_unit', 'TI-01')->firstOrFail();
        $currentYear = (int) date('Y');

        $data = [
            // Rektorat
            [
                'unit_kerja_id' => $rektorat->id,
                'kode_klasifikasi' => 'KP.01',
                'nama_klasifikasi' => 'Kepegawaian & Ketenagaan',
                'format_pola' => '{NOMOR}/UNIV/REK-KP/{BULAN_ROMAWI}/{TAHUN}',
                'nomor_terakhir' => 0,
                'tahun' => $currentYear,
                'is_active' => true,
            ],
            [
                'unit_kerja_id' => $rektorat->id,
                'kode_klasifikasi' => 'HM.02',
                'nama_klasifikasi' => 'Keputusan Rektor / Legalitas',
                'format_pola' => '{NOMOR}/UNIV/SK-REK/{BULAN_ROMAWI}/{TAHUN}',
                'nomor_terakhir' => 0,
                'tahun' => $currentYear,
                'is_active' => true,
            ],
            // Biro AAK
            [
                'unit_kerja_id' => $biroAak->id,
                'kode_klasifikasi' => 'AK.01',
                'nama_klasifikasi' => 'Administrasi Akademik',
                'format_pola' => '{NOMOR}/BAAK/AK/{BULAN_ROMAWI}/{TAHUN}',
                'nomor_terakhir' => 0,
                'tahun' => $currentYear,
                'is_active' => true,
            ],
            // FTIK
            [
                'unit_kerja_id' => $fakultasTeknik->id,
                'kode_klasifikasi' => 'TU.01',
                'nama_klasifikasi' => 'Tata Usaha Umum Fakultas',
                'format_pola' => '{NOMOR}/FTIK/TU/{BULAN_ROMAWI}/{TAHUN}',
                'nomor_terakhir' => 0,
                'tahun' => $currentYear,
                'is_active' => true,
            ],
            // Prodi TI
            [
                'unit_kerja_id' => $prodiTi->id,
                'kode_klasifikasi' => 'PD.01',
                'nama_klasifikasi' => 'Surat Pengantar Sidang & Ujian',
                'format_pola' => '{NOMOR}/FTIK/TI-PD/{BULAN_ROMAWI}/{TAHUN}',
                'nomor_terakhir' => 0,
                'tahun' => $currentYear,
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            MasterNomorSurat::create($item);
        }
    }
}
