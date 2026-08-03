<?php

namespace Tests\Feature\Result;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_result_market_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/results')
            ->assertOk();
    }

    public function test_admin_can_open_result_history_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/result-history')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_result_resources(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/results')
            ->assertForbidden();

        $this->actingAs($user)
            ->get('/admin/result-history')
            ->assertForbidden();
    }
}
