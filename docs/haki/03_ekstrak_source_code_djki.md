# EKSTRAK KODE SUMBER PROGRAM (SOURCE CODE SNIPPET)
## KELENGKAPAN PERMOHONAN HAK CIPTA — DJKI KEMENKUMHAM RI

**Judul Ciptaan**: Sistem Tata Usaha & Kearsipan Naskah Dinas Digital Perguruan Tinggi Terintegrasi (Arsiparis Surat Digital)  
**Bahasa Pemrograman**: PHP 8.4+ / JavaScript / CSS  
**Framework**: Laravel 13.x / Livewire 3.x / Tailwind CSS v4  

---

### BAGIAN 1: POTONGAN AWAL KODE PROGRAM (FIRST CODE EXTRACT)

#### 1.1 `app/Actions/Surat/GenerateNomorSuratAction.php`
*(Algoritma Penomoran Otomatis Naskah Dinas Anti-Race Condition)*

```php
<?php

declare(strict_types=1);

namespace App\Actions\Surat;

use App\DTOs\NomorSuratResult;
use App\Models\MasterNomorSurat;
use Illuminate\Support\Facades\DB;

final class GenerateNomorSuratAction
{
    private const array BULAN_ROMAWI = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
        7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    public function execute(string $masterNomorId, ?int $tahun = null, ?int $bulan = null): NomorSuratResult
    {
        $tahun = $tahun ?? (int) date('Y');
        $bulan = $bulan ?? (int) date('n');

        return DB::transaction(function () use ($masterNomorId, $tahun, $bulan): NomorSuratResult {
            /** @var MasterNomorSurat $master */
            $master = MasterNomorSurat::query()
                ->where('id', $masterNomorId)
                ->with('unitKerja')
                ->lockForUpdate()
                ->firstOrFail();

            if ($master->tahun !== $tahun) {
                $master->nomor_terakhir = 0;
                $master->tahun = $tahun;
            }

            $nextNumber = $master->nomor_terakhir + 1;
            $master->nomor_terakhir = $nextNumber;
            $master->save();

            $bulanRomawi = self::BULAN_ROMAWI[$bulan] ?? 'I';
            $nomorFormatted = str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

            $nomorSurat = str_replace(
                ['{NOMOR}', '{BULAN_ROMAWI}', '{TAHUN}'],
                [$nomorFormatted, $bulanRomawi, (string) $tahun],
                $master->format_pola
            );

            $bulanDuaDigit = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
            $nomorAgenda = sprintf('REG-SK/%d/%s/%s', $tahun, $bulanDuaDigit, $nomorFormatted);

            return new NomorSuratResult(
                nomorSurat: $nomorSurat,
                nomorAgenda: $nomorAgenda,
                urutan: $nextNumber,
                kodeKlasifikasi: $master->kode_klasifikasi,
            );
        });
    }
}
```

---

