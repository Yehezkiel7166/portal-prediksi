<?php

namespace Tests\Feature\User;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_user_can_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin')
        );

        $this->actingAs($user)
            ->get('/admin')
            ->assertSuccessful();
    }
}
