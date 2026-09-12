<?php

declare(strict_types=1);

namespace App\Livewire\SuratMasuk;

use App\Actions\Disposisi\DelegasikanSuratAction;
use App\Enums\StatusDisposisi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DisposisiModal extends Component
{
    public ?string $suratId = null;

    #[Validate('required|uuid|exists:pegawai,id')]
    public string $disposisiKepada = '';

    #[Validate('required|string|max:1000')]
    public string $instruksiDisposisi = '';

    #[Validate('required|string')]
    public string $statusDisposisi = StatusDisposisi::Diproses->value;

    public function mount(?string $suratId = null): void
    {
        $this->suratId = $suratId;

        if ($this->suratId) {
            $surat = SuratMasuk::findOrFail($this->suratId);
            $this->disposisiKepada = (string) ($surat->disposisi_kepada ?? '');
            $this->instruksiDisposisi = $surat->instruksi_disposisi ?? '';
            $this->statusDisposisi = $surat->status_disposisi->value;
        }
    }

    public function simpanDisposisi(DelegasikanSuratAction $delegasikanAction): void
    {
        $surat = SuratMasuk::findOrFail($this->suratId);
        Gate::authorize('update', $surat);

        $this->validate();

        $user = auth()->user();
        $dariPegawai = $user->pegawai ?? Pegawai::where('unit_kerja_id', $user->unit_kerja_id)->first();

        $kePegawai = Pegawai::findOrFail($this->disposisiKepada);

        if ($dariPegawai !== null) {
            $delegasikanAction->execute(
                $surat,
                $dariPegawai,
                $kePegawai,
                $this->instruksiDisposisi
            );
        } else {
            $surat->update([
                'disposisi_kepada' => $this->disposisiKepada,
                'instruksi_disposisi' => $this->instruksiDisposisi,
                'status_disposisi' => StatusDisposisi::Diproses,
            ]);
        }

        session()->flash('status', 'Pendelegasian disposisi naskah dinas berhasil dicatat.');
        $this->dispatch('disposisi-tersimpan');
    }

    public function render(): View
    {
        $surat = $this->suratId ? SuratMasuk::with(['unitKerja'])->find($this->suratId) : null;
        $pejabatList = Pegawai::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip_nidn', 'jabatan']);

        return view('livewire.surat-masuk.disposisi-modal', [
            'surat' => $surat,
            'pejabatList' => $pejabatList,
            'statuses' => StatusDisposisi::cases(),
        ]);
    }
}
