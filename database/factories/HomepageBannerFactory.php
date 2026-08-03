<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<HomepageBanner> */
final class HomepageBannerFactory extends Factory
{
    protected $model = HomepageBanner::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(),
            'desktop_image_path' => 'homepage-banners/example-desktop.jpg',
            'mobile_image_path' => null,
            'cta_label' => 'Lihat Selengkapnya',
            'cta_url' => 'https://example.com',
            'focal_point' => 'center',
            'status' => HomepageBanner::STATUS_DRAFT,
            'published_at' => null,
            'expires_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => HomepageBanner::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
            'expires_at' => null,
        ]);
    }
}
