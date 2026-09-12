<?php

declare(strict_types=1);

namespace App\Livewire\SuratKeluar;

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use App\Actions\Surat\GenerateNomorSuratAction;
use App\Models\MasterNomorSurat;
use App\Models\MasterOpsi;
use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public string $masterNomorSuratId = '';

    public string $jenisSurat = '';

    public string $jenisSuratManual = '';

    public bool $isCustomJenisSurat = false;

    public string $tujuanSurat = '';

    public string $tujuanSuratManual = '';

    public bool $isCustomTujuanSurat = false;

    public string $tanggalSurat = '';

    public string $perihal = '';

    public mixed $berkas = null;

    public function updatedJenisSurat(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomJenisSurat = true;
        } else {
            $this->isCustomJenisSurat = false;
        }
    }

    public function updatedTujuanSurat(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomTujuanSurat = true;
        } else {
            $this->isCustomTujuanSurat = false;
        }
    }

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

        $opsiJenis = MasterOpsi::getOpsi('jenis_surat', $user->unit_kerja_id);
        if (! empty($opsiJenis)) {
            $this->jenisSurat = $opsiJenis[0];
        }

        $opsiTujuan = MasterOpsi::getOpsi('tujuan_surat', $user->unit_kerja_id);
        if (! empty($opsiTujuan)) {
            $this->tujuanSurat = $opsiTujuan[0];
        }
    }

    public function simpan(
        GenerateNomorSuratAction $nomorAction,
        ValidateAndStoreDocumentAction $documentAction
    ): void {
        Gate::authorize('create', SuratKeluar::class);

        $finalJenisSurat = $this->jenisSurat;
        if ($this->jenisSurat === '__custom__' || $this->isCustomJenisSurat) {
            $finalJenisSurat = trim($this->jenisSuratManual);
        }

        $finalTujuanSurat = $this->tujuanSurat;
        if ($this->tujuanSurat === '__custom__' || $this->isCustomTujuanSurat) {
            $finalTujuanSurat = trim($this->tujuanSuratManual);
        }

        $rules = [
            'masterNomorSuratId' => ['required', 'uuid', 'exists:master_nomor_surat,id'],
            'tanggalSurat' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'berkas' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];

        if ($this->jenisSurat === '__custom__' || $this->isCustomJenisSurat) {
            $rules['jenisSuratManual'] = ['required', 'string', 'max:100'];
        } else {
            $rules['jenisSurat'] = ['required', 'string', 'max:100'];
        }

        if ($this->tujuanSurat === '__custom__' || $this->isCustomTujuanSurat) {
            $rules['tujuanSuratManual'] = ['required', 'string', 'max:255'];
        } else {
            $rules['tujuanSurat'] = ['required', 'string', 'max:255'];
        }

        $messages = [
            'masterNomorSuratId.required' => 'Pola format penomoran surat wajib dipilih.',
            'jenisSurat.required' => 'Jenis surat dinas wajib dipilih.',
            'jenisSuratManual.required' => 'Jenis surat dinas secara manual wajib diisi.',
            'tujuanSurat.required' => 'Pihak tujuan surat wajib dipilih.',
            'tujuanSuratManual.required' => 'Pihak tujuan surat secara manual wajib diisi.',
            'tanggalSurat.required' => 'Tanggal surat dinas wajib ditentukan.',
            'perihal.required' => 'Perihal surat dinas wajib diisi.',
            'berkas.mimes' => 'Berkas lampiran wajib berformat PDF (.pdf).',
            'berkas.max' => 'Ukuran berkas lampiran maksimal 10 Megabyte (10MB).',
        ];

        $this->validate($rules, $messages);

        if ($finalJenisSurat === '') {
            $this->addError('jenisSurat', 'Jenis surat dinas tidak boleh kosong.');

            return;
        }

        if ($finalTujuanSurat === '') {
            $this->addError('tujuanSurat', 'Pihak tujuan surat tidak boleh kosong.');

            return;
        }

        $user = auth()->user();

        // Save new options to MasterOpsi
        MasterOpsi::simpanJikaBaru('jenis_surat', $finalJenisSurat, $user->unit_kerja_id, $user->id);
        MasterOpsi::simpanJikaBaru('tujuan_surat', $finalTujuanSurat, $user->unit_kerja_id, $user->id);

        $master = MasterNomorSurat::with('unitKerja')->findOrFail($this->masterNomorSuratId);

        DB::transaction(function () use ($master, $nomorAction, $documentAction, $user, $finalJenisSurat, $finalTujuanSurat): void {
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
                $fileMime = $storedDoc->fileMime;
                $fileSize = $storedDoc->fileSize;
            }

            SuratKeluar::create([
                'unit_kerja_id' => $master->unit_kerja_id,
                'nomor_agenda' => $nomorResult->nomorAgenda,
                'nomor_surat' => $nomorResult->nomorSurat,
                'kode_klasifikasi' => $nomorResult->kodeKlasifikasi,
                'perihal' => $this->perihal,
                'tujuan' => $finalTujuanSurat,
                'tanggal_surat' => $this->tanggalSurat,
                'jenis_surat' => $finalJenisSurat,
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

        $daftarJenisSurat = MasterOpsi::getOpsi('jenis_surat', $user->unit_kerja_id);
        $daftarTujuanSurat = MasterOpsi::getOpsi('tujuan_surat', $user->unit_kerja_id);

        return view('livewire.surat-keluar.create', [
            'masterNomorList' => $masterQuery->get(),
            'daftarJenisSurat' => $daftarJenisSurat,
            'daftarTujuanSurat' => $daftarTujuanSurat,
        ]);
    }
}
