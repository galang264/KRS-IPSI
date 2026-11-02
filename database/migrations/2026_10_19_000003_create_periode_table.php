<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->string('id_periode', 5);
            $table->string('nama_periode', 30);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->timestamps();
            $table->primary('id_periode', 'pk_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};
