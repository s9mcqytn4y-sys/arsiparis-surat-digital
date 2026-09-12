<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('unit_kerja_id')->constrained('unit_kerja')->restrictOnDelete();
            $table->string('nomor_agenda', 50);
            $table->string('nomor_surat', 100)->unique();
            $table->string('kode_klasifikasi', 50);
            $table->string('tujuan');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->text('ringkasan')->nullable();
            $table->foreignUuid('penandatangan_id')->constrained('pegawai')->restrictOnDelete();
            $table->string('file_path')->nullable();
            $table->string('file_mime', 50)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['unit_kerja_id', 'nomor_agenda']);
            $table->index(['unit_kerja_id', 'kode_klasifikasi']);
            $table->index(['unit_kerja_id', 'tanggal_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
