<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterNomorSurat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_nomor_surat';

    /** @var list<string> */
    protected $fillable = [
        'unit_kerja_id',
        'kode_klasifikasi',
        'nama_klasifikasi',
        'format_pola',
        'nomor_terakhir',
        'tahun',
        'tanggal_dibuat',
        'keterangan',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nomor_terakhir' => 'integer',
            'tahun' => 'integer',
            'tanggal_dibuat' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<UnitKerja, $this>
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