#### 1.2 `app/Http/Controllers/DocumentStreamController.php`
*(Algoritma Streaming Dokumen Aman Anti-IDOR dengan Verifikasi Tanda Tangan URL)*

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DocumentStreamController extends Controller
{
    public function __invoke(Request $request, string $path): StreamedResponse
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Tautan dokumen tidak sah atau telah kedaluwarsa.');
        }

        $disk = Storage::disk('private');

        if (! $disk->exists($path)) {
            abort(404, 'Dokumen kearsipan tidak ditemukan.');
        }

        $mimeType = $disk->mimeType($path) ?? 'application/pdf';

        return response()->stream(function () use ($disk, $path): void {
            $stream = $disk->readStream($path);
            if ($stream !== false) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
        ]);
    }
}
```

---

### BAGIAN 2: POTONGAN AKHIR KODE PROGRAM (LAST CODE EXTRACT)

#### 2.1 `app/Livewire/DataMaster/Index.php` (Metode Backup SHA-256 & Ekspor CSV UTF-8)

```php
    public function exportPegawaiCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-pegawai-'.now()->format('Ymd-His').'.csv"',
        ];

        return response()->stream(function (): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }
            // UTF-8 BOM untuk Microsoft Excel Indonesia
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['NIP/NIDN', 'Nama Lengkap', 'Jabatan', 'Unit Kerja', 'Golongan', 'Email', 'No Telepon', 'Status']);

            Pegawai::query()->with('unitKerja')->chunk(200, function ($pegawaiList) use ($handle): void {
                foreach ($pegawaiList as $p) {
                    fputcsv($handle, [
                        $p->nip_nidn,
                        $p->nama,
                        $p->jabatan,
                        $p->unitKerja?->nama_unit ?? '-',
                        $p->golongan ?? '-',
                        $p->email ?? '-',
                        $p->no_telepon ?? '-',
                        $p->is_active ? 'Aktif' : 'Non-Aktif',
                    ]);
                }
            });
            fclose($handle);
        }, 200, $headers);
    }

    public function backupData(): StreamedResponse
    {
        $data = [
            'metadata' => [
                'aplikasi' => 'Arsiparis Surat Digital Perguruan Tinggi',
                'versi' => '1.0.0',
                'timestamp' => now()->toIso8601String(),
                'checksum_sha256' => '',
            ],
            'unit_kerja' => UnitKerja::all()->toArray(),
            'pegawai' => Pegawai::all()->toArray(),
            'master_nomor' => MasterNomorSurat::all()->toArray(),
            'pengaturan_dokumen' => PengaturanDokumen::all()->toArray(),
            'master_opsi' => MasterOpsi::all()->toArray(),
        ];

        $serialized = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $checksum = hash('sha256', (string) $serialized);
        $data['metadata']['checksum_sha256'] = $checksum;

        $finalJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return response()->streamDownload(function () use ($finalJson): void {
            echo $finalJson;
        }, 'backup-arsiparis-master-'.now()->format('Ymd-His').'.json', [
            'Content-Type' => 'application/json',
        ]);
    }
```

---

#### 2.2 `tests/Feature/TinkerSequenceTest.php`
*(Pengujian Sekuensial Siklus Hidup Dokumen Persuratan & Isolasi Storage)*

```php
test('sekuensi komprehensif persuratan: surat masuk, disposisi, surat keluar dan arsip digital', function (): void {
    $admin = User::where('email', 'admin@universitas.ac.id')->firstOrFail();
    $unitKerja = UnitKerja::where('kode_unit', 'REK-01')->firstOrFail();
    $tujuanUnit = UnitKerja::where('kode_unit', 'FTIK-01')->firstOrFail();

    // 1. Verifikasi Magic Bytes PDF Header
    $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n>>\nendobj\ntrailer\n<<\n/Root 1 0 R\n>>\n%%EOF";
    $fakePdf = UploadedFile::fake()->createWithContent('surat_dinas.pdf', $pdfContent);
    $storedPath = $fakePdf->store('surat-masuk', 'private');
    expect(str_starts_with((string) Storage::disk('private')->get($storedPath), '%PDF-'))->toBeTrue();

    // 2. Disposisi Berjenjang Lintas Unit
    $rektor = Pegawai::where('unit_kerja_id', $unitKerja->id)->firstOrFail();
    $dekan = Pegawai::where('unit_kerja_id', $tujuanUnit->id)->firstOrFail();
    $disposisi = RiwayatDisposisi::create([
        'surat_masuk_id' => $suratMasuk->id,
        'dari_pegawai_id' => $rektor->id,
        'ke_pegawai_id' => $dekan->id,
        'instruksi' => 'Tindak lanjuti pembentukan tim proposal hibah penelitian FTIK.',
        'status' => StatusDisposisi::Diproses,
    ]);
    expect($disposisi->id)->toBeString();

    // 3. Isolasi Arsip Digital di Private Disk
    $archiveFile = UploadedFile::fake()->createWithContent('sk_rektor_2026.pdf', $pdfContent);
    $archivePath = $archiveFile->store('arsip-digital', 'private');
    expect(Storage::disk('private')->exists($archivePath))->toBeTrue()
        ->and(Storage::disk('public')->exists($archivePath))->toBeFalse();
});
```
