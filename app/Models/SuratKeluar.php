<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'surat_keluar';

    /** @var list<string> */
    protected $fillable = [
        'unit_kerja_id',
        'nomor_agenda',
        'nomor_surat',
        'kode_klasifikasi',
        'tujuan',
        'tanggal_surat',
        'perihal',
        'jenis_surat',
        'file_path',
        'file_mime',
        'file_size',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'file_size' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<UnitKerja, $this>
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
