<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_opsi', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('tipe', 50); // 'jenis_surat', 'kategori_arsip'
            $table->string('nama', 150);
            $table->foreignUuid('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tipe', 'nama', 'unit_kerja_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_opsi');
    }
};
