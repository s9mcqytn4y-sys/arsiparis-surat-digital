<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\MasterOpsi;
use Livewire\Attributes\On;

trait HasSmartOpsi
{
    /**
     * Dapatkan daftar opsi terurut untuk tipe tertentu.
     *
     * @return list<string>
     */
    public function getSmartOpsi(string $tipe): array
    {
        $unitId = auth()->user()?->unit_kerja_id;

        return MasterOpsi::getOpsi($tipe, $unitId);
    }

    /**
     * Simpan nilai kustom baru ke master opsi jika diinputkan pengguna.
     */
    public function simpanOpsiKustom(string $tipe, string $nilai): void
    {
        $user = auth()->user();
        $unitId = $user?->unit_kerja_id;

        MasterOpsi::simpanJikaBaru($tipe, $nilai, $unitId, $user?->id);
    }

    /**
     * Listener SweetAlert2 untuk menghapus opsi spesifik dari database master_opsi.
     */
    #[On('hapus-master-opsi')]
    public function hapusMasterOpsi(string $id): void
    {
        $opsi = MasterOpsi::find($id);
        if ($opsi) {
            $nama = $opsi->nama;
            $opsi->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Opsi "'.$nama.'" berhasil dihapus.');
        }
    }
}
