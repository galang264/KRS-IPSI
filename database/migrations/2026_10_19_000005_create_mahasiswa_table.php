
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 12);
            $table->string('nama', 100);
            $table->string('email', 100)->nullable();

            // Tipe data disesuaikan untuk PostgreSQL.
            $table->smallInteger('tahun_masuk');

            $table->string('status', 10)->default('aktif');
            $table->string('kode_prodi', 10);
            $table->bigInteger('id_dosen_pa')->nullable();

            $table->timestamps();

            // Primary key.
            $table->primary('nim', 'pk_mahasiswa');

            // Unique email.
            $table->unique('email', 'uq_mahasiswa_email');

            // Foreign key ke tabel prodi.
            $table->foreign('kode_prodi', 'fk_mahasiswa_prodi')
                ->references('kode_prodi')
                ->on('prodi')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Foreign key ke tabel dosen.
            $table->foreign('id_dosen_pa', 'fk_mahasiswa_dosen_pa')
                ->references('id_dosen')
                ->on('dosen')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
