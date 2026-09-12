<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_nomor_surat', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('unit_kerja_id')->constrained('unit_kerja')->restrictOnDelete();
            $table->string('kode_klasifikasi', 50);
            $table->string('nama_klasifikasi');
            $table->string('format_pola');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->unsignedSmallInteger('tahun');
            $table->date('tanggal_dibuat')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['unit_kerja_id', 'kode_klasifikasi', 'tahun'], 'uq_unit_klasifikasi_tahun');
            $table->index(['unit_kerja_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_nomor_surat');
    }
};
