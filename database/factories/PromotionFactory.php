<?php

namespace Database\Factories;

use App\Domains\Promotion\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'excerpt' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'media_source' => Promotion::MEDIA_SOURCE_UPLOAD,
            'media_path' => 'promotions/example.jpg',
            'media_url' => null,
            'embed_url' => null,
            'focal_point' => 'center',
            'status' => Promotion::STATUS_DRAFT,
            'published_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => Promotion::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);
    }
}
