<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ResultIndexRequest extends FormRequest
{
    protected $redirectRoute = 'results.index';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'market' => $this->normalizeString($this->input('market')),
            'date' => $this->normalizeString($this->input('date')),
        ]);
    }

    public function rules(): array
    {
        return [
            'market' => [
                'nullable',
                'string',
                'max:120',
                Rule::exists('markets', 'slug')
                    ->where('is_active', true),
            ],
            'date' => [
                'nullable',
                'date_format:Y-m-d',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'market.exists' => 'Pasaran yang dipilih tidak tersedia.',
            'date.date_format' => 'Format tanggal harus menggunakan YYYY-MM-DD.',
        ];
    }

    /**
     * @return array{
     *     market: string|null,
     *     date: string|null
     * }
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'market' => $validated['market'] ?? null,
            'date' => $validated['date'] ?? null,
        ];
    }

    private function normalizeString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
