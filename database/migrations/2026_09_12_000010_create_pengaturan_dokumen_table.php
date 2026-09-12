<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_dokumen', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('nama_institusi')->default('Universitas Digital Nusantara');
            $table->string('nama_fakultas')->nullable()->default('Fakultas Teknologi Informasi dan Komunikasi');
            $table->text('alamat_lengkap')->nullable()->default('Jl. Prof. Dr. Soepomo No. 12, Kompleks Kampus Terpadu, Semarang');
            $table->string('telepon')->nullable()->default('(024) 8412345');
            $table->string('email')->nullable()->default('info@universitas.ac.id');
            $table->string('website')->nullable()->default('www.universitas.ac.id');
            $table->string('logo_path')->nullable();
            $table->string('kota_penerbitan')->default('Semarang');
            $table->string('nama_penanggung_jawab')->nullable()->default('Prof. Dr. Ir. H. M. Haris, M.T.');
            $table->string('jabatan_penanggung_jawab')->nullable()->default('Rektor Universitas');
            $table->string('nip_penanggung_jawab')->nullable()->default('196508121990031002');
            $table->text('catatan_footer')->nullable()->default('Dokumen ini diterbitkan secara elektronik melalui Sistem Arisparis Surat Digital.');
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_dokumen');
    }
};
