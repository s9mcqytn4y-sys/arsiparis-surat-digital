<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_disposisi', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('surat_masuk_id')->constrained('surat_masuk')->cascadeOnDelete();
            $table->foreignUuid('dari_pegawai_id')->constrained('pegawai')->restrictOnDelete();
            $table->foreignUuid('ke_pegawai_id')->constrained('pegawai')->restrictOnDelete();
            $table->text('instruksi');
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->date('tenggat_waktu')->nullable();
            $table->string('status', 30)->default('menunggu');
            $table->timestamps();

            $table->index(['surat_masuk_id', 'created_at']);
            $table->index('ke_pegawai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_disposisi');
    }
};
