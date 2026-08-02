<?php

namespace App\Domains\Prediction\Actions;

use App\Core\Contracts\Clock;
use App\Domains\Market\Models\Market;
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

            $market = Market::query()
                ->findOrFail($validated['market_id']);

            $prediction->brand_id = $market->brand_id;
            $prediction->fill($validated);
            $prediction->save();

            return $prediction->refresh();
        });
    }

    private function normalize(array $data): array
    {
        foreach ([
            'bbfs',
            'colok_bebas',
            'prediction_2d',
            'prediction_3d',
            'prediction_4d',
            'kembar',
            'shio',
        ] as $field) {
            $data[$field] = $this->nullableTrim(
                $data[$field] ?? null
            );
        }

        $legacyNumbers = trim(
            (string) ($data['predicted_numbers'] ?? '')
        );

        $data['predicted_numbers'] = $legacyNumbers !== ''
            ? $legacyNumbers
            : $this->buildLegacyPredictionSummary($data);

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
                'max:5000',
            ],
            'bbfs' => [
                'nullable',
                'string',
                'max:500',
            ],
            'colok_bebas' => [
                'nullable',
                'string',
                'max:500',
            ],
            'prediction_2d' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'prediction_3d' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'prediction_4d' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'kembar' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'shio' => [
                'nullable',
                'string',
                'max:100',
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

    private function buildLegacyPredictionSummary(array $data): string
    {
        return collect([
            'BBFS' => $data['bbfs'],
            'Colok Bebas' => $data['colok_bebas'],
            '2D' => $data['prediction_2d'],
            '3D' => $data['prediction_3d'],
            '4D' => $data['prediction_4d'],
            'Kembar' => $data['kembar'],
            'Shio' => $data['shio'],
        ])
            ->filter(
                static fn (?string $value): bool =>
                    $value !== null
            )
            ->map(
                static fn (string $value, string $label): string =>
                    $label.': '.$value
            )
            ->implode(PHP_EOL);
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
