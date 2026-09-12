<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\DataMaster\Index as DataMasterIndex;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UnitKerjaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DataMasterLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(UnitKerjaSeeder::class);
    }

    public function test_super_admin_dapat_mengakses_halaman_data_master(): void
    {
        $unit = UnitKerja::first();
        $admin = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $admin->assignRole('super_admin');

        $response = $this->actingAs($admin)->get(route('data-master.index'));

        $response->assertOk();
        $response->assertSee('Data Master');
        $response->assertSee('Data Pegawai');
        $response->assertSee('Nomor Surat');
        $response->assertSee('Data Admin');
    }

    public function test_super_admin_dapat_menambah_dan_menghapus_pegawai(): void
    {
        $unit = UnitKerja::first();
        $admin = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $admin->assignRole('super_admin');

        Livewire::actingAs($admin)
            ->test(DataMasterIndex::class)
            ->call('openPegawaiModal')
            ->set('pegawai_nip', '199001012026011001')
            ->set('pegawai_nama', 'Budi Santoso, M.Kom.')
            ->set('pegawai_jabatan', 'Kepala Laboratorium')
            ->set('pegawai_golongan', 'Penata / III/c')
            ->set('pegawai_unit_id', $unit->id)
            ->call('savePegawai')
            ->assertHasNoErrors()
            ->assertDispatched('show-toast');

        $this->assertDatabaseHas('pegawai', [
            'nip_nidn' => '199001012026011001',
            'nama' => 'Budi Santoso, M.Kom.',
        ]);

        $pegawai = Pegawai::where('nip_nidn', '199001012026011001')->firstOrFail();

        Livewire::actingAs($admin)
            ->test(DataMasterIndex::class)
            ->call('deletePegawai', $pegawai->id)
            ->assertDispatched('show-toast');

        $this->assertDatabaseMissing('pegawai', ['id' => $pegawai->id]);
    }

    public function test_super_admin_dapat_menambah_nomor_surat_dan_backup(): void
    {
        $unit = UnitKerja::first();
        $admin = User::factory()->create(['unit_kerja_id' => $unit->id]);
        $admin->assignRole('super_admin');

        Livewire::actingAs($admin)
            ->test(DataMasterIndex::class)
            ->call('openNomorSuratModal')
            ->set('ns_nomor_surat', '001/TU/IX/2026')
            ->set('ns_jenis_surat', 'Surat Undangan')
            ->set('ns_tanggal_dibuat', '2026-09-12')
            ->set('ns_keterangan', 'Format standar surat undangan dinas')
            ->call('saveNomorSurat')
            ->assertHasNoErrors()
            ->assertDispatched('show-toast');

        $this->assertDatabaseHas('master_nomor_surat', [
            'format_pola' => '001/TU/IX/2026',
            'nama_klasifikasi' => 'Surat Undangan',
        ]);

        Livewire::actingAs($admin)
            ->test(DataMasterIndex::class)
            ->call('backupData')
            ->assertFileDownloaded();

        Livewire::actingAs($admin)
            ->test(DataMasterIndex::class)
            ->call('exportPegawaiCsv')
            ->assertFileDownloaded();

        Livewire::actingAs($admin)
            ->test(DataMasterIndex::class)
            ->call('exportNomorSuratCsv')
            ->assertFileDownloaded();
    }
}
