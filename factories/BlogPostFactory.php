<?php

namespace Database\Factories;

use App\Domains\Brand\Models\Brand;
use App\Domains\Blog\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'brand_id' => Brand::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'excerpt' => fake()->sentence(),
            'content' => fake()->paragraphs(5, true),
            'image_source' => BlogPost::IMAGE_SOURCE_UPLOAD,
            'image_path' => 'blog/example.jpg',
            'image_url' => null,
            'focal_point' => 'center',
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
            'sort_order' => 0,
            'seo_title' => null,
            'seo_description' => null,
            'notes' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);
    }
}
