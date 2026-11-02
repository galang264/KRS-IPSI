<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->bigIncrements('id_krs');
            $table->string('nim', 12);
            $table->string('id_periode', 5);
            $table->date('tgl_isi');
            $table->string('status', 10)->default('draft');
            $table->timestamps();
            $table->unique(['nim', 'id_periode'], 'uq_krs_mahasiswa_periode');
            $table->foreign('nim', 'fk_krs_mahasiswa')->references('nim')->on('mahasiswa')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_periode', 'fk_krs_periode')->references('id_periode')->on('periode')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
