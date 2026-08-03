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
            'market' => $this->normalizeString(
                $this->input('market')
            ),
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
        ];
    }

    public function messages(): array
    {
        return [
            'market.exists' => 'Pasaran yang dipilih tidak tersedia.',
        ];
    }

    /**
     * @return array{market:string|null}
     */
    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'market' => $validated['market'] ?? null,
        ];
    }

    private function normalizeString(
        mixed $value
    ): ?string {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
