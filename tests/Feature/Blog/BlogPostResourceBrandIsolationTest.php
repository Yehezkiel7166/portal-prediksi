<?php

namespace Tests\Feature\Blog;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class BlogPostResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_query_only_returns_blog_posts_for_current_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentBlogPost = BlogPost::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        BlogPost::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $blogPostIds = BlogPostResource::getEloquentQuery()
            ->pluck('id')
            ->all();

        $this->assertSame(
            [$currentBlogPost->id],
            $blogPostIds,
        );
    }
}
