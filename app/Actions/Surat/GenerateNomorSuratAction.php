<?php

declare(strict_types=1);

namespace App\Actions\Surat;

use App\DTOs\NomorSuratResult;
use App\Models\MasterNomorSurat;
use Illuminate\Support\Facades\DB;

final class GenerateNomorSuratAction
{
    /**
     * Peta konversi angka bulan ke angka romawi naskah dinas.
     *
     * @var array<int, string>
     */
    private const array BULAN_ROMAWI = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    /**
     * Menerbitkan nomor surat dinas urut berikutnya dengan Pessimistic Locking (lockForUpdate).
     */
    public function execute(string $masterNomorId, ?int $tahun = null, ?int $bulan = null): NomorSuratResult
    {
        $tahun = $tahun ?? (int) date('Y');
        $bulan = $bulan ?? (int) date('n');

        return DB::transaction(function () use ($masterNomorId, $tahun, $bulan): NomorSuratResult {
            /** @var MasterNomorSurat $master */
            $master = MasterNomorSurat::query()
                ->where('id', $masterNomorId)
                ->with('unitKerja')
                ->lockForUpdate()
                ->firstOrFail();

            $urutan = $master->nomor_terakhir + 1;
            $master->update([
                'nomor_terakhir' => $urutan,
                'tahun' => $tahun,
            ]);

            $nomorPadded = sprintf('%04d', $urutan);
            $bulanRomawi = self::BULAN_ROMAWI[$bulan] ?? 'I';
            $kodeUnit = $master->unitKerja->kode_unit ?? 'UNIV';

            // Ganti placeholder pada format pola secara case-insensitive
            $replacements = [
                '{nomor}' => $nomorPadded,
                '{NOMOR}' => $nomorPadded,
                '{kode}' => $master->kode_klasifikasi,
                '{KODE}' => $master->kode_klasifikasi,
                '{kode_klasifikasi}' => $master->kode_klasifikasi,
                '{KODE_KLASIFIKASI}' => $master->kode_klasifikasi,
                '{unit}' => $kodeUnit,
                '{UNIT}' => $kodeUnit,
                '{kode_unit}' => $kodeUnit,
                '{KODE_UNIT}' => $kodeUnit,
                '{bulan_romawi}' => $bulanRomawi,
                '{BULAN_ROMAWI}' => $bulanRomawi,
                '{tahun}' => (string) $tahun,
                '{TAHUN}' => (string) $tahun,
            ];

            $nomorSurat = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $master->format_pola
            );

            $nomorAgenda = sprintf(
                'REG-SK/%04d/%02d/%04d',
                $tahun,
                $bulan,
                $urutan
            );

            return new NomorSuratResult(
                nomorSurat: $nomorSurat,
                nomorAgenda: $nomorAgenda,
                urutan: $urutan,
                kodeKlasifikasi: $master->kode_klasifikasi
            );
        });
    }
}
