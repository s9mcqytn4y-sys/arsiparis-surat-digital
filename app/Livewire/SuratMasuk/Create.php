<?php

declare(strict_types=1);

namespace App\Livewire\SuratMasuk;

use App\Actions\Documents\ValidateAndStoreDocumentAction;
use App\Enums\StatusDisposisi;
use App\Models\MasterOpsi;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
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

    public string $nomorSurat = '';

    public string $pengirim = '';

    public string $pengirim_manual = '';

    public bool $isCustomPengirim = false;

    public string $tanggalSurat = '';

    public string $tanggalTerima = '';

    public string $perihal = '';

    public string $unitKerjaId = '';

    public mixed $berkas = null;

    public function updatedPengirim(string $value): void
    {
        if ($value === '__custom__') {
            $this->isCustomPengirim = true;
        } else {
            $this->isCustomPengirim = false;
        }
    }

    public function mount(): void
    {
        $this->tanggalTerima = Carbon::now()->format('Y-m-d');
        $this->tanggalSurat = Carbon::now()->format('Y-m-d');

        $user = auth()->user();
        if ($user->unit_kerja_id) {
            $this->unitKerjaId = $user->unit_kerja_id;
        }

        $opsi = MasterOpsi::getOpsi('pengirim_surat', $user->unit_kerja_id);
        if (! empty($opsi)) {
            $this->pengirim = $opsi[0];
        }
    }

    public function simpan(ValidateAndStoreDocumentAction $documentAction): void
    {
        Gate::authorize('create', SuratMasuk::class);

        $finalPengirim = $this->pengirim;
        if ($this->pengirim === '__custom__' || $this->isCustomPengirim) {
            $finalPengirim = trim($this->pengirim_manual);
        }

        $rules = [
            'nomorSurat' => ['required', 'string', 'max:100'],
            'tanggalSurat' => ['required', 'date'],
            'tanggalTerima' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'unitKerjaId' => ['required', 'uuid', 'exists:unit_kerja,id'],
            'berkas' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];

        if ($this->pengirim === '__custom__' || $this->isCustomPengirim) {
            $rules['pengirim_manual'] = ['required', 'string', 'max:200'];
        } else {
            $rules['pengirim'] = ['required', 'string', 'max:200'];
        }

        $messages = [
            'nomorSurat.required' => 'Nomor naskah surat masuk wajib diisi.',
            'pengirim.required' => 'Asal pengirim surat wajib dipilih.',
            'pengirim_manual.required' => 'Asal pengirim surat secara manual wajib diisi.',
            'tanggalSurat.required' => 'Tanggal naskah surat wajib diisi.',
            'tanggalTerima.required' => 'Tanggal terima surat wajib diisi.',
            'perihal.required' => 'Perihal naskah surat masuk wajib diisi.',
            'unitKerjaId.required' => 'Unit kerja penerima wajib ditentukan.',
            'berkas.required' => 'Berkas pindaian naskah PDF wajib diunggah.',
            'berkas.mimes' => 'Berkas pindaian wajib berformat PDF (.pdf).',
            'berkas.max' => 'Ukuran berkas pindaian maksimal 10 Megabyte (10MB).',
        ];

        $this->validate($rules, $messages);

        if ($finalPengirim === '') {
            $this->addError('pengirim', 'Asal pengirim surat tidak boleh kosong.');

            return;
        }

        $user = auth()->user();

        // Save new sender to MasterOpsi
        MasterOpsi::simpanJikaBaru('pengirim_surat', $finalPengirim, $user->unit_kerja_id, $user->id);

        // Enforce unit kerja jika user bukan super_admin
        $targetUnitId = $user->hasRole(['super_admin', 'rektor', 'wakil_rektor'])
            ? $this->unitKerjaId
            : (string) $user->unit_kerja_id;

        DB::transaction(function () use ($documentAction, $targetUnitId, $user, $finalPengirim): void {
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
                'pengirim' => $finalPengirim,
                'tanggal_surat' => $this->tanggalSurat,
                'tanggal_terima' => $this->tanggalTerima,
                'perihal' => $this->perihal,
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

        $user = auth()->user();
        $daftarUnit = UnitKerja::orderBy('nama_unit')->get(['id', 'nama_unit', 'kode_unit']);
        $daftarPengirim = MasterOpsi::getOpsi('pengirim_surat', $user->unit_kerja_id);

        return view('livewire.surat-masuk.create', [
            'daftarUnit' => $daftarUnit,
            'daftarPengirim' => $daftarPengirim,
        ]);
    }
}
