<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignUuid('unit_kerja_id')->nullable()->after('id')->constrained('unit_kerja')->nullOnDelete();
            $table->string('role', 30)->default('petugas_tu')->after('email');
            $table->boolean('is_active')->default(true)->after('role');

            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['unit_kerja_id']);
            $table->dropColumn(['unit_kerja_id', 'role', 'is_active']);
        });
    }
};
