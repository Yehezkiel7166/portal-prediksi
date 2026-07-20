<?php

namespace Tests\Feature\Frontend;

use App\Domains\Blog\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBlogFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_only_shows_published_blog_posts(): void
    {
        $published = BlogPost::factory()
            ->published()
            ->create([
                'title' => 'Artikel Publik',
                'slug' => 'artikel-publik',
            ]);

        BlogPost::factory()->create([
            'title' => 'Artikel Draft',
            'slug' => 'artikel-draft',
        ]);

        BlogPost::factory()->create([
            'title' => 'Artikel Masa Depan',
            'slug' => 'artikel-masa-depan',
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee('Artikel Draft')
            ->assertDontSee('Artikel Masa Depan');
    }

    public function test_listing_is_paginated(): void
    {
        BlogPost::factory()
            ->count(13)
            ->published()
            ->create();

        $this->get('/blog')
            ->assertOk()
            ->assertViewHas(
                'blogPosts',
                fn ($blogPosts): bool =>
                    $blogPosts->count() === 12
                    && $blogPosts->total() === 13
            );
    }

    public function test_published_blog_detail_can_be_opened(): void
    {
        $blogPost = BlogPost::factory()
            ->published()
            ->create([
                'title' => 'Panduan Lengkap',
                'slug' => 'panduan-lengkap',
                'excerpt' => 'Ringkasan panduan.',
                'content' => '<p>Isi panduan lengkap.</p>',
                'seo_title' => 'SEO Panduan Lengkap',
                'seo_description' => 'Deskripsi SEO panduan.',
            ]);

        $this->get('/blog/panduan-lengkap')
            ->assertOk()
            ->assertSee($blogPost->title)
            ->assertSee('Isi panduan lengkap.', false)
            ->assertSee('SEO Panduan Lengkap')
            ->assertSee('Deskripsi SEO panduan.');
    }

    public function test_draft_blog_detail_returns_not_found(): void
    {
        BlogPost::factory()->create([
            'slug' => 'artikel-draft',
        ]);

        $this->get('/blog/artikel-draft')
            ->assertNotFound();
    }

    public function test_future_blog_detail_returns_not_found(): void
    {
        BlogPost::factory()->create([
            'slug' => 'artikel-masa-depan',
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $this->get('/blog/artikel-masa-depan')
            ->assertNotFound();
    }

    public function test_unknown_blog_post_returns_not_found(): void
    {
        $this->get('/blog/artikel-tidak-ada')
            ->assertNotFound();
    }
}
