<?php

namespace Tests\Feature\Blog;

use App\Domains\Blog\Actions\UpsertBlogPostAction;
use App\Domains\Blog\Models\BlogPost;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BlogModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_normalized_blog_post(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $post = app(UpsertBlogPostAction::class)->execute([
            'title' => '  Panduan Prediksi Togel  ',
            'slug' => '',
            'excerpt' => 'Ringkasan artikel.',
            'content' => 'Isi artikel.',
            'image_source' => 'url',
            'image_path' => 'must-be-cleared.jpg',
            'image_url' => 'https://example.com/blog.jpg',
            'focal_point' => 'center',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 10,
            'seo_title' => 'SEO Panduan Prediksi',
            'seo_description' => 'Deskripsi SEO artikel.',
            'notes' => null,
        ]);

        $this->assertSame(
            'Panduan Prediksi Togel',
            $post->title
        );

        $this->assertSame(
            'panduan-prediksi-togel',
            $post->slug
        );

        $this->assertSame('url', $post->image_source);
        $this->assertNull($post->image_path);

        $this->assertSame(
            'https://example.com/blog.jpg',
            $post->image_url
        );
    }

    public function test_action_updates_existing_blog_post(): void
    {
        $post = BlogPost::factory()->create();

        $updated = app(UpsertBlogPostAction::class)->execute([
            'title' => 'Artikel Diperbarui',
            'slug' => 'artikel-diperbarui',
            'excerpt' => null,
            'content' => 'Konten baru.',
            'image_source' => 'upload',
            'image_path' => 'blog/updated.jpg',
            'image_url' => null,
            'focal_point' => 'top',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 2,
            'seo_title' => null,
            'seo_description' => null,
            'notes' => null,
        ], $post);

        $this->assertSame($post->id, $updated->id);
        $this->assertSame('Artikel Diperbarui', $updated->title);
        $this->assertSame('top', $updated->focal_point);
    }

    public function test_slug_must_be_unique(): void
    {
        BlogPost::factory()->create([
            'slug' => 'artikel-unik',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertBlogPostAction::class)->execute([
            'title' => 'Artikel Duplikat',
            'slug' => 'artikel-unik',
            'excerpt' => null,
            'content' => null,
            'image_source' => 'upload',
            'image_path' => 'blog/duplicate.jpg',
            'image_url' => null,
            'focal_point' => 'center',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 0,
            'seo_title' => null,
            'seo_description' => null,
            'notes' => null,
        ]);
    }

    public function test_url_source_requires_valid_url(): void
    {
        $this->expectException(ValidationException::class);

        app(UpsertBlogPostAction::class)->execute([
            'title' => 'Artikel URL Tidak Valid',
            'slug' => 'artikel-url-tidak-valid',
            'excerpt' => null,
            'content' => null,
            'image_source' => 'url',
            'image_path' => null,
            'image_url' => 'not-a-url',
            'focal_point' => 'center',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 0,
            'seo_title' => null,
            'seo_description' => null,
            'notes' => null,
        ]);
    }

    public function test_published_scope_hides_future_and_draft_posts(): void
    {
        $published = BlogPost::factory()
            ->published()
            ->create();

        BlogPost::factory()->create();

        BlogPost::factory()->create([
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $results = BlogPost::query()
            ->published()
            ->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($published));
    }

    public function test_action_assigns_context_brand_when_creating_blog_post(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $post = app(UpsertBlogPostAction::class)->execute([
            'title' => 'Brand Article',
            'slug' => 'brand-article',
            'excerpt' => null,
            'content' => null,
            'image_source' => BlogPost::IMAGE_SOURCE_UPLOAD,
            'image_path' => 'blog/brand.jpg',
            'image_url' => null,
            'focal_point' => 'center',
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
            'sort_order' => 0,
            'seo_title' => null,
            'seo_description' => null,
            'notes' => null,
        ]);

        $this->assertSame($brand->id, $post->brand_id);
    }

    public function test_updating_blog_post_does_not_move_it_to_context_brand(): void
    {
        $originalBrand = Brand::factory()->create();
        $contextBrand = Brand::factory()->create();

        $post = BlogPost::factory()->create([
            'brand_id' => $originalBrand->id,
        ]);

        app(BrandContext::class)->set($contextBrand);

        $updated = app(UpsertBlogPostAction::class)->execute([
            'title' => 'Updated Article',
            'slug' => $post->slug,
            'excerpt' => null,
            'content' => null,
            'image_source' => BlogPost::IMAGE_SOURCE_UPLOAD,
            'image_path' => 'blog/updated-brand.jpg',
            'image_url' => null,
            'focal_point' => 'center',
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
            'sort_order' => 0,
            'seo_title' => null,
            'seo_description' => null,
            'notes' => null,
        ], $post);

        $this->assertSame($originalBrand->id, $updated->brand_id);
    }
}
