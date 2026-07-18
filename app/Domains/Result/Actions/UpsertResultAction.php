<?php

namespace App\Domains\Result\Actions;

use App\Domains\Result\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpsertResultAction
{
    public function execute(
        ?Result $result,
        array $data,
    ): Result {
        $data = $this->normalize($data);

        $validated = Validator::make(
            $data,
            $this->rules($result, $data),
        )->validate();

        return DB::transaction(function () use (
            $result,
            $validated,
        ): Result {
            $result ??= new Result();

            $result->fill($validated);
            $result->save();

            return $result->refresh();
        });
    }

    private function normalize(array $data): array
    {
        $data['notes'] = $this->nullableTrim(
            $data['notes'] ?? null
        );

        return $data;
    }

    private function rules(
        ?Result $result,
        array $data,
    ): array {
        return [
            'market_id' => [
                'required',
                'integer',
                'exists:markets,id',
                Rule::unique('results', 'market_id')
                    ->where(
                        fn ($query) => $query->whereDate(
                            'result_date',
                            $data['result_date'] ?? null
                        )
                    )
                    ->ignore($result?->getKey()),
            ],

            'result_date' => [
                'required',
                'date',
            ],

            'winning_numbers' => [
                'required',
                'string',
                'max:500',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    private function nullableTrim(
        mixed $value,
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === ''
            ? null
            : $value;
    }
}
