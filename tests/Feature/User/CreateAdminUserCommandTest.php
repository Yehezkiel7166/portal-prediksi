<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_an_admin_user(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Super Admin',
            '--email' => 'admin@example.com',
            '--password' => 'SecureAdmin#2026',
        ])->assertSuccessful();

        $user = User::query()
            ->where('email', 'admin@example.com')
            ->first();

        $this->assertNotNull($user);
        $this->assertSame('Super Admin', $user->name);
        $this->assertTrue($user->is_admin);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(
            Hash::check('SecureAdmin#2026', $user->password)
        );
    }

    public function test_command_promotes_an_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'is_admin' => false,
        ]);

        $this->artisan('admin:create', [
            '--name' => 'Updated Admin',
            '--email' => 'member@example.com',
            '--password' => 'UpdatedAdmin#2026',
        ])->assertSuccessful();

        $user->refresh();

        $this->assertSame('Updated Admin', $user->name);
        $this->assertTrue($user->is_admin);
        $this->assertTrue(
            Hash::check('UpdatedAdmin#2026', $user->password)
        );

        $this->assertSame(
            1,
            User::query()
                ->where('email', 'member@example.com')
                ->count()
        );
    }

    public function test_command_rejects_a_weak_password(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Invalid Admin',
            '--email' => 'invalid@example.com',
            '--password' => 'password',
        ])->assertFailed();

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid@example.com',
        ]);
    }
}
