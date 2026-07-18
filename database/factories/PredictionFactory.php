<?php

namespace Database\Factories;

use App\Domains\Prediction\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prediction>
 */
class PredictionFactory extends Factory
{
    protected $model = Prediction::class;

    public function definition(): array
    {
        return [
            'market' => strtoupper(
                fake()->unique()->lexify('market-????')
            ),
            'prediction_date' => fake()->dateTimeBetween(
                'today',
                '+30 days'
            )->format('Y-m-d'),
            'predicted_numbers' => implode(' ', [
                fake()->numerify('####'),
                fake()->numerify('####'),
                fake()->numerify('####'),
            ]),
            'status' => Prediction::STATUS_DRAFT,
            'notes' => fake()->optional()->sentence(),
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (): array => [
            'status' => Prediction::STATUS_ARCHIVED,
            'published_at' => null,
        ]);
    }
}
