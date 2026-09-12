<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\KategoriArsip;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipDigital extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'arsip_digital';

    /** @var list<string> */
    protected $fillable = [
        'unit_kerja_id',
        'judul',
        'kategori',
        'nomor_dokumen',
        'tanggal_dokumen',
        'deskripsi',
        'file_path',
        'file_mime',
        'file_size',
        'pegawai_id',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kategori' => KategoriArsip::class,
            'tanggal_dokumen' => 'date',
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
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
