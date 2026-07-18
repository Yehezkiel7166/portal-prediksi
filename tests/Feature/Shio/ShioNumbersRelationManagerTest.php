<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Models\ShioNumber;
use App\Domains\Shio\Models\ShioPeriod;
use App\Filament\Resources\ShioPeriods\Pages\EditShioPeriod;
use App\Filament\Resources\ShioPeriods\RelationManagers\ShioNumbersRelationManager;
use App\Filament\Resources\ShioPeriods\ShioPeriodResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShioNumbersRelationManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel('admin');
        Filament::bootCurrentPanel();
    }

    public function test_resource_registers_shio_numbers_relation_manager(): void
    {
        $this->assertContains(
            ShioNumbersRelationManager::class,
            ShioPeriodResource::getRelations(),
        );
    }

    public function test_admin_can_load_shio_numbers_relation_manager(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $period = ShioPeriod::factory()->create([
            'year' => 2026,
        ]);

        $numbers = ShioNumber::factory()
            ->count(2)
            ->sequence(
                [
                    'shio_period_id' => $period->id,
                    'name' => 'KUDA',
                    'numbers' => ['01', '13', '25'],
                    'sort_order' => 1,
                ],
                [
                    'shio_period_id' => $period->id,
                    'name' => 'ULAR',
                    'numbers' => ['02', '14', '26'],
                    'sort_order' => 2,
                ],
            )
            ->create();

        $this->actingAs($admin);

        Livewire::test(ShioNumbersRelationManager::class, [
            'ownerRecord' => $period,
            'pageClass' => EditShioPeriod::class,
        ])
            ->assertOk()
            ->assertCanSeeTableRecords($numbers);
    }

    public function test_relation_manager_only_lists_owner_period_shios(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $period = ShioPeriod::factory()->create([
            'year' => 2026,
        ]);

        $otherPeriod = ShioPeriod::factory()->create([
            'year' => 2027,
            'title' => 'Tabel Shio 2027',
            'start_date' => '2027-02-06',
            'end_date' => '2028-01-25',
        ]);

        $ownedShio = ShioNumber::factory()->create([
            'shio_period_id' => $period->id,
            'name' => 'NAGA',
            'numbers' => ['03', '15', '27'],
            'sort_order' => 1,
        ]);

        $otherShio = ShioNumber::factory()->create([
            'shio_period_id' => $otherPeriod->id,
            'name' => 'KELINCI',
            'numbers' => ['04', '16', '28'],
            'sort_order' => 1,
        ]);

        $this->actingAs($admin);

        Livewire::test(ShioNumbersRelationManager::class, [
            'ownerRecord' => $period,
            'pageClass' => EditShioPeriod::class,
        ])
            ->assertOk()
            ->assertCanSeeTableRecords([$ownedShio])
            ->assertCanNotSeeTableRecords([$otherShio]);
    }
}
