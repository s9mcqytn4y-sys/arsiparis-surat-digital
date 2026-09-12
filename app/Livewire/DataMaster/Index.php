<?php

declare(strict_types=1);

namespace App\Livewire\DataMaster;

use App\Constants\AppConstants;
use App\Livewire\Concerns\HasSmartOpsi;
use App\Models\MasterNomorSurat;
use App\Models\MasterOpsi;
use App\Models\Pegawai;
use App\Models\PengaturanDokumen;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Data Master System & Kepegawaian')]
class Index extends Component
{
    use HasSmartOpsi, WithFileUploads, WithPagination;

    public string $activeTab = 'pegawai'; // 'pegawai', 'nomor_surat', 'admin'

    // --- Pegawai Form Fields ---
    public bool $modalPegawaiOpen = false;

    public ?string $pegawaiId = null;

    public string $pegawai_nip = '';

    public string $pegawai_nama = '';

    public string $pegawai_jabatan = '';

    public string $pegawai_golongan = '';

    public ?string $pegawai_unit_id = null;

    // --- Nomor Surat Form Fields ---
    public bool $modalNomorSuratOpen = false;

    public ?string $nomorSuratId = null;

    public string $ns_nomor_surat = '';

    public string $ns_jenis_surat = '';

    public string $ns_tanggal_dibuat = '';

    public string $ns_keterangan = '';

    public bool $ns_status = true;

    // --- Admin Form Fields ---
    public bool $modalAdminOpen = false;

    public ?string $adminId = null;

    public string $admin_username = '';

    public string $admin_password = '';

    public string $admin_password_confirmation = '';

    public string $admin_nama = '';

    public string $admin_email = '';

    public string $admin_role = 'petugas_tu';

    public ?string $admin_unit_id = null;

    // --- Import Modal Fields ---
    public bool $modalImportOpen = false;

    public mixed $importFile = null;

    public function mount(): void
    {
        $this->ns_tanggal_dibuat = Carbon::now()->format('Y-m-d');
        $defaultUnit = UnitKerja::first();
        if ($defaultUnit) {
            $this->pegawai_unit_id = $defaultUnit->id;
            $this->admin_unit_id = $defaultUnit->id;
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // ==========================================
    // PEGAWAI CRUD
    // ==========================================
    public function openPegawaiModal(?string $id = null): void
    {
        $this->resetErrorBag();
        $this->pegawaiId = $id;

        if ($id) {
            $pegawai = Pegawai::findOrFail($id);
            $this->pegawai_nip = $pegawai->nip_nidn;
            $this->pegawai_nama = $pegawai->nama;
            $this->pegawai_jabatan = $pegawai->jabatan;
            $this->pegawai_golongan = $pegawai->golongan ?? '';
            $this->pegawai_unit_id = $pegawai->unit_kerja_id;
        } else {
            $this->pegawai_nip = '';
            $this->pegawai_nama = '';
            $this->pegawai_jabatan = '';
            $this->pegawai_golongan = '';
            $defaultUnit = UnitKerja::first();
            $this->pegawai_unit_id = $defaultUnit?->id;
        }

        $this->modalPegawaiOpen = true;
    }

    public function savePegawai(): void
    {
        $rules = [
            'pegawai_nip' => ['required', 'string', 'max:50', 'unique:pegawai,nip_nidn,'.($this->pegawaiId ?: 'NULL').',id'],
            'pegawai_nama' => ['required', 'string', 'max:255'],
            'pegawai_jabatan' => ['required', 'string', 'max:100'],
            'pegawai_golongan' => ['nullable', 'string', 'max:50'],
            'pegawai_unit_id' => ['required', 'exists:unit_kerja,id'],
        ];

        $messages = [
            'pegawai_nip.required' => 'NIP / NIDN wajib diisi.',
            'pegawai_nip.unique' => 'NIP / NIDN sudah terdaftar.',
            'pegawai_nama.required' => 'Nama lengkap pegawai wajib diisi.',
            'pegawai_jabatan.required' => 'Jabatan pegawai wajib diisi.',
            'pegawai_unit_id.required' => 'Unit kerja wajib dipilih.',
        ];

        $validated = $this->validate($rules, $messages);

        // Auto-save smart opsi untuk jabatan & golongan
        $this->simpanOpsiKustom('jabatan_pegawai', $validated['pegawai_jabatan']);
        if (! empty($validated['pegawai_golongan'])) {
            $this->simpanOpsiKustom('pangkat_golongan', $validated['pegawai_golongan']);
        }

        Pegawai::updateOrCreate(
            ['id' => $this->pegawaiId],
            [
                'nip_nidn' => trim($validated['pegawai_nip']),
                'nama' => trim($validated['pegawai_nama']),
                'jabatan' => trim($validated['pegawai_jabatan']),
                'golongan' => $validated['pegawai_golongan'] ? trim($validated['pegawai_golongan']) : null,
                'unit_kerja_id' => $validated['pegawai_unit_id'],
                'is_active' => true,
            ]
        );

        $this->modalPegawaiOpen = false;
        $pesan = $this->pegawaiId ? 'Data pegawai berhasil diperbarui.' : 'Data pegawai baru berhasil ditambahkan.';
        $this->dispatch('show-toast', type: 'success', message: $pesan);
    }

    public function deletePegawai(string $id): void
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            $pegawai->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Data pegawai berhasil dihapus.');
        }
    }

