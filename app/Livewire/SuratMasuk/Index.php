<?php

declare(strict_types=1);

namespace App\Livewire\SuratMasuk;

use App\Enums\StatusDisposisi;
use App\Models\MasterOpsi;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url as UrlParam;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads, WithPagination;

    #[UrlParam(as: 'q')]
    public string $search = '';

    #[UrlParam(as: 'tgl')]
    public string $filterTanggal = '';

    // Modal Form State (Tambah & Edit)
    public bool $showFormModal = false;

    public bool $isEditing = false;

    public ?string $editId = null;

    // Form fields
    public string $nomor_surat = '';

    public string $tanggal_surat = '';

    public string $pengirim = '';

    public string $pengirim_manual = '';

    public bool $isCustomPengirim = false;

    public string $perihal = '';

    public mixed $file_surat = null;

    public ?string $existing_file_name = null;

    // Modal Preview PDF
    public bool $showPreviewModal = false;

    public ?string $previewUrl = null;

    public ?string $previewJudul = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterTanggal(): void
    {
        $this->resetPage();
    }

    public function updatedPengirim(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomPengirim = true;
        } else {
            $this->isCustomPengirim = false;
        }
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterTanggal = '';
        $this->resetPage();
    }

    public function bukaTambahModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->tanggal_surat = date('Y-m-d');

        $opsi = MasterOpsi::getOpsi('pengirim_surat', auth()->user()->unit_kerja_id);
        if (! empty($opsi)) {
            $this->pengirim = $opsi[0];
        }

        $this->showFormModal = true;
    }

    public function bukaEditModal(string $id): void
    {
        $surat = SuratMasuk::findOrFail($id);
        Gate::authorize('update', $surat);

        $this->resetForm();
        $this->isEditing = true;
        $this->editId = $surat->id;
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');

        $opsi = MasterOpsi::getOpsi('pengirim_surat', auth()->user()->unit_kerja_id);
        $valPengirim = $surat->pengirim ?? '';
        if (in_array($valPengirim, $opsi, true)) {
            $this->pengirim = $valPengirim;
            $this->isCustomPengirim = false;
        } else {
            $this->pengirim = '__custom__';
            $this->pengirim_manual = $valPengirim;
            $this->isCustomPengirim = true;
        }

        $this->perihal = $surat->perihal;
        $this->existing_file_name = $surat->file_path ? basename($surat->file_path) : null;
        $this->showFormModal = true;
    }

    public function tutupModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->editId = null;
        $this->nomor_surat = '';
        $this->tanggal_surat = '';
        $this->pengirim = '';
        $this->pengirim_manual = '';
        $this->isCustomPengirim = false;
        $this->perihal = '';
        $this->file_surat = null;
        $this->existing_file_name = null;
    }

    public function simpan(): void
    {
        $finalPengirim = $this->pengirim;
        if ($this->pengirim === '__custom__' || $this->isCustomPengirim) {
            $finalPengirim = trim($this->pengirim_manual);
        }

        $rules = [
            'nomor_surat' => ['required', 'string', 'max:100'],
            'tanggal_surat' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:1000'],
            'file_surat' => [$this->isEditing ? 'nullable' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];

        if ($this->pengirim === '__custom__' || $this->isCustomPengirim) {
            $rules['pengirim_manual'] = ['required', 'string', 'max:255'];
        } else {
            $rules['pengirim'] = ['required', 'string', 'max:255'];
        }

        $messages = [
            'nomor_surat.required' => 'Nomor surat dinas wajib diisi.',
            'tanggal_surat.required' => 'Tanggal surat dinas wajib diisi.',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid.',
            'pengirim.required' => 'Asal pengirim surat wajib diisi.',
            'pengirim_manual.required' => 'Asal pengirim surat secara manual wajib diisi.',
            'perihal.required' => 'Perihal surat dinas wajib diisi.',
            'file_surat.mimes' => 'Berkas lampiran wajib berformat PDF.',
            'file_surat.max' => 'Ukuran berkas lampiran maksimal 10 Megabyte.',
        ];

        $validated = $this->validate($rules, $messages);

        if ($finalPengirim === '') {
            $this->addError('pengirim', 'Asal pengirim surat tidak boleh kosong.');

            return;
        }

        // Magic bytes validation for PDF
        if ($this->file_surat) {
            $realPath = $this->file_surat->getRealPath();
            $handle = fopen($realPath, 'rb');
            if ($handle !== false) {
                $header = fread($handle, 5);
                fclose($handle);
                if ($header !== '%PDF-') {
                    $this->addError('file_surat', 'Berkas bukan PDF valid (Magic bytes ditolak).');

                    return;
                }
            }
        }

        $user = auth()->user();
        $unitId = $user->unit_kerja_id ?? UnitKerja::first()?->id;

        // Auto save new sender to MasterOpsi
        MasterOpsi::simpanJikaBaru('pengirim_surat', $finalPengirim, $unitId, $user->id);

        if ($this->isEditing && $this->editId) {
            $surat = SuratMasuk::findOrFail($this->editId);
            Gate::authorize('update', $surat);

            $data = [
                'nomor_surat' => trim($validated['nomor_surat']),
                'tanggal_surat' => $validated['tanggal_surat'],
                'pengirim' => $finalPengirim,
                'perihal' => trim($validated['perihal']),
            ];

            if ($this->file_surat) {
                // Hapus berkas lama jika ada
                if ($surat->file_path) {
                    if (Storage::disk('private')->exists($surat->file_path)) {
                        Storage::disk('private')->delete($surat->file_path);
                    }
                    if (Storage::disk('local')->exists($surat->file_path)) {
                        Storage::disk('local')->delete($surat->file_path);
                    }
                }

                $filename = Str::uuid()->toString().'.pdf';
                $path = $this->file_surat->storeAs('surat-masuk', $filename, 'local');
                $data['file_path'] = $path;
                $data['file_mime'] = 'application/pdf';
                $data['file_size'] = $this->file_surat->getSize();
            }

            $surat->update($data);
            $pesan = 'Surat masuk berhasil diperbarui.';
        } else {
            Gate::authorize('create', SuratMasuk::class);

            // Generate nomor agenda otomatis anti-duplikat
            $count = SuratMasuk::whereYear('created_at', date('Y'))->count() + 1;
            $nomorAgenda = sprintf('REG-SM/%s/%s/%04d', date('Y'), date('m'), $count);

            $filePath = null;
            $fileMime = null;
            $fileSize = null;

            if ($this->file_surat) {
                $filename = Str::uuid()->toString().'.pdf';
                $filePath = $this->file_surat->storeAs('surat-masuk', $filename, 'local');
                $fileMime = 'application/pdf';
                $fileSize = $this->file_surat->getSize();
            }

            SuratMasuk::create([
                'unit_kerja_id' => $unitId,
                'nomor_agenda' => $nomorAgenda,
                'nomor_surat' => trim($validated['nomor_surat']),
                'pengirim' => $finalPengirim,
                'tanggal_surat' => $validated['tanggal_surat'],
                'tanggal_terima' => Carbon::today(),
                'perihal' => trim($validated['perihal']),
                'status_disposisi' => StatusDisposisi::MENUNGGU,
                'file_path' => $filePath,
                'file_mime' => $fileMime,
                'file_size' => $fileSize,
                'created_by' => $user->id,
            ]);

            $pesan = 'Surat masuk baru berhasil dicatatkan ke dalam agenda kearsipan.';
        }

        $this->tutupModal();
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function bukaPreview(string $id): void
    {
        $surat = SuratMasuk::findOrFail($id);
        Gate::authorize('view', $surat);

        if (! $surat->file_path || (! Storage::disk('local')->exists($surat->file_path) && ! Storage::disk('private')->exists($surat->file_path))) {
            $this->dispatch('show-toast', type: 'error', message: 'Berkas fisik naskah tidak ditemukan pada penyimpanan privat.');

            return;
        }

        $this->previewJudul = $surat->nomor_surat.' - '.$surat->perihal;
        $this->previewUrl = URL::temporarySignedRoute(
            'documents.stream',
            now()->addMinutes(15),
            ['document' => $surat->id]
        );
        $this->showPreviewModal = true;
    }

    public function tutupPreview(): void
    {
        $this->showPreviewModal = false;
        $this->previewUrl = null;
        $this->previewJudul = null;
    }

    #[On('hapus-surat-masuk')]
    public function hapus(string $id): void
    {
        $surat = SuratMasuk::findOrFail($id);
        Gate::authorize('delete', $surat);

        if ($surat->file_path) {
            if (Storage::disk('local')->exists($surat->file_path)) {
                Storage::disk('local')->delete($surat->file_path);
            }
            if (Storage::disk('private')->exists($surat->file_path)) {
                Storage::disk('private')->delete($surat->file_path);
            }
        }

        $nomor = $surat->nomor_surat;
        $surat->delete();

        $pesan = 'Naskah surat masuk No. '.$nomor.' berhasil dihapus dari sistem kearsipan.';
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function render(): View
    {
        Gate::authorize('viewAny', SuratMasuk::class);

        $user = auth()->user();

        $query = SuratMasuk::query()
            ->with(['unitKerja', 'pembuat'])
            ->latest('tanggal_terima')
            ->latest('created_at');

        // Scoping per unit kerja bila bukan level pimpinan
        if (! $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']) && $user->unit_kerja_id) {
            $query->where('unit_kerja_id', $user->unit_kerja_id);
        }

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('nomor_surat', 'like', $term)
                    ->orWhere('pengirim', 'like', $term)
                    ->orWhere('perihal', 'like', $term)
                    ->orWhere('nomor_agenda', 'like', $term);
            });
        }

        if ($this->filterTanggal !== '') {
            $query->whereDate('tanggal_surat', $this->filterTanggal);
        }

        $daftarPengirim = MasterOpsi::getOpsi('pengirim_surat', $user->unit_kerja_id);

        return view('livewire.surat-masuk.index', [
            'daftarSurat' => $query->paginate(15),
            'daftarPengirim' => $daftarPengirim,
        ]);
    }
}
