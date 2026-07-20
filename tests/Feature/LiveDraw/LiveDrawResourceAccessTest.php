<?php

namespace Tests\Feature\LiveDraw;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveDrawResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_live_draw_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/live-draws')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_live_draw_resource(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/live-draws')
            ->assertForbidden();
    }
}