    // ==========================================
    // NOMOR SURAT CRUD
    // ==========================================
    public function openNomorSuratModal(?string $id = null): void
    {
        $this->resetErrorBag();
        $this->nomorSuratId = $id;

        if ($id) {
            $ns = MasterNomorSurat::findOrFail($id);
            $this->ns_nomor_surat = $ns->format_pola;
            $this->ns_jenis_surat = $ns->nama_klasifikasi;
            $this->ns_tanggal_dibuat = $ns->tanggal_dibuat ? $ns->tanggal_dibuat->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $this->ns_keterangan = $ns->keterangan ?? '';
            $this->ns_status = $ns->is_active;
        } else {
            $this->ns_nomor_surat = '';
            $this->ns_jenis_surat = '';
            $this->ns_tanggal_dibuat = Carbon::now()->format('Y-m-d');
            $this->ns_keterangan = '';
            $this->ns_status = true;
        }

        $this->modalNomorSuratOpen = true;
    }

    public function saveNomorSurat(): void
    {
        $rules = [
            'ns_nomor_surat' => ['required', 'string', 'max:255'],
            'ns_jenis_surat' => ['required', 'string', 'max:100'],
            'ns_tanggal_dibuat' => ['required', 'date'],
            'ns_keterangan' => ['nullable', 'string', 'max:500'],
        ];

        $messages = [
            'ns_nomor_surat.required' => 'Nomor surat / format pola wajib diisi.',
            'ns_jenis_surat.required' => 'Jenis surat wajib dipilih.',
            'ns_tanggal_dibuat.required' => 'Tanggal dibuat wajib diisi.',
        ];

        $validated = $this->validate($rules, $messages);

        $this->simpanOpsiKustom('jenis_surat', $validated['ns_jenis_surat']);

        $defaultUnit = UnitKerja::first();

        MasterNomorSurat::updateOrCreate(
            ['id' => $this->nomorSuratId],
            [
                'unit_kerja_id' => $defaultUnit?->id,
                'kode_klasifikasi' => 'TU.'.strtoupper(substr(md5($validated['ns_jenis_surat']), 0, 4)),
                'nama_klasifikasi' => trim($validated['ns_jenis_surat']),
                'format_pola' => trim($validated['ns_nomor_surat']),
                'tanggal_dibuat' => $validated['ns_tanggal_dibuat'],
                'keterangan' => $validated['ns_keterangan'] ? trim($validated['ns_keterangan']) : null,
                'tahun' => (int) date('Y', strtotime($validated['ns_tanggal_dibuat'])),
                'is_active' => $this->ns_status,
            ]
        );

        $this->modalNomorSuratOpen = false;
        $pesan = $this->nomorSuratId ? 'Nomor surat berhasil diperbarui.' : 'Nomor surat baru berhasil ditambahkan.';
        $this->dispatch('show-toast', type: 'success', message: $pesan);
    }

    public function deleteNomorSurat(string $id): void
    {
        $ns = MasterNomorSurat::find($id);
        if ($ns) {
            $ns->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Master nomor surat berhasil dihapus.');
        }
    }

    // ==========================================
    // DATA ADMIN CRUD
    // ==========================================
    public function openAdminModal(?string $id = null): void
    {
        $this->resetErrorBag();
        $this->adminId = $id;

        if ($id) {
            $user = User::findOrFail($id);
            $this->admin_username = explode('@', $user->email)[0] ?: $user->email;
            $this->admin_nama = $user->name;
            $this->admin_email = $user->email;
            $this->admin_role = $user->roles->first()?->name ?: 'petugas_tu';
            $this->admin_unit_id = $user->unit_kerja_id;
            $this->admin_password = '';
            $this->admin_password_confirmation = '';
        } else {
            $this->admin_username = '';
            $this->admin_nama = '';
            $this->admin_email = '';
            $this->admin_role = 'petugas_tu';
            $this->admin_password = '';
            $this->admin_password_confirmation = '';
            $defaultUnit = UnitKerja::first();
            $this->admin_unit_id = $defaultUnit?->id;
        }

        $this->modalAdminOpen = true;
    }

