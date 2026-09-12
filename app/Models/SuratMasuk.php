<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusDisposisi;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'surat_masuk';

    /** @var list<string> */
    protected $fillable = [
        'unit_kerja_id',
        'nomor_agenda',
        'nomor_surat',
        'pengirim',
        'tanggal_surat',
        'tanggal_terima',
        'perihal',
        'disposisi_kepada',
        'instruksi_disposisi',
        'status_disposisi',
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
            'tanggal_terima' => 'date',
            'status_disposisi' => StatusDisposisi::class,
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
     * @return BelongsTo<Pegawai, $this>
     */
    public function pegawaiDisposisi(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'disposisi_kepada');
    }

    /**
     * @return BelongsTo<Pegawai, $this>
     */
    public function disposisiPegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'disposisi_kepada');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<RiwayatDisposisi, $this>
     */
    public function riwayatDisposisi(): HasMany
    {
        return $this->hasMany(RiwayatDisposisi::class, 'surat_masuk_id')->orderBy('created_at', 'asc');
    }
}
