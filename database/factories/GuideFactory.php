<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Guide\Models\Guide;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Guide> */
class GuideFactory extends Factory
{
    protected $model = Guide::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);
        return [
            'brand_id' => Brand::factory(), 'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'excerpt' => fake()->sentence(), 'content' => '<p>'.fake()->paragraph().'</p>',
            'category' => 'Umum', 'status' => Guide::STATUS_DRAFT,
            'published_at' => null, 'sort_order' => 0,
            'seo_title' => null, 'seo_description' => null, 'notes' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => ['status' => Guide::STATUS_PUBLISHED, 'published_at' => now()->subMinute()]);
    }
}
