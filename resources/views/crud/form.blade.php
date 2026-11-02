@extends('layouts.app', ['title' => ($mode === 'create' ? 'Tambah ' : 'Ubah ') . $definition['title']])

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $mode === 'create' ? 'Tambah data' : 'Ubah data' }}</h1>
            <p class="lead">{{ $definition['title'] }}</p>
        </div>
        <a class="btn btn-light" href="{{ route('crud.index', $entity) }}">Kembali</a>
    </div>
    <div class="panel">
        <form method="POST" action="{{ $mode === 'create' ? route('crud.store', $entity) : route('crud.update', [$entity, request()->route('key')]) }}">
            @csrf
            @if ($mode === 'edit') @method('PUT') @endif
            <div class="form-grid">
                @foreach ($definition['fields'] as $field => $meta)
                    @php $value = old($field, $record->{$field} ?? ''); @endphp
                    <div class="field">
                        <label for="{{ $field }}">{{ $meta['label'] }}</label>
                        @if (($meta['type'] ?? 'text') === 'select')
                            <select id="{{ $field }}" name="{{ $field }}" @if (str_contains($meta['rules'] ?? '', 'required')) required @endif>
                                @if (!empty($meta['nullable_option'])) <option value="">-- Tidak dipilih --</option> @endif
                                @foreach (($options[$field] ?? []) as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @else
                            <input id="{{ $field }}" name="{{ $field }}" type="{{ $meta['type'] ?? 'text' }}" value="{{ $value }}" @if (str_contains($meta['rules'] ?? '', 'required')) required @endif>
                        @endif
                        @error($field)<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">{{ $mode === 'create' ? 'Simpan data' : 'Simpan perubahan' }}</button>
                <a class="btn btn-light" href="{{ route('crud.index', $entity) }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
