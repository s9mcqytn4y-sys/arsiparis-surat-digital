<?php

declare(strict_types=1);

namespace App\Livewire\SuratKeluar;

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use App\Actions\Surat\GenerateNomorSuratAction;
use App\Models\MasterNomorSurat;
use App\Models\MasterOpsi;
use App\Models\SuratKeluar;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
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
    public string $modeNomor = 'manual'; // 'manual' | 'otomatis'

    public string $nomor_surat_manual = '';

    public string $master_nomor_surat_id = '';

    public string $tanggal_surat = '';

    public string $tujuan = '';

    public string $tujuan_manual = '';

    public bool $isCustomTujuan = false;

    public string $perihal = '';

    public string $jenis_surat = '';

    public string $jenis_surat_manual = '';

    public bool $isCustomJenisSurat = false;

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

    public function updatedTujuan(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomTujuan = true;
        } else {
            $this->isCustomTujuan = false;
        }
    }

    public function updatedJenisSurat(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomJenisSurat = true;
        } else {
            $this->isCustomJenisSurat = false;
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
        $this->modeNomor = 'manual';

        $user = auth()->user();
        $unitId = $user->unit_kerja_id;

        $opsiTujuan = MasterOpsi::getOpsi('tujuan_surat', $unitId);
        if (! empty($opsiTujuan)) {
            $this->tujuan = $opsiTujuan[0];
        }

        $opsiJenis = MasterOpsi::getOpsi('jenis_surat', $unitId);
        if (! empty($opsiJenis)) {
            $this->jenis_surat = $opsiJenis[0];
        }

        // Set default master nomor surat jika ada
        $masterDefault = MasterNomorSurat::query()
            ->when($unitId, fn (Builder $q) => $q->where('unit_kerja_id', $unitId))
            ->where('is_active', true)
            ->first();

        if ($masterDefault) {
            $this->master_nomor_surat_id = $masterDefault->id;
        }

        $this->showFormModal = true;
    }

    public function bukaEditModal(string $id): void
    {
        $surat = SuratKeluar::findOrFail($id);
        Gate::authorize('update', $surat);

        $this->resetForm();
        $this->isEditing = true;
        $this->editId = $surat->id;
        $this->modeNomor = 'manual';
        $this->nomor_surat_manual = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : date('Y-m-d');

        $unitId = auth()->user()->unit_kerja_id;

        // Smart Dropdown Tujuan
        $opsiTujuan = MasterOpsi::getOpsi('tujuan_surat', $unitId);
        $valTujuan = $surat->tujuan ?? '';
        if (in_array($valTujuan, $opsiTujuan, true)) {
            $this->tujuan = $valTujuan;
            $this->isCustomTujuan = false;
        } else {
            $this->tujuan = '__custom__';
            $this->tujuan_manual = $valTujuan;
            $this->isCustomTujuan = true;
        }

        $this->perihal = $surat->perihal;

        // Smart Dropdown Jenis Surat
        $opsiJenis = MasterOpsi::getOpsi('jenis_surat', $unitId);
        $valJenis = $surat->jenis_surat ?? '';
        if (in_array($valJenis, $opsiJenis, true)) {
            $this->jenis_surat = $valJenis;
            $this->isCustomJenisSurat = false;
        } else {
            $this->jenis_surat = '__custom__';
            $this->jenis_surat_manual = $valJenis;
            $this->isCustomJenisSurat = true;
        }

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
        $this->modeNomor = 'manual';
        $this->nomor_surat_manual = '';
        $this->master_nomor_surat_id = '';
        $this->tanggal_surat = '';
        $this->tujuan = '';
        $this->tujuan_manual = '';
        $this->isCustomTujuan = false;
        $this->perihal = '';
        $this->jenis_surat = '';
        $this->jenis_surat_manual = '';
        $this->isCustomJenisSurat = false;
        $this->file_surat = null;
        $this->existing_file_name = null;
    }

    public function simpan(
        GenerateNomorSuratAction $nomorAction,
        ValidateAndStoreDocumentAction $docAction
    ): void {
        $finalTujuan = $this->tujuan;
        if ($this->tujuan === '__custom__' || $this->isCustomTujuan) {
            $finalTujuan = trim($this->tujuan_manual);
        }

        $finalJenisSurat = $this->jenis_surat;
        if ($this->jenis_surat === '__custom__' || $this->isCustomJenisSurat) {
            $finalJenisSurat = trim($this->jenis_surat_manual);
        }

        $rules = [
            'modeNomor' => ['required', 'in:manual,otomatis'],
            'tanggal_surat' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:1000'],
            'file_surat' => [$this->isEditing ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:10240'],
        ];

        if ($this->tujuan === '__custom__' || $this->isCustomTujuan) {
            $rules['tujuan_manual'] = ['required', 'string', 'max:255'];
        } else {
            $rules['tujuan'] = ['required', 'string', 'max:255'];
        }

        if ($this->jenis_surat === '__custom__' || $this->isCustomJenisSurat) {
            $rules['jenis_surat_manual'] = ['required', 'string', 'max:100'];
        } else {
            $rules['jenis_surat'] = ['required', 'string', 'max:100'];
        }

        if ($this->modeNomor === 'manual' || $this->isEditing) {
            $rules['nomor_surat_manual'] = [
                'required',
                'string',
                'max:100',
                Rule::unique('surat_keluar', 'nomor_surat')->ignore($this->editId),
            ];
        } else {
            $rules['master_nomor_surat_id'] = ['required', 'exists:master_nomor_surat,id'];
        }

        $messages = [
            'tanggal_surat.required' => 'Tanggal surat dinas wajib ditentukan.',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid.',
            'tujuan.required' => 'Instansi/pihak tujuan surat dinas wajib diisi.',
            'tujuan_manual.required' => 'Pihak tujuan surat dinas secara manual wajib diisi.',
            'perihal.required' => 'Perihal surat dinas wajib diisi.',
            'jenis_surat.required' => 'Jenis surat dinas wajib diisi.',
            'jenis_surat_manual.required' => 'Jenis surat dinas secara manual wajib diisi.',
            'nomor_surat_manual.required' => 'Nomor surat dinas manual wajib diisi.',
            'nomor_surat_manual.unique' => 'Nomor surat dinas tersebut sudah digunakan di sistem.',
            'master_nomor_surat_id.required' => 'Pola format nomor surat wajib dipilih untuk penerbitan otomatis.',
            'file_surat.required' => 'Berkas naskah surat keluar berformat PDF wajib diunggah.',
            'file_surat.mimes' => 'Berkas naskah surat wajib berformat PDF (.pdf).',
            'file_surat.max' => 'Ukuran berkas naskah surat maksimal 10 Megabyte.',
        ];

        $validated = $this->validate($rules, $messages);

        if ($finalTujuan === '') {
            $this->addError('tujuan', 'Pihak tujuan surat dinas tidak boleh kosong.');

            return;
        }

        if ($finalJenisSurat === '') {
            $this->addError('jenis_surat', 'Jenis surat dinas tidak boleh kosong.');

            return;
        }

        $user = auth()->user();
        $unitId = $user->unit_kerja_id ?? UnitKerja::first()?->id;

        // Simpan opsi baru ke master opsi jika belum ada
        MasterOpsi::simpanJikaBaru('tujuan_surat', $finalTujuan, $unitId, $user->id);
        MasterOpsi::simpanJikaBaru('jenis_surat', $finalJenisSurat, $unitId, $user->id);

        if ($this->isEditing && $this->editId) {
            $surat = SuratKeluar::findOrFail($this->editId);
            Gate::authorize('update', $surat);

            $data = [
                'nomor_surat' => trim($validated['nomor_surat_manual']),
                'tanggal_surat' => $validated['tanggal_surat'],
                'tujuan' => $finalTujuan,
                'perihal' => trim($validated['perihal']),
                'jenis_surat' => $finalJenisSurat,
            ];

            if ($this->file_surat instanceof UploadedFile) {
                try {
                    $docResult = $docAction->execute($this->file_surat, 'surat-keluar');

                    // Hapus berkas lama jika ada
                    if ($surat->file_path) {
                        if (Storage::disk('local')->exists($surat->file_path)) {
                            Storage::disk('local')->delete($surat->file_path);
                        }
                        if (Storage::disk('private')->exists($surat->file_path)) {
                            Storage::disk('private')->delete($surat->file_path);
                        }
                    }

                    $data['file_path'] = $docResult->filePath;
                    $data['file_mime'] = $docResult->fileMime;
                    $data['file_size'] = $docResult->fileSize;
                } catch (\InvalidArgumentException $e) {
                    $this->addError('file_surat', $e->getMessage());

                    return;
                }
            }

            $surat->update($data);
            $pesan = 'Naskah surat keluar berhasil diperbarui.';
        } else {
            Gate::authorize('create', SuratKeluar::class);

            $dt = Carbon::parse($validated['tanggal_surat']);
            $tahun = (int) $dt->format('Y');
            $bulan = (int) $dt->format('n');

            $docResult = null;
            if ($this->file_surat instanceof UploadedFile) {
                try {
                    $docResult = $docAction->execute($this->file_surat, 'surat-keluar');
                } catch (\InvalidArgumentException $e) {
                    $this->addError('file_surat', $e->getMessage());

                    return;
                }
            }

            DB::transaction(function () use (
                $validated,
                $tahun,
                $bulan,
                $nomorAction,
                $docResult,
                $unitId,
                $user,
                $finalTujuan,
                $finalJenisSurat
            ): void {
                if ($this->modeNomor === 'otomatis') {
                    $nomorResult = $nomorAction->execute(
                        $validated['master_nomor_surat_id'],
                        $tahun,
                        $bulan
                    );
                    $nomorSurat = $nomorResult->nomorSurat;
                    $nomorAgenda = $nomorResult->nomorAgenda;
                    $kodeKlasifikasi = $nomorResult->kodeKlasifikasi;
                } else {
                    $nomorSurat = trim($validated['nomor_surat_manual']);
                    $count = SuratKeluar::whereYear('created_at', $tahun)->count() + 1;
                    $nomorAgenda = sprintf('REG-SK/%04d/%02d/%04d', $tahun, $bulan, $count);
                    $kodeKlasifikasi = 'MANUAL';
                }

                SuratKeluar::create([
                    'unit_kerja_id' => $unitId,
                    'nomor_agenda' => $nomorAgenda,
                    'nomor_surat' => $nomorSurat,
                    'kode_klasifikasi' => $kodeKlasifikasi ?? 'UMUM',
                    'tujuan' => $finalTujuan,
                    'tanggal_surat' => $validated['tanggal_surat'],
                    'perihal' => trim($validated['perihal']),
                    'jenis_surat' => $finalJenisSurat,
                    'file_path' => $docResult?->filePath,
                    'file_mime' => $docResult?->fileMime,
                    'file_size' => $docResult?->fileSize,
                    'created_by' => $user->id,
                ]);
            });

            $pesan = 'Surat keluar baru berhasil dicatatkan dan nomor resmi diterbitkan.';
        }

        $this->tutupModal();
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function bukaPreview(string $id): void
    {
        $surat = SuratKeluar::findOrFail($id);
        Gate::authorize('view', $surat);

        if (! $surat->file_path || (! Storage::disk('local')->exists($surat->file_path) && ! Storage::disk('private')->exists($surat->file_path))) {
            $this->dispatch('show-toast', type: 'error', message: 'Berkas fisik naskah tidak ditemukan pada repositori privat.');

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

    #[On('hapus-surat-keluar')]
    public function hapus(string $id): void
    {
        $surat = SuratKeluar::findOrFail($id);
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

        $pesan = 'Naskah surat keluar No. '.$nomor.' berhasil dihapus dari sistem kearsipan.';
        $this->dispatch('show-toast', type: 'success', message: $pesan);
        session()->flash('success', $pesan);
    }

    public function render(): View
    {
        Gate::authorize('viewAny', SuratKeluar::class);

        $user = auth()->user();
        $isLeaderOrSuper = $user->hasRole(['super_admin', 'rektor', 'wakil_rektor']);

        $query = SuratKeluar::query()
            ->with(['unitKerja', 'author'])
            ->latest('tanggal_surat')
            ->latest('created_at');

        // Scoping per unit kerja bila bukan level pimpinan universitas
        if (! $isLeaderOrSuper && $user->unit_kerja_id) {
            $query->where('unit_kerja_id', $user->unit_kerja_id);
        }

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('nomor_surat', 'like', $term)
                    ->orWhere('tujuan', 'like', $term)
                    ->orWhere('perihal', 'like', $term)
                    ->orWhere('jenis_surat', 'like', $term)
                    ->orWhere('nomor_agenda', 'like', $term);
            });
        }

        if ($this->filterTanggal !== '') {
            $query->whereDate('tanggal_surat', $this->filterTanggal);
        }

        // Query Master Format Nomor Surat yang aktif
        $masterNomorQuery = MasterNomorSurat::query()
            ->with('unitKerja')
            ->where('is_active', true);

        if (! $isLeaderOrSuper && $user->unit_kerja_id) {
            $masterNomorQuery->where('unit_kerja_id', $user->unit_kerja_id);
        }

        $daftarTujuanSurat = MasterOpsi::getOpsi('tujuan_surat', $user->unit_kerja_id);
        $daftarJenisSurat = MasterOpsi::getOpsi('jenis_surat', $user->unit_kerja_id);

        return view('livewire.surat-keluar.index', [
            'daftarSurat' => $query->paginate(15),
            'daftarMasterNomor' => $masterNomorQuery->orderBy('kode_klasifikasi')->get(),
            'daftarTujuanSurat' => $daftarTujuanSurat,
            'daftarJenisSurat' => $daftarJenisSurat,
        ]);
    }
}
