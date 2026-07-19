<?php

namespace App\Domains\Prediction\Actions;

use App\Core\Contracts\Clock;
use App\Domains\Prediction\Models\Prediction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpsertPredictionAction
{
    public function __construct(
        private readonly Clock $clock,
    ) {}

    public function execute(
        ?Prediction $prediction,
        array $data,
    ): Prediction {
        $data = $this->normalize($data);

        $validated = Validator::make(
            $data,
            $this->rules($prediction, $data),
        )->validate();

        return DB::transaction(function () use (
            $prediction,
            $validated,
        ): Prediction {
            $prediction ??= new Prediction;

            $prediction->fill($validated);
            $prediction->save();

            return $prediction->refresh();
        });
    }

    private function normalize(array $data): array
    {
        $data['predicted_numbers'] = trim(
            (string) ($data['predicted_numbers'] ?? '')
        );

        $data['status'] ??= Prediction::STATUS_DRAFT;
        $data['notes'] = $this->nullableTrim($data['notes'] ?? null);

        if ($data['status'] === Prediction::STATUS_PUBLISHED) {
            $data['published_at'] ??= $this->clock->now();
        } else {
            $data['published_at'] = null;
        }

        return $data;
    }

    private function rules(
        ?Prediction $prediction,
        array $data,
    ): array {
        return [
            'market_id' => [
                'required',
                'integer',
                Rule::exists('markets', 'id'),
                Rule::unique('predictions', 'market_id')
                    ->where(
                        fn ($query) => $query->whereDate(
                            'prediction_date',
                            $data['prediction_date'] ?? null
                        )
                    )
                    ->ignore($prediction?->getKey()),
            ],
            'prediction_date' => [
                'required',
                'date',
            ],
            'predicted_numbers' => [
                'required',
                'string',
                'max:500',
            ],
            'status' => [
                'required',
                Rule::in(array_keys(Prediction::statusOptions())),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'published_at' => [
                'nullable',
                'date',
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
