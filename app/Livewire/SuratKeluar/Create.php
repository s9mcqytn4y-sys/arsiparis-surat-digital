<?php

declare(strict_types=1);

namespace App\Livewire\SuratKeluar;

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use App\Actions\Surat\GenerateNomorSuratAction;
use App\Models\MasterNomorSurat;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
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

    #[Validate('required|uuid|exists:master_nomor_surat,id')]
    public string $masterNomorSuratId = '';

    #[Validate('required|uuid|exists:pegawai,id')]
    public string $penandatanganId = '';

    #[Validate('required|string|max:255')]
    public string $tujuanSurat = '';

    #[Validate('required|date')]
    public string $tanggalSurat = '';

    #[Validate('required|string|max:255')]
    public string $perihal = '';

    #[Validate('nullable|file|mimes:pdf|max:10240')]
    public $berkas = null;

    public function mount(): void
    {
        $this->tanggalSurat = Carbon::now()->format('Y-m-d');

        $user = auth()->user();
        if ($user->unit_kerja_id) {
            $master = MasterNomorSurat::where('unit_kerja_id', $user->unit_kerja_id)->first();
            if ($master) {
                $this->masterNomorSuratId = $master->id;
            }
        }
    }

    public function simpan(
        GenerateNomorSuratAction $nomorAction,
        ValidateAndStoreDocumentAction $documentAction
    ): void {
        Gate::authorize('create', SuratKeluar::class);

        $this->validate();

        $user = auth()->user();
        $master = MasterNomorSurat::with('unitKerja')->findOrFail($this->masterNomorSuratId);

        DB::transaction(function () use ($master, $nomorAction, $documentAction, $user): void {
            $date = Carbon::parse($this->tanggalSurat);
            $nomorResult = $nomorAction->execute(
                masterNomorId: $master->id,
                tahun: (int) $date->format('Y'),
                bulan: (int) $date->format('n')
            );

            $filePath = null;
            $fileMime = null;
            $fileSize = null;

            if ($this->berkas !== null) {
                $storedDoc = $documentAction->execute($this->berkas, 'documents');
                $filePath = $storedDoc->filePath;
                $fileMime = $storedDoc->mimeType;
                $fileSize = $storedDoc->sizeBytes;
            }

            SuratKeluar::create([
                'unit_kerja_id' => $master->unit_kerja_id,
                'nomor_agenda' => $nomorResult->nomorAgenda,
                'nomor_surat' => $nomorResult->nomorSurat,
                'kode_klasifikasi' => $nomorResult->kodeKlasifikasi,
                'perihal' => $this->perihal,
                'tujuan' => $this->tujuanSurat,
                'tanggal_surat' => $this->tanggalSurat,
                'penandatangan_id' => $this->penandatanganId,
                'file_path' => $filePath,
                'file_mime' => $fileMime,
                'file_size' => $fileSize,
                'created_by' => $user->id,
            ]);
        });

        session()->flash('status', 'Naskah surat keluar berhasil diterbitkan dengan nomor resmi.');
        $this->redirect(route('surat-keluar.index'), navigate: true);
    }

    public function render(): View
    {
        Gate::authorize('create', SuratKeluar::class);

        $user = auth()->user();
        $masterQuery = MasterNomorSurat::with('unitKerja')->where('is_active', true);

        if (! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']) && $user->unit_kerja_id) {
            $masterQuery->where('unit_kerja_id', $user->unit_kerja_id);
        }

        $pejabatList = Pegawai::where('is_active', true)->orderBy('nama')->get(['id', 'nama', 'nip_nidn', 'jabatan']);

        return view('livewire.surat-keluar.create', [
            'masterNomorList' => $masterQuery->get(),
            'pejabatList' => $pejabatList,
        ]);
    }
}
