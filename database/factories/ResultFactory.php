<?php

namespace Database\Factories;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Result>
 */
class ResultFactory extends Factory
{
    protected $model = Result::class;

    public function configure(): static
    {
        return $this->afterMaking(function (Result $result): void {
            if ($result->market_id === null) {
                return;
            }

            $result->brand_id = Market::query()
                ->find($result->market_id)
                ?->brand_id;
        });
    }

    public function definition(): array
    {
        return [
            'market_id' => Market::factory(),
            'result_date' => fake()->dateTimeBetween(
                'today',
                '+30 days'
            )->format('Y-m-d'),
            'winning_numbers' => implode(' ', [
                fake()->numerify('####'),
                fake()->numerify('####'),
                fake()->numerify('####'),
            ]),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
