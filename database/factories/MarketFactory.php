<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Market\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Market>
 */
class MarketFactory extends Factory
{
    protected $model = Market::class;

    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'brand_id' => Brand::factory(),
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1, 2, 3, 4, 5, 6, 7],
            'open_time' => '09:00',
            'close_time' => '18:00',
            'result_time' => '19:00',
            'is_holiday' => false,
            'holiday_note' => null,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}
