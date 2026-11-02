@extends('layouts.app', ['title' => $definition['title']])

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $definition['title'] }}</h1>
            <p class="lead">Kelola data menggunakan operasi CRUD.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('crud.create', $entity) }}">+ Tambah data</a>
    </div>
    <div class="panel">
        @if ($records->count() === 0)
            <p>Belum ada data. Tekan <strong>Tambah data</strong> untuk mulai mengisi.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr>@foreach ($definition['columns'] as $label)<th>{{ $label }}</th>@endforeach<th>Aksi</th></tr></thead>
                    <tbody>
                    @foreach ($records as $record)
                        <tr>
                            @foreach ($definition['columns'] as $column => $label)
                                <td>{{ $record->{$column} ?? '-' }}</td>
                            @endforeach
                            <td>
                                <div class="actions">
                                    <a class="btn btn-light" href="{{ route('crud.edit', [$entity, $record->_crud_key]) }}">Ubah</a>
                                    <form method="POST" action="{{ route('crud.destroy', [$entity, $record->_crud_key]) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $records->links() }}</div>
        @endif
    </div>
@endsection
