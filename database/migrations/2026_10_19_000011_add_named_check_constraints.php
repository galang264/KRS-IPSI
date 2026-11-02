
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Constraint tabel periode
        DB::statement("
            ALTER TABLE periode
            ADD CONSTRAINT ck_periode_dates
            CHECK (tgl_selesai >= tgl_mulai)
        ");

        // 2. Constraint tabel mata_kuliah
        DB::statement("
            ALTER TABLE mata_kuliah
            ADD CONSTRAINT ck_mk_sks
            CHECK (sks BETWEEN 1 AND 6)
        ");

        DB::statement("
            ALTER TABLE mata_kuliah
            ADD CONSTRAINT ck_mk_semester
            CHECK (semester_ke BETWEEN 1 AND 14)
        ");

        // 3. Constraint tabel mahasiswa
        DB::statement("
            ALTER TABLE mahasiswa
            ADD CONSTRAINT ck_mahasiswa_tahun
            CHECK (tahun_masuk BETWEEN 2000 AND 2100)
        ");

        DB::statement("
            ALTER TABLE mahasiswa
            ADD CONSTRAINT ck_mahasiswa_status
            CHECK (status IN ('aktif', 'cuti', 'lulus'))
        ");

        DB::statement("
            ALTER TABLE mahasiswa
            ADD CONSTRAINT ck_mahasiswa_no_hp
            CHECK (
                no_hp IS NULL
                OR no_hp ~ '^[0-9+]{8,20}$'
            )
        ");

        // 4. Constraint tabel kelas
        DB::statement("
            ALTER TABLE kelas
            ADD CONSTRAINT ck_kelas_kuota
            CHECK (kuota BETWEEN 1 AND 1000)
        ");

        // 5. Constraint tabel krs
        DB::statement("
            ALTER TABLE krs
            ADD CONSTRAINT ck_krs_status
            CHECK (status IN ('draft', 'diajukan', 'disetujui', 'ditolak'))
        ");

        // 6. Constraint tabel krs_detail
        DB::statement("
            ALTER TABLE krs_detail
            ADD CONSTRAINT ck_krs_detail_nilai
            CHECK (
                nilai_huruf IS NULL
                OR nilai_huruf IN ('A', 'AB', 'B', 'BC', 'C', 'D', 'E')
            )
        ");

        // 7. Constraint tabel aturan_akademik
        DB::statement("
            ALTER TABLE aturan_akademik
            ADD CONSTRAINT ck_aturan_nilai
            CHECK (nilai BETWEEN 1 AND 100)
        ");
    }

    public function down(): void
    {
        // Menghapus constraint secara berurutan.

        DB::statement("
            ALTER TABLE aturan_akademik
            DROP CONSTRAINT IF EXISTS ck_aturan_nilai
        ");

        DB::statement("
            ALTER TABLE krs_detail
            DROP CONSTRAINT IF EXISTS ck_krs_detail_nilai
        ");

        DB::statement("
            ALTER TABLE krs
            DROP CONSTRAINT IF EXISTS ck_krs_status
        ");

        DB::statement("
            ALTER TABLE kelas
            DROP CONSTRAINT IF EXISTS ck_kelas_kuota
        ");

        DB::statement("
            ALTER TABLE mahasiswa
            DROP CONSTRAINT IF EXISTS ck_mahasiswa_no_hp
        ");

        DB::statement("
            ALTER TABLE mahasiswa
            DROP CONSTRAINT IF EXISTS ck_mahasiswa_status
        ");

        DB::statement("
            ALTER TABLE mahasiswa
            DROP CONSTRAINT IF EXISTS ck_mahasiswa_tahun
        ");

        DB::statement("
            ALTER TABLE mata_kuliah
            DROP CONSTRAINT IF EXISTS ck_mk_semester
        ");

        DB::statement("
            ALTER TABLE mata_kuliah
            DROP CONSTRAINT IF EXISTS ck_mk_sks
        ");

        DB::statement("
            ALTER TABLE periode
            DROP CONSTRAINT IF EXISTS ck_periode_dates
        ");
    }
};
