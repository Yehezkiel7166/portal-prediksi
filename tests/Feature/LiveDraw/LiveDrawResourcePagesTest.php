<?php

namespace Tests\Feature\LiveDraw;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveDrawResourcePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_create_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/live-draws/create')
            ->assertOk();
    }

    public function test_admin_can_open_edit_page(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $liveDraw = LiveDraw::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/live-draws/{$liveDraw->id}/edit")
            ->assertOk();
    }
}
