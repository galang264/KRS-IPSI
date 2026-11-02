<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->index('nama', 'idx_mahasiswa_nama');
        });
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->index('nama_mk', 'idx_mata_kuliah_nama');
        });
        Schema::table('krs', function (Blueprint $table) {
            $table->index('status', 'idx_krs_status');
        });
    }

    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            $table->dropIndex('idx_krs_status');
        });
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropIndex('idx_mata_kuliah_nama');
        });
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropIndex('idx_mahasiswa_nama');
        });
    }
};
