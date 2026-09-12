<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pegawai';

    /** @var list<string> */
    protected $fillable = [
        'unit_kerja_id',
        'nip_nidn',
        'nama',
        'jabatan',
        'golongan',
        'no_telepon',
        'email',
        'is_penandatangan',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_penandatangan' => 'boolean',
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

    /**
     * @return HasMany<SuratMasuk, $this>
     */
    public function disposisiSuratMasuk(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'disposisi_kepada');
    }

    /**
     * @return HasMany<SuratKeluar, $this>
     */
    public function suratKeluarDitandatangani(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'penandatangan_id');
    }

    /**
     * @return HasMany<ArsipDigital, $this>
     */
    public function arsipDigital(): HasMany
    {
        return $this->hasMany(ArsipDigital::class, 'pegawai_id');
    }

    /**
     * @return HasOne<User, $this>
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
}
