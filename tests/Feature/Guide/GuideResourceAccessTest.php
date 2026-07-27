<?php
namespace Tests\Feature\Guide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class GuideResourceAccessTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_open_guide_resource(): void { $admin=User::factory()->create(['is_admin'=>true,'email_verified_at'=>now()]); $this->actingAs($admin)->get('/admin/guides')->assertOk(); }
    public function test_regular_user_cannot_open_guide_resource(): void { $user=User::factory()->create(['is_admin'=>false,'email_verified_at'=>now()]); $this->actingAs($user)->get('/admin/guides')->assertForbidden(); }
}
