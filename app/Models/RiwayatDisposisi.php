<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusDisposisi;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatDisposisi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'riwayat_disposisi';

    /** @var list<string> */
    protected $fillable = [
        'surat_masuk_id',
        'dari_pegawai_id',
        'ke_pegawai_id',
        'instruksi',
        'catatan_tindak_lanjut',
        'tenggat_waktu',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tenggat_waktu' => 'date',
            'status' => StatusDisposisi::class,
        ];
    }

    /**
     * @return BelongsTo<SuratMasuk, $this>
     */
    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    /**
     * @return BelongsTo<Pegawai, $this>
     */
    public function pemberiDisposisi(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'dari_pegawai_id');
    }

    /**
     * @return BelongsTo<Pegawai, $this>
     */
    public function penerimaDisposisi(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'ke_pegawai_id');
    }
}
