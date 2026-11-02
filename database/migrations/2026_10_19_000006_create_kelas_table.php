
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->bigIncrements('id_kelas');

            // FK ke mata_kuliah
            $table->string('kode_mk', 15);

            // FK ke periode
            $table->string('id_periode', 5);

            // FK ke dosen
            $table->bigInteger('id_dosen');

            $table->string('nama_kelas', 5);
            $table->smallInteger('kuota')->default(40);

            $table->timestamps();

            // Unique kode mata kuliah dan nama kelas
            $table->unique(
                ['kode_mk', 'nama_kelas'],
                'uq_kelas_mk_nama'
            );

            // Relasi ke mata_kuliah
            $table->foreign('kode_mk', 'fk_kelas_mata_kuliah')
                ->references('kode_mk')
                ->on('mata_kuliah')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Relasi ke periode
            $table->foreign('id_periode', 'fk_kelas_periode')
                ->references('id_periode')
                ->on('periode')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Relasi ke dosen
            $table->foreign('id_dosen', 'fk_kelas_dosen')
                ->references('id_dosen')
                ->on('dosen')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
