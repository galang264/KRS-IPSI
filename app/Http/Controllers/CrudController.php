<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CrudController extends \Illuminate\Routing\Controller
{
    private function definition(string $entity): array
    {
        $entities = config('crud.entities', []);
        abort_unless(array_key_exists($entity, $entities), 404);

        return $entities[$entity];
    }

    private function primaryColumns(array $definition): array
    {
        return is_array($definition['primary']) ? $definition['primary'] : [$definition['primary']];
    }

    private function keyToken(object $record, array $definition): string
    {
        $values = [];
        foreach ($this->primaryColumns($definition) as $column) {
            $values[] = (string) $record->{$column};
        }

        return implode('_', $values);
    }

    private function applyKey($query, array $definition, string $token)
    {
        $columns = $this->primaryColumns($definition);
        $values = count($columns) === 1 ? [$token] : explode('_', $token);
        abort_unless(count($columns) === count($values), 404);

        foreach ($columns as $index => $column) {
            $query->where($column, $values[$index]);
        }

        return $query;
    }

    private function findRecord(string $entity, string $token): object
    {
        $definition = $this->definition($entity);
        $record = $this->applyKey(DB::table($definition['table']), $definition, $token)->first();
        abort_unless($record, 404);

        return $record;
    }

    private function optionsFor(array $definition): array
    {
        $options = [];
        foreach ($definition['fields'] as $field => $meta) {
            if (isset($meta['options'])) {
                $options[$field] = $meta['options'];
            } elseif (isset($meta['options_from'])) {
                $source = $meta['options_from'];
                $rows = DB::table($source['table'])
                    ->orderBy($source['label'])
                    ->get([$source['key'], $source['label']]);
                $options[$field] = $rows->mapWithKeys(fn ($row) => [(string) $row->{$source['key']} => (string) $row->{$source['label']}])->all();
            }
        }

        return $options;
    }

    private function validationRules(array $definition, ?object $record = null): array
    {
        $rules = [];
        $primaryColumns = $this->primaryColumns($definition);
        $primaryValue = null;
        if ($record && count($primaryColumns) === 1) {
            $primaryValue = $record->{$primaryColumns[0]};
        }

        foreach ($definition['fields'] as $field => $meta) {
            $fieldRules = $meta['rules'] ?? 'nullable';
            if (!empty($meta['unique'])) {
                $unique = Rule::unique($definition['table'], $field);
                if ($record && count($primaryColumns) === 1) {
                    $unique->ignore($primaryValue, $primaryColumns[0]);
                }
                $fieldRules = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);
                $fieldRules[] = $unique;
            }
            $rules[$field] = $fieldRules;
        }

        return $rules;
    }

    public function dashboard()
    {
        return view('dashboard', ['entities' => config('crud.entities', [])]);
    }

    public function index(string $entity)
    {
        $definition = $this->definition($entity);
        $firstKey = $this->primaryColumns($definition)[0];
        $records = DB::table($definition['table'])->orderBy($firstKey)->paginate(15);
        foreach ($records as $record) {
            $record->_crud_key = $this->keyToken($record, $definition);
        }

        return view('crud.index', compact('entity', 'definition', 'records'));
    }

    public function create(string $entity)
    {
        $definition = $this->definition($entity);
        $options = $this->optionsFor($definition);

        return view('crud.form', ['entity' => $entity, 'definition' => $definition, 'record' => null, 'options' => $options, 'mode' => 'create']);
    }

    public function store(Request $request, string $entity)
    {
        $definition = $this->definition($entity);
        $validated = $request->validate($this->validationRules($definition));
        $payload = [];
        foreach ($definition['fields'] as $field => $meta) {
            $payload[$field] = $validated[$field] ?? null;
        }
        $payload['created_at'] = now();
        $payload['updated_at'] = now();

        try {
            DB::table($definition['table'])->insert($payload);
        } catch (QueryException $exception) {
            return back()->withInput()->with('error', 'Data tidak dapat disimpan. Periksa duplikasi atau relasi foreign key. Detail: ' . $exception->getMessage());
        }

        return redirect()->route('crud.index', $entity)->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(string $entity, string $key)
    {
        $definition = $this->definition($entity);
        $record = $this->findRecord($entity, $key);
        $options = $this->optionsFor($definition);

        return view('crud.form', compact('entity', 'definition', 'record', 'options') + ['mode' => 'edit']);
    }

    public function update(Request $request, string $entity, string $key)
    {
        $definition = $this->definition($entity);
        $record = $this->findRecord($entity, $key);
        $validated = $request->validate($this->validationRules($definition, $record));
        $payload = [];
        foreach ($definition['fields'] as $field => $meta) {
            $payload[$field] = $validated[$field] ?? null;
        }
        $payload['updated_at'] = now();

        try {
            $this->applyKey(DB::table($definition['table']), $definition, $key)->update($payload);
        } catch (QueryException $exception) {
            return back()->withInput()->with('error', 'Data tidak dapat diperbarui. Periksa duplikasi atau relasi foreign key. Detail: ' . $exception->getMessage());
        }

        return redirect()->route('crud.index', $entity)->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $entity, string $key)
    {
        $definition = $this->definition($entity);
        try {
            $this->applyKey(DB::table($definition['table']), $definition, $key)->delete();
        } catch (QueryException $exception) {
            return redirect()->route('crud.index', $entity)->with('error', 'Data tidak dapat dihapus karena masih dipakai tabel lain atau melanggar aturan database.');
        }

        return redirect()->route('crud.index', $entity)->with('success', 'Data berhasil dihapus.');
    }
}
