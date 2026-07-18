<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Models\ShioPeriod;
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

    public function test_admin_can_open_shio_create_form(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/shio-periods/create')
            ->assertOk()
            ->assertSee('Template Banner')
            ->assertSee('Banner Hasil')
            ->assertDontSee('Generate Banner');
    }

    public function test_admin_can_open_shio_edit_form(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $period = ShioPeriod::factory()->create([
            'banner_template' =>
                'shio/banner-templates/template.png',
        ]);

        $this->actingAs($admin)
            ->get("/admin/shio-periods/{$period->getKey()}/edit")
            ->assertOk()
            ->assertSee('Template Banner')
            ->assertSee('Banner Hasil')
            ->assertSee('Generate Banner');
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
