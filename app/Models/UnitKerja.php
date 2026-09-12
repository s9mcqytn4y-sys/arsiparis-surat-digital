<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LevelUnitKerja;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'unit_kerja';

    /** @var list<string> */
    protected $fillable = [
        'parent_id',
        'kode_unit',
        'nama_unit',
        'level',
        'singkatan',
        'kepala_nama',
        'kepala_nip',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => LevelUnitKerja::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<self, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<self, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Pegawai, $this>
     */
    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'unit_kerja_id');
    }

    /**
     * @return HasMany<MasterNomorSurat, $this>
     */
    public function masterNomorSurat(): HasMany
    {
        return $this->hasMany(MasterNomorSurat::class, 'unit_kerja_id');
    }

    /**
     * @return HasMany<SuratMasuk, $this>
     */
    public function suratMasuk(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'unit_kerja_id');
    }

    /**
     * @return HasMany<SuratKeluar, $this>
     */
    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'unit_kerja_id');
    }

    /**
     * @return HasMany<ArsipDigital, $this>
     */
    public function arsipDigital(): HasMany
    {
        return $this->hasMany(ArsipDigital::class, 'unit_kerja_id');
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_kerja_id');
    }
}
