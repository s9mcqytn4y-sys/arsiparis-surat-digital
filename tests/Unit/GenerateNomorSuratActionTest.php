<?php

declare(strict_types=1);

use App\Actions\Surat\GenerateNomorSuratAction;
use App\Enums\LevelUnitKerja;
use App\Models\MasterNomorSurat;
use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('konversi bulan romawi dan penomoran surat urut dengan kunci pesimistik berjalan presisi', function () {
    $action = app(GenerateNomorSuratAction::class);

    $unit = UnitKerja::create([
        'kode_unit' => 'FTIK',
        'nama_unit' => 'Fakultas Teknologi Informasi dan Komunikasi',
        'level_unit' => LevelUnitKerja::FAKULTAS,
    ]);

    $master = MasterNomorSurat::create([
        'unit_kerja_id' => $unit->id,
        'kode_klasifikasi' => 'B.01',
        'nama_klasifikasi' => 'Surat Keputusan Dekan',
        'format_pola' => '{NOMOR}/FTIK/B/{BULAN_ROMAWI}/{TAHUN}',
        'nomor_terakhir' => 45,
        'tahun' => 2026,
        'is_active' => true,
    ]);

    // Uji tanggal September (Bulan 9 = IX)
    $result = $action->execute($master->id, 2026, 9);

    expect($result->urutan)->toBe(46)
        ->and($result->nomorSurat)->toBe('0046/FTIK/B/IX/2026')
        ->and($result->nomorAgenda)->toBe('REG-SK/2026/09/0046');

    $master->refresh();
    expect($master->nomor_terakhir)->toBe(46);

    // Iterasi kedua pada master yang sama
    $result2 = $action->execute($master->id, 2026, 9);
    expect($result2->urutan)->toBe(47)
        ->and($result2->nomorSurat)->toBe('0047/FTIK/B/IX/2026');
});
