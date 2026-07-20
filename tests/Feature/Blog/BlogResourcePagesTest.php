<?php

namespace Tests\Feature\Blog;

use App\Domains\Blog\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogResourcePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_create_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/blog-posts/create')
            ->assertOk();
    }

    public function test_admin_can_open_edit_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $blogPost = BlogPost::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/blog-posts/{$blogPost->id}/edit")
            ->assertOk();
    }
}
