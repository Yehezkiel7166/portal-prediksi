<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\Brand\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomepageBannerResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_resource(): void
    {
        Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/homepage-banners')
            ->assertOk();
    }

    public function test_regular_user_is_forbidden(): void
    {
        Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/homepage-banners')
            ->assertForbidden();
    }
}
