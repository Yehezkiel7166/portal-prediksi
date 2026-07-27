<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\JackpotProof\Models\JackpotProof;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JackpotProofFactory extends Factory
{
    protected $model = JackpotProof::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'brand_id' => Brand::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->paragraph(),
            'image_path' => 'jackpot-proofs/example.jpg',
            'thumbnail_path' => null,
            'status' => JackpotProof::STATUS_DRAFT,
            'moderated_at' => null,
            'moderated_by' => null,
            'published_at' => null,
            'sort_order' => 0,
            'seo_title' => null,
            'seo_description' => null,
            'moderation_notes' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => JackpotProof::STATUS_APPROVED,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);
    }
}
