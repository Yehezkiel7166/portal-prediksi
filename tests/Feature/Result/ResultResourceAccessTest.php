<?php

namespace Tests\Feature\Result;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
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

    public function test_admin_can_open_result_market_resource_with_existing_results(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $market = Market::factory()->create([
            'name' => 'Singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'winning_numbers' => '1234',
        ]);

        $this->actingAs($admin)
            ->get('/admin/results')
            ->assertOk()
            ->assertSee('Singapore')
            ->assertSee('1234');
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
