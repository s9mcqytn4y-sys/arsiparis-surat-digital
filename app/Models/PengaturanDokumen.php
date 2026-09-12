<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengaturanDokumen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pengaturan_dokumen';

    /** @var list<string> */
    protected $fillable = [
        'nama_institusi',
        'nama_fakultas',
        'alamat_lengkap',
        'telepon',
        'email',
        'website',
        'logo_path',
        'kota_penerbitan',
        'nama_penanggung_jawab',
        'jabatan_penanggung_jawab',
        'nip_penanggung_jawab',
        'catatan_footer',
        'is_active',
        'updated_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Dapatkan konfigurasi Kop Surat yang aktif, atau buat baru dari default.
     */
    public static function getAktif(): self
    {
        /** @var self|null $aktif */
        $aktif = self::where('is_active', true)->first();

        if ($aktif !== null) {
            return $aktif;
        }

        return self::create([
            'nama_institusi' => 'Universitas Digital Nusantara',
            'nama_fakultas' => 'Fakultas Teknologi Informasi dan Komunikasi',
            'alamat_lengkap' => 'Jl. Prof. Dr. Soepomo No. 12, Kompleks Kampus Terpadu, Semarang',
            'telepon' => '(024) 8412345',
            'email' => 'info@universitas.ac.id',
            'website' => 'www.universitas.ac.id',
            'kota_penerbitan' => 'Semarang',
            'nama_penanggung_jawab' => 'Prof. Dr. Ir. H. M. Haris, M.T.',
            'jabatan_penanggung_jawab' => 'Rektor Universitas',
            'nip_penanggung_jawab' => '196508121990031002',
            'catatan_footer' => 'Dokumen ini diterbitkan secara elektronik melalui Sistem Arisparis Surat Digital.',
            'is_active' => true,
        ]);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function modifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
