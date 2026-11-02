
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_akademik', function (Blueprint $table) {
            $table->string('kode', 30);

            // Menggunakan SMALLINT untuk PostgreSQL.
            $table->smallInteger('nilai');

            $table->string('keterangan', 200)->nullable();
            $table->timestamps();

            $table->primary('kode', 'pk_aturan_akademik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_akademik');
    }
};
