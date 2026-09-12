<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kerja', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->string('kode_unit', 50)->unique();
            $table->string('nama_unit');
            $table->string('level', 30)->default('prodi');
            $table->string('singkatan', 30)->nullable();
            $table->string('kepala_nama')->nullable();
            $table->string('kepala_nip', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('level');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kerja');
    }
};
