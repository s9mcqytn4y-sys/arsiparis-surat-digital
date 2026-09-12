<?php

declare(strict_types=1);

namespace App\Livewire\SuratMasuk;

use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ExportModal extends Component
{
    #[Validate('required|date')]
    public string $startDate = '';

    #[Validate('required|date|after_or_equal:startDate')]
    public string $endDate = '';

    public string $unitId = '';

    #[Validate('required|in:print,pdf,csv')]
    public string $format = 'pdf';

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->toDateString();

        $user = auth()->user();
        if (! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']) && $user->unit_kerja_id) {
            $this->unitId = $user->unit_kerja_id;
        }
    }

    public function export(): mixed
    {
        Gate::authorize('viewAny', SuratMasuk::class);

        $this->validate();

        $params = [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];

        if ($this->unitId !== '') {
            $params['unit_id'] = $this->unitId;
        }

        if ($this->format !== 'print') {
            $params['export'] = $this->format;
        }

        return redirect()->route('cetak.buku-agenda', $params);
    }

    public function render(): View
    {
        $user = auth()->user();
        $daftarUnit = $user->hasRole(['super_admin', 'rektor', 'wakil_rektor'])
            ? UnitKerja::orderBy('nama_unit')->get(['id', 'nama_unit', 'kode_unit'])
            : collect();

        return view('livewire.surat-masuk.export-modal', [
            'daftarUnit' => $daftarUnit,
            'isUnitLocked' => ! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']),
        ]);
    }
}