    public function saveAdmin(): void
    {
        $rules = [
            'admin_nama' => ['required', 'string', 'max:255'],
            'admin_role' => ['required', 'string'],
            'admin_unit_id' => ['required', 'exists:unit_kerja,id'],
        ];

        if (! $this->adminId) {
            $rules['admin_username'] = ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,email'];
            $rules['admin_password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            if (! empty($this->admin_password)) {
                $rules['admin_password'] = ['string', 'min:8', 'confirmed'];
            }
        }

        $messages = [
            'admin_nama.required' => 'Nama lengkap administrator wajib diisi.',
            'admin_username.required' => 'Username wajib diisi.',
            'admin_username.unique' => 'Username / Email sudah terpakai.',
            'admin_password.required' => 'Password wajib diisi untuk admin baru.',
            'admin_password.min' => 'Password minimal 8 karakter.',
            'admin_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'admin_unit_id.required' => 'Unit kerja wajib dipilih.',
        ];

        $validated = $this->validate($rules, $messages);

        if (! $this->adminId) {
            $email = str_contains($validated['admin_username'], '@') ? $validated['admin_username'] : $validated['admin_username'].'@universitas.ac.id';

            $user = User::create([
                'name' => trim($validated['admin_nama']),
                'email' => $email,
                'password' => Hash::make($validated['admin_password']),
                'unit_kerja_id' => $validated['admin_unit_id'],
            ]);
            $user->syncRoles([$validated['admin_role']]);
            $pesan = 'Administrator baru berhasil ditambahkan.';
        } else {
            $user = User::findOrFail($this->adminId);
            $updateData = [
                'name' => trim($validated['admin_nama']),
                'unit_kerja_id' => $validated['admin_unit_id'],
            ];
            if (! empty($this->admin_password)) {
                $updateData['password'] = Hash::make($this->admin_password);
            }
            $user->update($updateData);
            $user->syncRoles([$validated['admin_role']]);
            $pesan = 'Data administrator berhasil diperbarui.';
        }

        $this->modalAdminOpen = false;
        $this->dispatch('show-toast', type: 'success', message: $pesan);
    }

    public function deleteAdmin(string $id): void
    {
        if (auth()->id() === $id) {
            $this->dispatch('show-toast', type: 'error', message: 'Anda tidak dapat menghapus akun Anda sendiri.');

            return;
        }

        $user = User::find($id);
        if ($user) {
            $user->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Data administrator berhasil dihapus.');
        }
    }

