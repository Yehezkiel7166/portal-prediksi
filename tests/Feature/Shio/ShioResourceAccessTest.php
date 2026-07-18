<?php

namespace Tests\Feature\Shio;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShioResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_shio_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/shio-periods')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_shio_resource(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/shio-periods')
            ->assertForbidden();
    }
}
