<?php

namespace Database\Factories;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LiveDraw>
 */
class LiveDrawFactory extends Factory
{
    protected $model = LiveDraw::class;

    public function definition(): array
    {
        $title = fake()->unique()->city().' Live Draw';

        return [
            'market_id' => Market::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(
                1,
                9999
            ),
            'provider' => LiveDraw::PROVIDER_OFFICIAL,
            'stream_type' => LiveDraw::STREAM_TYPE_URL,
            'source_url' => 'https://example.com/live-draw',
            'draw_days' => [1, 2, 3, 4, 5, 6, 7],
            'draw_time' => '20:00:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_OFFLINE,
            'headline' => fake()->sentence(),
            'footer' => fake()->optional()->sentence(),
            'logo_path' => null,
            'background_path' => null,
            'background_focal_point' => 'center',
            'priority' => fake()->numberBetween(0, 100),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function live(): static
    {
        return $this->state(fn (): array => [
            'status' => LiveDraw::STATUS_LIVE,
        ]);
    }
}
