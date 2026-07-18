<?php

namespace App\Domains\Market\Actions;

use App\Domains\Market\Models\Market;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpsertMarketAction
{
    public function execute(
        ?Market $market,
        array $data,
    ): Market {
        $data = $this->normalize($data);

        $validated = Validator::make(
            $data,
            $this->rules($market),
        )->validate();

        return DB::transaction(function () use (
            $market,
            $validated,
        ): Market {
            $market ??= new Market;

            $market->fill($validated);
            $market->save();

            return $market->refresh();
        });
    }

    private function normalize(array $data): array
    {
        $data['code'] = Str::upper(
            trim((string) ($data['code'] ?? ''))
        );

        $data['name'] = trim(
            (string) ($data['name'] ?? '')
        );

        $slug = trim((string) ($data['slug'] ?? ''));

        $data['slug'] = Str::slug(
            $slug !== '' ? $slug : $data['name']
        );

        $data['timezone'] = trim(
            (string) ($data['timezone'] ?? 'Asia/Jakarta')
        );

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['notes'] = $this->nullableTrim($data['notes'] ?? null);

        return $data;
    }

    private function rules(?Market $market): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('markets', 'code')
                    ->ignore($market?->getKey()),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'slug' => [
                'required',
                'string',
                'max:120',
                Rule::unique('markets', 'slug')
                    ->ignore($market?->getKey()),
            ],
            'timezone' => [
                'required',
                'string',
                'max:100',
                'timezone:all',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    private function nullableTrim(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
