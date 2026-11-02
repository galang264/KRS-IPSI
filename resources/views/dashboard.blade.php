@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard Database KRS</h1>
            <p class="lead">Pilih data yang ingin dikelola. Tidak ada halaman login/logout sesuai kebutuhan proyek.</p>
        </div>
    </div>
    <div class="cards">
        @foreach ($entities as $slug => $entity)
            <a class="entity-card" href="{{ route('crud.index', $slug) }}">
                <strong>{{ $entity['title'] }}</strong>
                <span>Tampilkan, tambah, ubah, dan hapus data</span>
            </a>
        @endforeach
    </div>
    <div class="footer-note">Database memuat tabel master, transaksi KRS, detail KRS sebagai relasi many-to-many, constraint, index, serta aturan batas SKS.</div>
@endsection
