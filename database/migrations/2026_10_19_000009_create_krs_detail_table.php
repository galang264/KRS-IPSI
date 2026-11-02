
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs_detail', function (Blueprint $table) {

            // Foreign key ke tabel krs
            $table->bigInteger('id_krs');

            // Foreign key ke tabel kelas
            $table->bigInteger('id_kelas');

            // Nilai mahasiswa, boleh kosong
            $table->string('nilai_huruf', 2)->nullable();

            $table->timestamps();

            // Primary key gabungan
            $table->primary(
                ['id_krs', 'id_kelas'],
                'pk_krs_detail'
            );

            // Relasi ke tabel krs
            $table->foreign('id_krs', 'fk_krs_detail_krs')
                ->references('id_krs')
                ->on('krs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Relasi ke tabel kelas
            $table->foreign('id_kelas', 'fk_krs_detail_kelas')
                ->references('id_kelas')
                ->on('kelas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs_detail');
    }
};