    // ==========================================
    // BACKUP, EXPORT & IMPORT ACTIONS
    // ==========================================
    public function exportPegawaiCsv(): StreamedResponse
    {
        $filename = 'Export_Pegawai_'.date('Ymd_His').'.csv';
        $pegawai = Pegawai::with('unitKerja')->latest()->get();

        return response()->streamDownload(function () use ($pegawai): void {
            // Send UTF-8 BOM for Microsoft Excel compatibility
            echo "\xEF\xBB\xBF";
            $handle = fopen('php://output', 'w');
            if ($handle !== false) {
                fputcsv($handle, ['NIP / NIDN', 'Nama Lengkap', 'Jabatan', 'Pangkat / Golongan', 'Unit Kerja']);
                foreach ($pegawai as $p) {
                    fputcsv($handle, [
                        $p->nip_nidn,
                        $p->nama,
                        $p->jabatan,
                        $p->golongan ?? '-',
                        $p->unitKerja?->nama_unit ?? '-',
                    ]);
                }
                fclose($handle);
            }
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportNomorSuratCsv(): StreamedResponse
    {
        $filename = 'Export_NomorSurat_'.date('Ymd_His').'.csv';
        $nomorSurat = MasterNomorSurat::with('unitKerja')->latest()->get();

        return response()->streamDownload(function () use ($nomorSurat): void {
            echo "\xEF\xBB\xBF";
            $handle = fopen('php://output', 'w');
            if ($handle !== false) {
                fputcsv($handle, ['Nomor / Pola Format', 'Jenis Surat', 'Unit Kerja', 'Tanggal Dibuat', 'Keterangan', 'Status']);
                foreach ($nomorSurat as $ns) {
                    fputcsv($handle, [
                        $ns->nomor_surat,
                        $ns->jenis_surat,
                        $ns->unitKerja?->nama_unit ?? 'Semua Unit',
                        $ns->tanggal_dibuat?->format('d/m/Y') ?? '-',
                        $ns->keterangan ?? '-',
                        $ns->status ? 'Aktif' : 'Nonaktif',
                    ]);
                }
                fclose($handle);
            }
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function backupData(): StreamedResponse
    {
        $filename = 'Backup_MasterData_'.date('Ymd_His').'.json';

        $dataRecords = [
            'pegawai' => Pegawai::all()->toArray(),
            'master_nomor_surat' => MasterNomorSurat::all()->toArray(),
            'master_opsi' => MasterOpsi::all()->toArray(),
            'pengaturan_dokumen' => PengaturanDokumen::all()->toArray(),
            'users' => User::with('roles')->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'unit_kerja_id' => $u->unit_kerja_id,
                    'role' => $u->roles->first()?->name,
                ];
            })->toArray(),
        ];

        $serializedData = (string) json_encode($dataRecords, JSON_UNESCAPED_UNICODE);
        $checksumSha256 = hash('sha256', $serializedData);

        $payload = [
            'metadata' => [
                'app_name' => AppConstants::APP_NAME,
                'exported_at' => Carbon::now()->toIso8601String(),
                'checksum_sha256' => $checksumSha256,
                'record_count' => [
                    'pegawai' => count($dataRecords['pegawai']),
                    'master_nomor_surat' => count($dataRecords['master_nomor_surat']),
                    'master_opsi' => count($dataRecords['master_opsi']),
                    'users' => count($dataRecords['users']),
                ],
            ],
            'data' => $dataRecords,
        ];

        return response()->streamDownload(function () use ($payload): void {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, ['Content-Type' => 'application/json']);
    }

    public function openImportModal(): void
    {
        $this->resetErrorBag();
        $this->importFile = null;
        $this->modalImportOpen = true;
    }

    public function importData(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:json,txt', 'max:5120'], // Max 5MB
        ], [
            'importFile.required' => 'Berkas cadangan JSON wajib dipilih.',
            'importFile.mimes' => 'Berkas cadangan harus dalam format .json.',
            'importFile.max' => 'Ukuran berkas maksimal 5MB.',
        ]);

        if ($this->importFile instanceof UploadedFile) {
            $content = (string) file_get_contents($this->importFile->getRealPath());
            $json = json_decode($content, true);

            if (! is_array($json)) {
                $this->addError('importFile', 'Format isi berkas JSON tidak valid.');

                return;
            }

            // Verify structure: support both legacy and new checksum formats
            $data = isset($json['data']) && is_array($json['data']) ? $json['data'] : $json;

            // Optional checksum check
            if (isset($json['metadata']['checksum_sha256'])) {
                $expected = $json['metadata']['checksum_sha256'];
                $actual = hash('sha256', (string) json_encode($data, JSON_UNESCAPED_UNICODE));
                if (! hash_equals($expected, $actual)) {
                    $this->addError('importFile', 'Peringatan: Hash integritas SHA-256 berkas cadangan tidak sesuai.');

                    return;
                }
            }

            DB::transaction(function () use ($data): void {
                // Restore Master Opsi
                if (isset($data['master_opsi']) && is_array($data['master_opsi'])) {
                    foreach ($data['master_opsi'] as $opsi) {
                        if (isset($opsi['kategori'], $opsi['nilai_opsi'])) {
                            MasterOpsi::simpanJikaBaru($opsi['kategori'], $opsi['nilai_opsi']);
                        }
                    }
                }
            });

            $this->modalImportOpen = false;
            $this->importFile = null;
            $this->dispatch('show-toast', type: 'success', message: 'Data master berhasil diverifikasi integritasnya dan dipulihkan.');
        }
    }

    public function render(): View
    {
        $pegawaiList = Pegawai::with('unitKerja')->latest()->paginate(10, ['*'], 'pegawaiPage');
        $nomorSuratList = MasterNomorSurat::with('unitKerja')->latest()->paginate(10, ['*'], 'nomorSuratPage');
        $adminList = User::with(['roles', 'unitKerja'])->latest()->paginate(10, ['*'], 'adminPage');
        $unitKerjaList = UnitKerja::all();
        $rolesList = Role::all();

        return view('livewire.data-master.index', [
            'pegawaiList' => $pegawaiList,
            'nomorSuratList' => $nomorSuratList,
            'adminList' => $adminList,
            'unitKerjaList' => $unitKerjaList,
            'rolesList' => $rolesList,
            'opsiJabatan' => $this->getSmartOpsi('jabatan_pegawai'),
            'opsiGolongan' => $this->getSmartOpsi('pangkat_golongan'),
            'opsiJenisSurat' => $this->getSmartOpsi('jenis_surat'),
        ]);
    }
}
