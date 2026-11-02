<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConstraintsTest extends TestCase
{
    use RefreshDatabase;

    private function basicData(): array
    {
        $now = now();
        DB::table('prodi')->insert(['kode_prodi' => 'SIF', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1', 'created_at' => $now, 'updated_at' => $now]);
        $dosen = DB::table('dosen')->insertGetId(['nidn' => '1111111111', 'nama' => 'Dosen Test', 'email' => 'dosen@test.local', 'kode_prodi' => 'SIF', 'created_at' => $now, 'updated_at' => $now]);
        DB::table('periode')->insert(['id_periode' => '20261', 'nama_periode' => 'Ganjil 2026', 'tgl_mulai' => '2026-08-01', 'tgl_selesai' => '2027-01-31', 'created_at' => $now, 'updated_at' => $now]);
        DB::table('mata_kuliah')->insert(['kode_mk' => 'MK001', 'nama_mk' => 'Basis Data', 'sks' => 3, 'semester_ke' => 3, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('mahasiswa')->insert(['nim' => '2023900001', 'nama' => 'Mahasiswa Test', 'email' => 'mhs@test.local', 'tahun_masuk' => 2023, 'status' => 'aktif', 'kode_prodi' => 'SIF', 'id_dosen_pa' => $dosen, 'created_at' => $now, 'updated_at' => $now]);
        $kelas = DB::table('kelas')->insertGetId(['kode_mk' => 'MK001', 'id_periode' => '20261', 'id_dosen' => $dosen, 'nama_kelas' => 'A', 'kuota' => 40, 'created_at' => $now, 'updated_at' => $now]);
        $krs = DB::table('krs')->insertGetId(['nim' => '2023900001', 'id_periode' => '20261', 'tgl_isi' => '2026-08-10', 'status' => 'draft', 'created_at' => $now, 'updated_at' => $now]);
        DB::table('aturan_akademik')->insert(['kode' => 'maks_sks', 'nilai' => 24, 'keterangan' => 'Batas SKS', 'created_at' => $now, 'updated_at' => $now]);

        return ['dosen' => $dosen, 'kelas' => $kelas, 'krs' => $krs];
    }

    public function test_primary_key_rejects_duplicate_program_study_code(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('prodi')->insert(['kode_prodi' => 'SIF', 'nama_prodi' => 'Sistem Informasi Kedua', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_not_null_rejects_program_study_without_name(): void
    {
        $this->expectException(QueryException::class);
        DB::table('prodi')->insert(['kode_prodi' => 'ABC', 'jenjang' => 'S1', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_foreign_key_rejects_student_with_unknown_program_study(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('mahasiswa')->insert(['nim' => '2023900002', 'nama' => 'Mhs Invalid', 'tahun_masuk' => 2023, 'status' => 'aktif', 'kode_prodi' => 'XXX', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_unique_email_rejects_duplicate_student_email(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('mahasiswa')->insert(['nim' => '2023900002', 'nama' => 'Mhs Duplikat', 'email' => 'mhs@test.local', 'tahun_masuk' => 2023, 'status' => 'aktif', 'kode_prodi' => 'SIF', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_check_rejects_student_year_before_2000(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('mahasiswa')->insert(['nim' => '1999000001', 'nama' => 'Mhs Tahun Salah', 'tahun_masuk' => 1999, 'status' => 'aktif', 'kode_prodi' => 'SIF', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_check_rejects_invalid_student_status(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('mahasiswa')->where('nim', '2023900001')->update(['status' => 'nonaktif']);
    }

    public function test_restrict_prevents_deleting_program_study_used_by_student(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('prodi')->where('kode_prodi', 'SIF')->delete();
    }

    public function test_cascade_deletes_krs_details_when_parent_krs_is_deleted(): void
    {
        $ids = $this->basicData();
        DB::table('krs_detail')->insert(['id_krs' => $ids['krs'], 'id_kelas' => $ids['kelas'], 'nilai_huruf' => null, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('krs')->where('id_krs', $ids['krs'])->delete();
        $this->assertDatabaseMissing('krs_detail', ['id_krs' => $ids['krs'], 'id_kelas' => $ids['kelas']]);
    }

    public function test_composite_primary_key_rejects_duplicate_krs_detail_pair(): void
    {
        $ids = $this->basicData();
        $row = ['id_krs' => $ids['krs'], 'id_kelas' => $ids['kelas'], 'nilai_huruf' => null, 'created_at' => now(), 'updated_at' => now()];
        DB::table('krs_detail')->insert($row);
        $this->expectException(QueryException::class);
        DB::table('krs_detail')->insert($row);
    }

    public function test_unique_krs_student_period_rejects_second_krs(): void
    {
        $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('krs')->insert(['nim' => '2023900001', 'id_periode' => '20261', 'tgl_isi' => '2026-08-10', 'status' => 'diajukan', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_set_null_clears_student_advisor_when_dosen_is_deleted(): void
    {
        $ids = $this->basicData();
        DB::table('kelas')->where('id_kelas', $ids['kelas'])->delete();
        DB::table('dosen')->where('id_dosen', $ids['dosen'])->delete();
        $this->assertDatabaseHas('mahasiswa', ['nim' => '2023900001', 'id_dosen_pa' => null]);
    }

    public function test_check_rejects_mata_kuliah_with_zero_sks(): void
    {
        $this->expectException(QueryException::class);
        DB::table('mata_kuliah')->insert(['kode_mk' => 'MK000', 'nama_mk' => 'SKS Salah', 'sks' => 0, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_check_rejects_invalid_grade_letter(): void
    {
        $ids = $this->basicData();
        $this->expectException(QueryException::class);
        DB::table('krs_detail')->insert(['id_krs' => $ids['krs'], 'id_kelas' => $ids['kelas'], 'nilai_huruf' => 'F', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function test_trigger_rejects_krs_above_maximum_credits(): void
    {
        $ids = $this->basicData();
        $classIds = [$ids['kelas']];
        $now = now();
        for ($i = 2; $i <= 9; $i++) {
            $code = sprintf('MK%03d', $i);
            DB::table('mata_kuliah')->insert(['kode_mk' => $code, 'nama_mk' => 'Mata Kuliah ' . $i, 'sks' => 3, 'semester_ke' => 3, 'created_at' => $now, 'updated_at' => $now]);
            $classIds[$i] = DB::table('kelas')->insertGetId(['kode_mk' => $code, 'id_periode' => '20261', 'id_dosen' => $ids['dosen'], 'nama_kelas' => 'A', 'kuota' => 40, 'created_at' => $now, 'updated_at' => $now]);
        }
        foreach (range(1, 8) as $number) {
            DB::table('krs_detail')->insert(['id_krs' => $ids['krs'], 'id_kelas' => $classIds[$number], 'nilai_huruf' => null, 'created_at' => $now, 'updated_at' => $now]);
        }

        $this->expectException(QueryException::class);
        DB::table('krs_detail')->insert(['id_krs' => $ids['krs'], 'id_kelas' => $classIds[9], 'nilai_huruf' => null, 'created_at' => $now, 'updated_at' => $now]);
    }
}
