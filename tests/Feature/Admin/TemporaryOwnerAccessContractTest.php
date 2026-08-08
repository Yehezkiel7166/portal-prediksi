<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class TemporaryOwnerAccessContractTest extends TestCase
{
    public function test_admin_is_temporary_owner(): void
    {
        $user = User::factory()->make([
            'is_admin' => true,
        ]);

        $this->assertTrue(
            $user->isTemporaryOwner(),
        );
    }

    public function test_non_admin_is_not_temporary_owner(): void
    {
        $user = User::factory()->make([
            'is_admin' => false,
        ]);

        $this->assertFalse(
            $user->isTemporaryOwner(),
        );
    }

    public function test_temporary_owner_bypasses_authorization_gate(): void
    {
        $user = User::factory()->make([
            'is_admin' => true,
        ]);

        $this->assertTrue(
            Gate::forUser($user)->allows(
                'temporary-owner-contract-probe'
            ),
        );
    }

    public function test_non_admin_does_not_receive_owner_gate_bypass(): void
    {
        $user = User::factory()->make([
            'is_admin' => false,
        ]);

        $this->assertFalse(
            Gate::forUser($user)->allows(
                'temporary-owner-contract-probe'
            ),
        );
    }

    public function test_brand_scope_contract_remains_present(): void
    {
        $scope = file_get_contents(
            app_path(
                'Domains/Brand/Scopes/BrandScope.php'
            )
        );

        foreach ([
            'BrandContext::class',
            '$context->has()',
            "qualifyColumn('brand_id')",
            '$context->get()->getKey()',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $scope,
            );
        }
    }

    public function test_filament_brand_context_remains_enabled(): void
    {
        $provider = file_get_contents(
            app_path(
                'Providers/Filament/AdminPanelProvider.php'
            )
        );

        $this->assertStringContainsString(
            'InitializeFilamentBrandContext::class',
            $provider,
        );
    }

    public function test_history_result_navigation_is_enabled(): void
    {
        $resource = file_get_contents(
            app_path(
                'Filament/Resources/Results/ResultResource.php'
            )
        );

        $this->assertStringContainsString(
            <<<'PHP'
    protected static bool $shouldRegisterNavigation =
        true;
PHP,
            $resource,
        );
    }

    public function test_temporary_owner_does_not_depend_on_brand_id_or_roles(): void
    {
        $user = file_get_contents(
            app_path('Models/User.php')
        );

        $ownerMethodStart = strpos(
            $user,
            'public function isTemporaryOwner(): bool'
        );

        $panelMethodStart = strpos(
            $user,
            'public function canAccessPanel'
        );

        $this->assertNotFalse($ownerMethodStart);
        $this->assertNotFalse($panelMethodStart);

        $ownerContract = substr(
            $user,
            $ownerMethodStart,
            $panelMethodStart - $ownerMethodStart,
        );

        $this->assertStringContainsString(
            '$this->is_admin',
            $ownerContract,
        );

        $this->assertStringNotContainsString(
            'brand_id',
            $ownerContract,
        );

        $this->assertStringNotContainsString(
            'role',
            $ownerContract,
        );
    }
}
