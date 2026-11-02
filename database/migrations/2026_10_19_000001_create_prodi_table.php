<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prodi', function (Blueprint $table) {
            $table->string('kode_prodi', 10);
            $table->string('nama_prodi', 100);
            $table->string('jenjang', 3);
            $table->timestamps();
            $table->primary('kode_prodi', 'pk_prodi');
            $table->unique('nama_prodi', 'uq_prodi_nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
