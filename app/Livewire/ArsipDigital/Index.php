<?php

declare(strict_types=1);

namespace App\Livewire\ArsipDigital;

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use App\Models\ArsipDigital;
use App\Models\MasterOpsi;
use App\Models\UnitKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
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

    #[UrlParam(as: 'kat')]
    public string $filterKategori = '';

    // Modal Form State (Tambah & Edit)
    public bool $showFormModal = false;

    public bool $isEditing = false;

    public ?string $editId = null;

    // Form fields (Sesuai Spesifikasi Modal Gambar 4)
    public string $judul = '';

    public string $kategori = '';

    public string $kategori_manual = '';

    public bool $isCustomKategori = false;

    public string $nomor_dokumen = '';

    public string $tanggal_dokumen = '';

    public string $deskripsi = '';

    public mixed $file_dokumen = null;

    public ?string $existing_file_name = null;

    // Modal Preview Document (Signed Stream)
    public bool $showPreviewModal = false;

    public ?string $previewUrl = null;

    public ?string $previewJudul = null;

    public ?string $previewMime = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterKategori(): void
    {
        $this->resetPage();
    }

    public function updatedKategori(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomKategori = true;
        } else {
            $this->isCustomKategori = false;
        }
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterKategori = '';
        $this->resetPage();
    }

    public function bukaTambahModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->tanggal_dokumen = date('Y-m-d');

        // Set default kategori dari opsi pertama jika ada
        $opsi = MasterOpsi::getOpsi('kategori_arsip', auth()->user()->unit_kerja_id);
        if (! empty($opsi)) {
            $this->kategori = $opsi[0];
        }

        $this->showFormModal = true;
    }

    public function bukaEditModal(string $id): void
    {
        $arsip = ArsipDigital::findOrFail($id);
        Gate::authorize('update', $arsip);

        $this->resetForm();
        $this->isEditing = true;
        $this->editId = $arsip->id;
        $this->judul = $arsip->judul;

        $opsi = MasterOpsi::getOpsi('kategori_arsip', auth()->user()->unit_kerja_id);
        $katVal = is_object($arsip->kategori) ? $arsip->kategori->value : (string) $arsip->kategori;

        if (in_array($katVal, $opsi, true)) {
            $this->kategori = $katVal;
            $this->isCustomKategori = false;
        } else {
            $this->kategori = '__custom__';
            $this->kategori_manual = $katVal;
            $this->isCustomKategori = true;
        }

        $this->nomor_dokumen = $arsip->nomor_dokumen ?? '';
        $this->tanggal_dokumen = $arsip->tanggal_dokumen ? $arsip->tanggal_dokumen->format('Y-m-d') : date('Y-m-d');
        $this->deskripsi = $arsip->deskripsi ?? '';
        $this->existing_file_name = $arsip->file_path ? basename($arsip->file_path) : null;

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
        $this->isEditing = false;
        $this->judul = '';
        $this->kategori = '';
        $this->kategori_manual = '';
        $this->isCustomKategori = false;
        $this->nomor_dokumen = '';
        $this->tanggal_dokumen = '';
        $this->deskripsi = '';
        $this->file_dokumen = null;
        $this->existing_file_name = null;
    }

    public function simpan(ValidateAndStoreDocumentAction $docAction): void
    {
        $user = auth()->user();
        $unitId = $user->unit_kerja_id ?? UnitKerja::first()?->id;

        // Tentukan nilai kategori final
        $finalKategori = $this->kategori;
        if ($this->kategori === '__custom__' || $this->isCustomKategori) {
            $finalKategori = trim($this->kategori_manual);
        }

        $rules = [
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_dokumen' => ['required', 'date'],
            'file_dokumen' => [$this->isEditing ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // Max 10MB
        ];

        if ($this->kategori === '__custom__' || $this->isCustomKategori) {
            $rules['kategori_manual'] = ['required', 'string', 'max:100'];
        } else {
            $rules['kategori'] = ['required', 'string', 'max:100'];
        }

        $messages = [
            'judul.required' => 'Nama dokumen arsip digital wajib diisi.',
            'judul.max' => 'Nama dokumen tidak boleh melebihi 255 karakter.',
            'kategori.required' => 'Kategori dokumen wajib dipilih.',
            'kategori_manual.required' => 'Kategori dokumen secara manual wajib diisi.',
            'tanggal_dokumen.required' => 'Tanggal dokumen wajib ditentukan.',
            'tanggal_dokumen.date' => 'Format tanggal dokumen tidak valid.',
            'file_dokumen.required' => 'File dokumen (PDF, DOC/DOCX) wajib diunggah.',
            'file_dokumen.mimes' => 'Format file dokumen wajib berformat PDF, DOC, atau DOCX.',
            'file_dokumen.max' => 'Ukuran file dokumen maksimal 10 Megabyte (10MB).',
        ];

        $validated = $this->validate($rules, $messages);

        if ($finalKategori === '') {
            $this->addError('kategori', 'Kategori dokumen tidak boleh kosong.');

            return;
        }

        // Simpan kategori baru secara otomatis ke master opsi agar tersimpan permanen
        MasterOpsi::simpanJikaBaru('kategori_arsip', $finalKategori, $unitId, $user->id);

        if ($this->isEditing && $this->editId) {
            $arsip = ArsipDigital::findOrFail($this->editId);
            Gate::authorize('update', $arsip);

            $data = [
                'judul' => trim($validated['judul']),
                'kategori' => $finalKategori,
                'nomor_dokumen' => $this->nomor_dokumen !== '' ? trim($this->nomor_dokumen) : null,
                'tanggal_dokumen' => $validated['tanggal_dokumen'],
                'deskripsi' => $this->deskripsi !== '' ? trim($this->deskripsi) : null,
            ];

            if ($this->file_dokumen instanceof UploadedFile) {
                // Simpan berkas fisik baru
                $extension = strtolower($this->file_dokumen->getClientOriginalExtension());
                $filename = Str::uuid()->toString().'.'.$extension;
                $filePath = $this->file_dokumen->storeAs('arsip-digital', $filename, 'local');

                // Hapus berkas lama jika ada
                if ($arsip->file_path && Storage::disk('local')->exists($arsip->file_path)) {
                    Storage::disk('local')->delete($arsip->file_path);
                }

                $data['file_path'] = $filePath;
                $data['file_mime'] = $this->file_dokumen->getMimeType() ?: 'application/octet-stream';
                $data['file_size'] = $this->file_dokumen->getSize();
            }

            $arsip->update($data);
            $pesan = 'Dokumen arsip digital berhasil diperbarui.';
        } else {
            Gate::authorize('create', ArsipDigital::class);

            $filePath = null;
            $fileMime = 'application/pdf';
            $fileSize = 0;

            if ($this->file_dokumen instanceof UploadedFile) {
                $extension = strtolower($this->file_dokumen->getClientOriginalExtension());
                $filename = Str::uuid()->toString().'.'.$extension;
                $filePath = $this->file_dokumen->storeAs('arsip-digital', $filename, 'local');
                $fileMime = $this->file_dokumen->getMimeType() ?: 'application/octet-stream';
                $fileSize = $this->file_dokumen->getSize();
            }

            ArsipDigital::create([
                'unit_kerja_id' => $unitId,
                'judul' => trim($validated['judul']),
                'kategori' => $finalKategori,
                'nomor_dokumen' => $this->nomor_dokumen !== '' ? trim($this->nomor_dokumen) : null,
                'tanggal_dokumen' => $validated['tanggal_dokumen'],
                'deskripsi' => $this->deskripsi !== '' ? trim($this->deskripsi) : null,
                'file_path' => $filePath,
                'file_mime' => $fileMime,
                'file_size' => $fileSize,
                'created_by' => $user->id,
            ]);

            $pesan = 'Dokumen arsip digital baru berhasil diunggah dan disimpan.';
        }

        $this->tutupModal();
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function bukaPreview(string $id): void
    {
        $arsip = ArsipDigital::findOrFail($id);
        Gate::authorize('view', $arsip);

        if (! $arsip->file_path || ! Storage::disk('local')->exists($arsip->file_path)) {
            $this->dispatch('show-toast', type: 'error', message: 'Berkas fisik dokumen tidak ditemukan pada repositori privat.');

            return;
        }

        $this->previewJudul = $arsip->judul;
        $this->previewMime = $arsip->file_mime;
        $this->previewUrl = URL::temporarySignedRoute(
            'documents.stream',
            now()->addMinutes(15),
            ['document' => $arsip->id]
        );
        $this->showPreviewModal = true;
    }

    public function tutupPreview(): void
    {
        $this->showPreviewModal = false;
        $this->previewUrl = null;
        $this->previewJudul = null;
        $this->previewMime = null;
    }

    #[On('hapus-arsip-digital')]
    public function hapus(string $id): void
    {
        $arsip = ArsipDigital::findOrFail($id);
        Gate::authorize('delete', $arsip);

        if ($arsip->file_path && Storage::disk('local')->exists($arsip->file_path)) {
            Storage::disk('local')->delete($arsip->file_path);
        }

        $judul = $arsip->judul;
        $arsip->delete();

        $pesan = 'Dokumen arsip "'.$judul.'" berhasil dihapus dari repositori digital.';
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function render(): View
    {
        $user = auth()->user();
        $isLeaderOrSuper = $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']);

        $query = ArsipDigital::query()
            ->with(['unitKerja', 'author'])
            ->latest('tanggal_dokumen')
            ->latest('created_at');

        // Scoping unit kerja
        if (! $isLeaderOrSuper && $user->unit_kerja_id) {
            $query->where('unit_kerja_id', $user->unit_kerja_id);
        }

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('judul', 'like', $term)
                    ->orWhere('nomor_dokumen', 'like', $term)
                    ->orWhere('kategori', 'like', $term);
            });
        }

        if ($this->filterKategori !== '') {
            $query->where('kategori', $this->filterKategori);
        }

        // Daftar opsi kategori dari database master
        $daftarKategori = MasterOpsi::getOpsi('kategori_arsip', $user->unit_kerja_id);

        return view('livewire.arsip-digital.index', [
            'daftarArsip' => $query->paginate(12),
            'daftarKategori' => $daftarKategori,
        ]);
    }
}
