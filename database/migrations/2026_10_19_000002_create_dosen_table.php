<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->bigIncrements('id_dosen');
            $table->string('nidn', 10)->nullable();
            $table->string('nama', 100);
            $table->string('email', 100)->nullable();
            $table->string('kode_prodi', 10);
            $table->timestamps();
            $table->unique('nidn', 'uq_dosen_nidn');
            $table->unique('email', 'uq_dosen_email');
            $table->foreign('kode_prodi', 'fk_dosen_prodi')->references('kode_prodi')->on('prodi')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
