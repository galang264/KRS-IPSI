
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->string('kode_mk', 15);
            $table->string('nama_mk', 100);

            // Menggunakan SMALLINT untuk PostgreSQL.
            $table->smallInteger('sks');
            $table->smallInteger('semester_ke');

            $table->timestamps();

            $table->primary('kode_mk', 'pk_mata_kuliah');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
