<?php

declare(strict_types=1);

namespace App\Livewire\SuratMasuk;

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use App\Enums\StatusDisposisi;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:100')]
    public string $nomorSurat = '';

    #[Validate('required|string|max:200')]
    public string $pengirim = '';

    #[Validate('required|date')]
    public string $tanggalSurat = '';

    #[Validate('required|date')]
    public string $tanggalTerima = '';

    #[Validate('required|string|max:255')]
    public string $perihal = '';

    #[Validate('nullable|string|max:1000')]
    public string $ringkasan = '';

    #[Validate('required|uuid|exists:unit_kerja,id')]
    public string $unitKerjaId = '';

    #[Validate('required|file|mimes:pdf|max:10240')]
    public $berkas = null;

    public function mount(): void
    {
        $this->tanggalTerima = Carbon::now()->format('Y-m-d');
        $this->tanggalSurat = Carbon::now()->format('Y-m-d');

        $user = auth()->user();
        if ($user->unit_kerja_id) {
            $this->unitKerjaId = $user->unit_kerja_id;
        }
    }

    public function simpan(ValidateAndStoreDocumentAction $documentAction): void
    {
        Gate::authorize('create', SuratMasuk::class);

        $this->validate();

        $user = auth()->user();

        // Enforce unit kerja jika user bukan super_admin
        $targetUnitId = $user->hasRole(['super_admin', 'rektor', 'wakil_rektor'])
            ? $this->unitKerjaId
            : (string) $user->unit_kerja_id;

        DB::transaction(function () use ($documentAction, $targetUnitId, $user): void {
            // Generate Nomor Agenda anti-race condition
            $year = Carbon::parse($this->tanggalTerima)->format('Y');
            $month = Carbon::parse($this->tanggalTerima)->format('m');
            $prefix = sprintf('REG-SM/%s/%s/', $year, $month);

            $lastAgenda = SuratMasuk::query()
                ->where('nomor_agenda', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('nomor_agenda')
                ->value('nomor_agenda');

            $nextSequence = 1;
            if ($lastAgenda !== null) {
                $lastNumStr = substr($lastAgenda, strrpos($lastAgenda, '/') + 1);
                $nextSequence = (int) $lastNumStr + 1;
            }

            $nomorAgenda = sprintf('%s%04d', $prefix, $nextSequence);

            // Validasi magic bytes & simpan berkas terenkripsi/privat
            $storedDoc = $documentAction->execute($this->berkas, 'documents');

            SuratMasuk::create([
                'unit_kerja_id' => $targetUnitId,
                'nomor_agenda' => $nomorAgenda,
                'nomor_surat' => $this->nomorSurat,
                'pengirim' => $this->pengirim,
                'tanggal_surat' => $this->tanggalSurat,
                'tanggal_terima' => $this->tanggalTerima,
                'perihal' => $this->perihal,
                'ringkasan' => $this->ringkasan ?: null,
                'status_disposisi' => StatusDisposisi::Menunggu,
                'file_path' => $storedDoc->filePath,
                'file_mime' => $storedDoc->fileMime,
                'file_size' => $storedDoc->fileSize,
                'created_by' => $user->id,
            ]);
        });

        session()->flash('status', 'Naskah surat masuk berhasil dicatat dalam Buku Agenda Digital.');
        $this->redirect(route('surat-masuk.index'), navigate: true);
    }

    public function render(): View
    {
        Gate::authorize('create', SuratMasuk::class);

        $daftarUnit = UnitKerja::orderBy('nama_unit')->get(['id', 'nama_unit', 'kode_unit']);

        return view('livewire.surat-masuk.create', [
            'daftarUnit' => $daftarUnit,
        ]);
    }
}
