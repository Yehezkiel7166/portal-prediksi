<?php

namespace Tests\Feature\Blog;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_blog_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/blog-posts')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_blog_resource(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/blog-posts')
            ->assertForbidden();
    }
}
