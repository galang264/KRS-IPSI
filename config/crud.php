<?php

return [
    'entities' => [
        'prodi' => [
            'title' => 'Program Studi', 'table' => 'prodi', 'primary' => 'kode_prodi', 'auto' => false,
            'columns' => ['kode_prodi' => 'Kode', 'nama_prodi' => 'Nama program studi', 'jenjang' => 'Jenjang'],
            'fields' => [
                'kode_prodi' => ['label' => 'Kode prodi', 'type' => 'text', 'rules' => 'required|string|max:10'],
                'nama_prodi' => ['label' => 'Nama program studi', 'type' => 'text', 'rules' => 'required|string|max:100', 'unique' => true],
                'jenjang' => ['label' => 'Jenjang', 'type' => 'select', 'rules' => 'required|in:D3,S1,S2,S3', 'options' => ['D3' => 'D3', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3']],
            ],
        ],
        'dosen' => [
            'title' => 'Dosen', 'table' => 'dosen', 'primary' => 'id_dosen', 'auto' => true,
            'columns' => ['id_dosen' => 'ID', 'nidn' => 'NIDN', 'nama' => 'Nama', 'email' => 'Email', 'kode_prodi' => 'Kode prodi'],
            'fields' => [
                'nidn' => ['label' => 'NIDN', 'type' => 'text', 'rules' => 'nullable|string|max:10', 'unique' => true],
                'nama' => ['label' => 'Nama dosen', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:100', 'unique' => true],
                'kode_prodi' => ['label' => 'Program studi', 'type' => 'select', 'rules' => 'required|string|exists:prodi,kode_prodi', 'options_from' => ['table' => 'prodi', 'key' => 'kode_prodi', 'label' => 'nama_prodi']],
            ],
        ],
        'periode' => [
            'title' => 'Periode Akademik', 'table' => 'periode', 'primary' => 'id_periode', 'auto' => false,
            'columns' => ['id_periode' => 'Kode periode', 'nama_periode' => 'Nama periode', 'tgl_mulai' => 'Tanggal mulai', 'tgl_selesai' => 'Tanggal selesai'],
            'fields' => [
                'id_periode' => ['label' => 'Kode periode', 'type' => 'text', 'rules' => 'required|string|max:5'],
                'nama_periode' => ['label' => 'Nama periode', 'type' => 'text', 'rules' => 'required|string|max:30'],
                'tgl_mulai' => ['label' => 'Tanggal mulai', 'type' => 'date', 'rules' => 'required|date'],
                'tgl_selesai' => ['label' => 'Tanggal selesai', 'type' => 'date', 'rules' => 'required|date|after_or_equal:tgl_mulai'],
            ],
        ],
        'mata-kuliah' => [
            'title' => 'Mata Kuliah', 'table' => 'mata_kuliah', 'primary' => 'kode_mk', 'auto' => false,
            'columns' => ['kode_mk' => 'Kode MK', 'nama_mk' => 'Nama mata kuliah', 'sks' => 'SKS', 'semester_ke' => 'Semester'],
            'fields' => [
                'kode_mk' => ['label' => 'Kode mata kuliah', 'type' => 'text', 'rules' => 'required|string|max:10'],
                'nama_mk' => ['label' => 'Nama mata kuliah', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'sks' => ['label' => 'SKS', 'type' => 'number', 'rules' => 'required|integer|min:1|max:6'],
                'semester_ke' => ['label' => 'Semester ke', 'type' => 'number', 'rules' => 'required|integer|min:1|max:14'],
            ],
        ],
        'mahasiswa' => [
            'title' => 'Mahasiswa', 'table' => 'mahasiswa', 'primary' => 'nim', 'auto' => false,
            'columns' => ['nim' => 'NIM', 'nama' => 'Nama', 'email' => 'Email', 'tahun_masuk' => 'Tahun masuk', 'status' => 'Status', 'kode_prodi' => 'Kode prodi', 'id_dosen_pa' => 'Dosen PA', 'no_hp' => 'Nomor HP'],
            'fields' => [
                'nim' => ['label' => 'NIM', 'type' => 'text', 'rules' => 'required|string|regex:/^[0-9]{8,12}$/|max:12'],
                'nama' => ['label' => 'Nama mahasiswa', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:100', 'unique' => true],
                'tahun_masuk' => ['label' => 'Tahun masuk', 'type' => 'number', 'rules' => 'required|integer|min:2000|max:2100'],
                'status' => ['label' => 'Status', 'type' => 'select', 'rules' => 'required|in:aktif,cuti,lulus', 'options' => ['aktif' => 'Aktif', 'cuti' => 'Cuti', 'lulus' => 'Lulus']],
                'kode_prodi' => ['label' => 'Program studi', 'type' => 'select', 'rules' => 'required|string|exists:prodi,kode_prodi', 'options_from' => ['table' => 'prodi', 'key' => 'kode_prodi', 'label' => 'nama_prodi']],
                'id_dosen_pa' => ['label' => 'Dosen PA (opsional)', 'type' => 'select', 'rules' => 'nullable|integer|exists:dosen,id_dosen', 'options_from' => ['table' => 'dosen', 'key' => 'id_dosen', 'label' => 'nama'], 'nullable_option' => true],
                'no_hp' => ['label' => 'Nomor HP (opsional)', 'type' => 'text', 'rules' => 'nullable|string|regex:/^[0-9+]{8,20}$/|max:20'],
            ],
        ],
        'kelas' => [
            'title' => 'Kelas Kuliah', 'table' => 'kelas', 'primary' => 'id_kelas', 'auto' => true,
            'columns' => ['id_kelas' => 'ID', 'kode_mk' => 'Kode MK', 'id_periode' => 'Periode', 'nama_kelas' => 'Nama kelas', 'id_dosen' => 'ID dosen', 'kuota' => 'Kuota'],
            'fields' => [
                'kode_mk' => ['label' => 'Mata kuliah', 'type' => 'select', 'rules' => 'required|string|exists:mata_kuliah,kode_mk', 'options_from' => ['table' => 'mata_kuliah', 'key' => 'kode_mk', 'label' => 'nama_mk']],
                'id_periode' => ['label' => 'Periode akademik', 'type' => 'select', 'rules' => 'required|string|exists:periode,id_periode', 'options_from' => ['table' => 'periode', 'key' => 'id_periode', 'label' => 'nama_periode']],
                'nama_kelas' => ['label' => 'Nama kelas', 'type' => 'text', 'rules' => 'required|string|max:5'],
                'id_dosen' => ['label' => 'Dosen pengajar', 'type' => 'select', 'rules' => 'required|integer|exists:dosen,id_dosen', 'options_from' => ['table' => 'dosen', 'key' => 'id_dosen', 'label' => 'nama']],
                'kuota' => ['label' => 'Kuota', 'type' => 'number', 'rules' => 'required|integer|min:1|max:1000'],
            ],
        ],
        'krs' => [
            'title' => 'KRS Mahasiswa', 'table' => 'krs', 'primary' => 'id_krs', 'auto' => true,
            'columns' => ['id_krs' => 'ID KRS', 'nim' => 'NIM', 'id_periode' => 'Periode', 'tgl_isi' => 'Tanggal isi', 'status' => 'Status'],
            'fields' => [
                'nim' => ['label' => 'Mahasiswa', 'type' => 'select', 'rules' => 'required|string|exists:mahasiswa,nim', 'options_from' => ['table' => 'mahasiswa', 'key' => 'nim', 'label' => 'nama']],
                'id_periode' => ['label' => 'Periode akademik', 'type' => 'select', 'rules' => 'required|string|exists:periode,id_periode', 'options_from' => ['table' => 'periode', 'key' => 'id_periode', 'label' => 'nama_periode']],
                'tgl_isi' => ['label' => 'Tanggal isi KRS', 'type' => 'date', 'rules' => 'required|date'],
                'status' => ['label' => 'Status KRS', 'type' => 'select', 'rules' => 'required|in:draft,diajukan,disetujui,ditolak', 'options' => ['draft' => 'Draft', 'diajukan' => 'Diajukan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak']],
            ],
        ],
        'krs-detail' => [
            'title' => 'Detail KRS (Relasi M:N)', 'table' => 'krs_detail', 'primary' => ['id_krs', 'id_kelas'], 'auto' => false,
            'columns' => ['id_krs' => 'ID KRS', 'id_kelas' => 'ID kelas', 'nilai_huruf' => 'Nilai'],
            'fields' => [
                'id_krs' => ['label' => 'KRS', 'type' => 'select', 'rules' => 'required|integer|exists:krs,id_krs', 'options_from' => ['table' => 'krs', 'key' => 'id_krs', 'label' => 'id_krs']],
                'id_kelas' => ['label' => 'Kelas', 'type' => 'select', 'rules' => 'required|integer|exists:kelas,id_kelas', 'options_from' => ['table' => 'kelas', 'key' => 'id_kelas', 'label' => 'id_kelas']],
                'nilai_huruf' => ['label' => 'Nilai huruf (opsional)', 'type' => 'select', 'rules' => 'nullable|in:A,AB,B,BC,C,D,E', 'options' => ['A' => 'A', 'AB' => 'AB', 'B' => 'B', 'BC' => 'BC', 'C' => 'C', 'D' => 'D', 'E' => 'E'], 'nullable_option' => true],
            ],
        ],
        'aturan-akademik' => [
            'title' => 'Aturan Akademik', 'table' => 'aturan_akademik', 'primary' => 'kode', 'auto' => false,
            'columns' => ['kode' => 'Kode aturan', 'nilai' => 'Nilai', 'keterangan' => 'Keterangan'],
            'fields' => [
                'kode' => ['label' => 'Kode aturan', 'type' => 'text', 'rules' => 'required|string|max:30'],
                'nilai' => ['label' => 'Nilai numerik', 'type' => 'number', 'rules' => 'required|integer|min:1|max:100'],
                'keterangan' => ['label' => 'Keterangan (opsional)', 'type' => 'text', 'rules' => 'nullable|string|max:200'],
            ],
        ],
    ],
];
