<?php

namespace App\Domains\Market\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpsertMarketAction
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {
    }

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
            $isCreating = $market === null;
            $market ??= new Market;

            if ($isCreating) {
                $market->brand_id = $this->brandContext
                    ->get()
                    ?->getKey();
            }

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

        $data['active_days'] = collect($data['active_days'] ?? [])
            ->map(fn (mixed $day): int => (int) $day)
            ->filter(fn (int $day): bool => $day >= 1 && $day <= 7)
            ->unique()
            ->sort()
            ->values()
            ->all();
        $data['open_time'] = $this->normalizeTime($data['open_time'] ?? null);
        $data['close_time'] = $this->normalizeTime($data['close_time'] ?? null);
        $data['result_time'] = $this->normalizeTime($data['result_time'] ?? null);
        $data['is_holiday'] = (bool) ($data['is_holiday'] ?? false);
        $data['holiday_note'] = $this->nullableTrim($data['holiday_note'] ?? null);
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
            'active_days' => [
                'array',
            ],
            'active_days.*' => [
                'integer',
                'between:1,7',
            ],
            'close_time' => [
                'nullable',
                'date_format:H:i',
            ],
            'result_time' => [
                'nullable',
                'date_format:H:i',
                'after:close_time',
            ],
            'open_time' => [
                'nullable',
                'date_format:H:i',
                'after:result_time',
            ],
            'is_holiday' => [
                'required',
                'boolean',
            ],
            'holiday_note' => [
                'nullable',
                'string',
                'max:255',
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

    private function normalizeTime(mixed $value): ?string
    {
        $value = $this->nullableTrim($value);

        return $value === null ? null : substr($value, 0, 5);
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
