<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoKrsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ([
            ['kode_prodi' => 'SIF', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['kode_prodi' => 'TIF', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
        ] as $row) {
            DB::table('prodi')->updateOrInsert(['kode_prodi' => $row['kode_prodi']], $row + ['created_at' => $now, 'updated_at' => $now]);
        }

        foreach ([
            ['nidn' => '0012345678', 'nama' => 'Dr. Sigit Anggoro', 'email' => 'sigit@example.test', 'kode_prodi' => 'SIF'],
            ['nidn' => '0098765432', 'nama' => 'Irma Santikarama', 'email' => 'irma@example.test', 'kode_prodi' => 'SIF'],
        ] as $row) {
            DB::table('dosen')->updateOrInsert(['nidn' => $row['nidn']], $row + ['created_at' => $now, 'updated_at' => $now]);
        }
        $dosen1 = (int) DB::table('dosen')->where('nidn', '0012345678')->value('id_dosen');
        $dosen2 = (int) DB::table('dosen')->where('nidn', '0098765432')->value('id_dosen');

        foreach ([
            ['id_periode' => '20261', 'nama_periode' => 'Ganjil 2026/2027', 'tgl_mulai' => '2026-08-01', 'tgl_selesai' => '2027-01-31'],
            ['id_periode' => '20262', 'nama_periode' => 'Genap 2026/2027', 'tgl_mulai' => '2027-02-01', 'tgl_selesai' => '2027-07-31'],
        ] as $row) {
            DB::table('periode')->updateOrInsert(['id_periode' => $row['id_periode']], $row + ['created_at' => $now, 'updated_at' => $now]);
        }

        $courses = [
            ['kode_mk' => 'SI611001', 'nama_mk' => 'Basis Data', 'sks' => 3, 'semester_ke' => 3],
            ['kode_mk' => 'SI611002', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester_ke' => 3],
            ['kode_mk' => 'SI611003', 'nama_mk' => 'Analisis Proses Bisnis', 'sks' => 3, 'semester_ke' => 3],
            ['kode_mk' => 'SI611004', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester_ke' => 4],
            ['kode_mk' => 'SI611005', 'nama_mk' => 'Manajemen Data', 'sks' => 3, 'semester_ke' => 4],
            ['kode_mk' => 'SI611006', 'nama_mk' => 'Interaksi Manusia dan Komputer', 'sks' => 3, 'semester_ke' => 4],
            ['kode_mk' => 'SI611007', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester_ke' => 3],
            ['kode_mk' => 'SI611008', 'nama_mk' => 'Statistika', 'sks' => 3, 'semester_ke' => 2],
            ['kode_mk' => 'SI611009', 'nama_mk' => 'Etika Profesi', 'sks' => 3, 'semester_ke' => 2],
        ];
        foreach ($courses as $row) {
            DB::table('mata_kuliah')->updateOrInsert(['kode_mk' => $row['kode_mk']], $row + ['created_at' => $now, 'updated_at' => $now]);
        }

        foreach ([
            ['nim' => '2023900001', 'nama' => 'Andi Pratama', 'email' => 'andi@example.test', 'tahun_masuk' => 2023, 'status' => 'aktif', 'kode_prodi' => 'SIF', 'id_dosen_pa' => $dosen1, 'no_hp' => '081234567890'],
            ['nim' => '2023900002', 'nama' => 'Bunga Lestari', 'email' => 'bunga@example.test', 'tahun_masuk' => 2023, 'status' => 'aktif', 'kode_prodi' => 'SIF', 'id_dosen_pa' => $dosen2, 'no_hp' => '081234567891'],
            ['nim' => '2024900003', 'nama' => 'Dimas Saputra', 'email' => 'dimas@example.test', 'tahun_masuk' => 2024, 'status' => 'aktif', 'kode_prodi' => 'TIF', 'id_dosen_pa' => null, 'no_hp' => null],
        ] as $row) {
            DB::table('mahasiswa')->updateOrInsert(['nim' => $row['nim']], $row + ['created_at' => $now, 'updated_at' => $now]);
        }

        foreach ($courses as $index => $course) {
            $dosenId = $index % 2 === 0 ? $dosen1 : $dosen2;
            DB::table('kelas')->updateOrInsert(
                ['kode_mk' => $course['kode_mk'], 'nama_kelas' => 'A'],
                ['id_periode' => '20261', 'id_dosen' => $dosenId, 'kuota' => 40, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        DB::table('aturan_akademik')->updateOrInsert(
            ['kode' => 'maks_sks'],
            ['nilai' => 24, 'keterangan' => 'Batas maksimum SKS yang boleh diambil dalam satu periode', 'created_at' => $now, 'updated_at' => $now]
        );

        DB::table('krs')->updateOrInsert(
            ['nim' => '2023900001', 'id_periode' => '20261'],
            ['tgl_isi' => '2026-08-10', 'status' => 'disetujui', 'created_at' => $now, 'updated_at' => $now]
        );
        DB::table('krs')->updateOrInsert(
            ['nim' => '2023900002', 'id_periode' => '20261'],
            ['tgl_isi' => '2026-08-10', 'status' => 'diajukan', 'created_at' => $now, 'updated_at' => $now]
        );
        $krsAndi = (int) DB::table('krs')->where('nim', '2023900001')->where('id_periode', '20261')->value('id_krs');
        $kelasIds = DB::table('kelas')->whereIn('kode_mk', ['SI611001', 'SI611002', 'SI611003'])->pluck('id_kelas');
        foreach ($kelasIds as $idKelas) {
            DB::table('krs_detail')->updateOrInsert(
                ['id_krs' => $krsAndi, 'id_kelas' => $idKelas],
                ['nilai_huruf' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
