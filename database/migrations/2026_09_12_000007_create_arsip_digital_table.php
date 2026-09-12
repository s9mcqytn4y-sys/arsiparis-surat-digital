<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_digital', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('unit_kerja_id')->constrained('unit_kerja')->restrictOnDelete();
            $table->string('judul');
            $table->string('kategori', 50);
            $table->string('nomor_dokumen', 100)->nullable();
            $table->date('tanggal_dokumen');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->string('file_mime', 50);
            $table->unsignedBigInteger('file_size');
            $table->foreignUuid('pegawai_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['unit_kerja_id', 'kategori']);
            $table->index(['unit_kerja_id', 'tanggal_dokumen']);
            $table->index('pegawai_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_digital');
    }
};
