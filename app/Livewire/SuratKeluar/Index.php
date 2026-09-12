<?php

declare(strict_types=1);

namespace App\Livewire\SuratKeluar;

use App\Models\SuratKeluar;
use App\Models\UnitKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
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
    public string $unitId = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedUnitId(): void
    {
        $this->resetPage();
    }

    public function hapus(string $id): void
    {
        $surat = SuratKeluar::findOrFail($id);
        Gate::authorize('delete', $surat);

        $surat->delete();
        session()->flash('status', 'Naskah surat keluar berhasil dihapus dari repositori dinas.');
    }

    public function render(): View
    {
        Gate::authorize('viewAny', SuratKeluar::class);

        $user = auth()->user();

        $query = SuratKeluar::query()
            ->with(['unitKerja', 'penandatangan', 'pembuat'])
            ->latest('tanggal_surat');

        // Scoping per unit kerja bila bukan super_admin / rektorat
        if (! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']) && $user->unit_kerja_id) {
            $query->where('unit_kerja_id', $user->unit_kerja_id);
        } elseif ($this->unitId !== '') {
            $query->where('unit_kerja_id', $this->unitId);
        }

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('nomor_register', 'like', $term)
                    ->orWhere('nomor_surat', 'like', $term)
                    ->orWhere('tujuan_surat', 'like', $term)
                    ->orWhere('perihal', 'like', $term);
            });
        }

        return view('livewire.surat-keluar.index', [
            'daftarSurat' => $query->paginate(10),
            'daftarUnit' => UnitKerja::orderBy('nama_unit')->get(['id', 'nama_unit', 'kode_unit']),
        ]);
    }
}
