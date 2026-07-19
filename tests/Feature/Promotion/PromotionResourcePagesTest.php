<?php

namespace Tests\Feature\Promotion;

use App\Domains\Promotion\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionResourcePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_create_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/promotions/create')
            ->assertOk();
    }

    public function test_admin_can_open_edit_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $promotion = Promotion::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/promotions/{$promotion->id}/edit")
            ->assertOk();
    }
}
