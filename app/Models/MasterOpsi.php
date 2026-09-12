<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterOpsi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_opsi';

    /** @var list<string> */
    protected $fillable = [
        'tipe',
        'nama',
        'unit_kerja_id',
        'created_by',
    ];

    /**
     * @return BelongsTo<UnitKerja, $this>
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Dapatkan daftar opsi untuk tipe tertentu (dengan fallback opsi bawaan).
     * Jika ada opsi baru yang dimasukkan pengguna, opsi tersebut akan otomatis disimpan permanen.
     *
     * @return list<string>
     */
    public static function getOpsi(string $tipe, ?string $unitKerjaId = null): array
    {
        $defaultOptions = match ($tipe) {
            'jenis_surat' => [
                'Surat Undangan',
                'Surat Tugas',
                'Surat Keputusan',
                'Surat Edaran',
                'Surat Permohonan',
                'Surat Keterangan',
                'Nota Dinas',
                'Surat Pengumuman',
            ],
            'kategori_arsip' => [
                'Surat Keputusan Rektor',
                'Surat Keputusan Dekan',
                'Dokumen Akreditasi',
                'Kurikulum & Silabus',
                'Perjanjian Kerjasama (MoU/MoA)',
                'Arsip Kepegawaian',
                'Laporan Keuangan',
                'Notulen Rapat',
            ],
            'pengirim_surat' => [
                'Kemendikbudristek Ditjen Dikti',
                'Lembaga Layanan Dikti (LLDIKTI) Region VI',
                'Dinas Pendidikan & Kebudayaan',
                'Rektorat Universitas',
                'Fakultas Teknologi Informasi dan Komunikasi',
                'Badan Akreditasi Nasional (BAN-PT)',
                'Mitra Industri / Perusahaan',
            ],
            'tujuan_surat' => [
                'Seluruh Dosen & Tenaga Kependidikan',
                'Dekan FTIK',
                'Wakil Rektor Bidang Akademik',
                'Ketua Program Studi',
                'Kepala Bagian Tata Usaha',
                'Mahasiswa Peserta Yudisium',
            ],
            default => [],
        };

        $dbOptions = self::query()
            ->where('tipe', $tipe)
            ->when($unitKerjaId, function ($q) use ($unitKerjaId): void {
                $q->where(function ($sq) use ($unitKerjaId): void {
                    $sq->whereNull('unit_kerja_id')->orWhere('unit_kerja_id', $unitKerjaId);
                });
            })
            ->pluck('nama')
            ->toArray();

        $all = array_values(array_unique(array_merge($defaultOptions, $dbOptions)));
        sort($all);

        return $all;
    }

    /**
     * Simpan opsi baru secara otomatis dan permanen jika belum ada di database.
     */
    public static function simpanJikaBaru(string $tipe, string $nama, ?string $unitKerjaId = null, ?string $userId = null): void
    {
        $namaClean = trim($nama);
        if ($namaClean === '') {
            return;
        }

        $exists = self::query()
            ->where('tipe', $tipe)
            ->whereRaw('LOWER(nama) = ?', [mb_strtolower($namaClean)])
            ->exists();

        if (! $exists) {
            self::create([
                'tipe' => $tipe,
                'nama' => $namaClean,
                'unit_kerja_id' => $unitKerjaId,
                'created_by' => $userId,
            ]);
        }
    }
}
