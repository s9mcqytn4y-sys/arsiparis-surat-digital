<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Laporan\Index;
use App\Livewire\Pengaturan\Dokumen;
use App\Models\PengaturanDokumen;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\PengaturanDokumenSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LaporanPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(UnitKerjaSeeder::class);
        $this->seed(PengaturanDokumenSeeder::class);
    }

    public function test_pengguna_terotentikasi_dapat_mengakses_halaman_laporan(): void
    {
        $unit = UnitKerja::first();
        $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $user->assignRole('petugas_tu');

        $response = $this->actingAs($user)->get(route('laporan.index'));

        $response->assertOk();
        $response->assertSee('Laporan');
    }

    public function test_komponen_livewire_laporan_menghitung_metrik_dan_rekapitulasi(): void
    {
        $unit = UnitKerja::first();
        $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $user->assignRole('petugas_tu');

        SuratMasuk::create([
            'unit_kerja_id' => $unit->id,
            'nomor_agenda' => 'REG-SM/2026/09/0001',
            'nomor_surat' => '100/SM/2026',
            'pengirim' => 'Kemendikbudristek',
            'tanggal_surat' => '2026-09-10',
            'tanggal_terima' => '2026-09-12',
            'perihal' => 'Undangan Monitoring Kearsipan Digital',
            'file_path' => 'documents/sample.pdf',
            'created_by' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('tampilkanLaporan')
            ->assertSet('totalSuratMasuk', 1)
            ->assertSet('totalDokumen', 1)
            ->assertSee('Undangan Monitoring Kearsipan Digital');
    }

    public function test_ekspor_excel_menghasilkan_stream_csv(): void
    {
        $unit = UnitKerja::first();
        $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $user->assignRole('petugas_tu');

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('exportExcel')
            ->assertFileDownloaded();
    }

    public function test_halaman_cetak_laporan_memuat_kop_surat_resmi(): void
    {
        $unit = UnitKerja::first();
        $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $user->assignRole('petugas_tu');

        $response = $this->actingAs($user)->get(route('laporan.cetak'));

        $response->assertOk();
        $response->assertSee('Universitas Digital Nusantara');
        $response->assertSee('LAPORAN REKAPITULASI SURAT DINAS');
    }

    public function test_pengaturan_dokumen_dapat_diperbarui_melalui_livewire(): void
    {
        $unit = UnitKerja::first();
        $user = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $user->assignRole('super_admin');

        Livewire::actingAs($user)
            ->test(Dokumen::class)
            ->set('nama_institusi', 'Universitas Digital Indonesia')
            ->set('alamat_lengkap', 'Jl. Merdeka No. 45, Semarang')
            ->set('kota_penerbitan', 'Semarang')
            ->call('simpan')
            ->assertHasNoErrors()
            ->assertDispatched('show-toast');

        $setting = PengaturanDokumen::getAktif();
        $this->assertEquals('Universitas Digital Indonesia', $setting->nama_institusi);
    }
}
