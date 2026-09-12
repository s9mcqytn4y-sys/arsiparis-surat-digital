<?php

declare(strict_types=1);

namespace App\Livewire\SuratMasuk;

use App\Enums\StatusDisposisi;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $unitId = '';

    public ?string $selectedSuratId = null;

    public bool $showDisposisiModal = false;

    public bool $showExportModal = false;

    public function bukaExport(): void
    {
        $this->showExportModal = true;
    }

    #[On('tutup-export-modal')]
    public function tutupExport(): void
    {
        $this->showExportModal = false;
    }

    #[On('disposisi-tersimpan')]
    public function onDisposisiTersimpan(): void
    {
        $this->tutupDisposisi();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedUnitId(): void
    {
        $this->resetPage();
    }

    public function bukaDisposisi(string $suratId): void
    {
        $surat = SuratMasuk::findOrFail($suratId);
        Gate::authorize('update', $surat);

        $this->selectedSuratId = $suratId;
        $this->showDisposisiModal = true;
    }

    public function tutupDisposisi(): void
    {
        $this->showDisposisiModal = false;
        $this->selectedSuratId = null;
    }

    public function hapus(string $id): void
    {
        $surat = SuratMasuk::findOrFail($id);
        Gate::authorize('delete', $surat);

        $surat->delete();
        session()->flash('status', 'Naskah surat masuk berhasil dihapus dari sistem kearsipan.');
    }

    public function render(): View
    {
        Gate::authorize('viewAny', SuratMasuk::class);

        $user = auth()->user();

        $query = SuratMasuk::query()
            ->with(['unitKerja', 'disposisiPegawai', 'pembuat'])
            ->latest('tanggal_terima');

        // Scoping per unit kerja bila bukan super_admin / pimpinan rektorat
        if (! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']) && $user->unit_kerja_id) {
            $query->where('unit_kerja_id', $user->unit_kerja_id);
        } elseif ($this->unitId !== '') {
            $query->where('unit_kerja_id', $this->unitId);
        }

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('nomor_agenda', 'like', $term)
                    ->orWhere('nomor_surat', 'like', $term)
                    ->orWhere('pengirim', 'like', $term)
                    ->orWhere('perihal', 'like', $term);
            });
        }

        if ($this->status !== '') {
            $query->where('status_disposisi', $this->status);
        }

        return view('livewire.surat-masuk.index', [
            'daftarSurat' => $query->paginate(10),
            'daftarUnit' => UnitKerja::orderBy('nama_unit')->get(['id', 'nama_unit', 'kode_unit']),
            'statuses' => StatusDisposisi::cases(),
        ]);
    }
}
