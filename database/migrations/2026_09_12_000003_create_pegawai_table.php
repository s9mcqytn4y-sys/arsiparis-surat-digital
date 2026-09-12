<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('unit_kerja_id')->constrained('unit_kerja')->restrictOnDelete();
            $table->string('nip_nidn', 50)->unique();
            $table->string('nama');
            $table->string('jabatan', 100);
            $table->string('golongan', 20)->nullable();
            $table->string('no_telepon', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_penandatangan')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('unit_kerja_id');
            $table->index('is_penandatangan');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
