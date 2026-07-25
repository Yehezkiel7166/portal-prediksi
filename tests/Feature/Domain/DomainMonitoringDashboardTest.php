<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource\Pages\ListBrandDomains;
use App\Filament\Widgets\DomainMonitoringStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class DomainMonitoringDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_list_registers_monitoring_widget(): void
    {
        $contents = file_get_contents(
            app_path(
                'Filament/Resources/BrandDomainResource/Pages/ListBrandDomains.php',
            ),
        );

        $this->assertStringContainsString(
            DomainMonitoringStats::class,
            $contents,
        );

        $this->assertStringContainsString(
            'getHeaderWidgets',
            $contents,
        );
    }

    public function test_monitoring_widget_is_discoverable(): void
    {
        $this->assertTrue(
            class_exists(DomainMonitoringStats::class),
        );

        $contents = file_get_contents(
            app_path(
                'Providers/Filament/AdminPanelProvider.php',
            ),
        );

        $this->assertStringContainsString(
            'discoverWidgets',
            $contents,
        );
    }

    public function test_domain_table_contains_monitoring_controls(): void
    {
        $contents = file_get_contents(
            app_path(
                'Filament/Resources/BrandDomainResource/Tables/BrandDomainsTable.php',
            ),
        );

        $this->assertStringContainsString(
            "TextColumn::make('verification_status')",
            $contents,
        );

        $this->assertStringContainsString(
            "TextColumn::make('verification_score')",
            $contents,
        );

        $this->assertStringContainsString(
            "TextColumn::make('verified_at')",
            $contents,
        );

        $this->assertStringContainsString(
            "Action::make('verifyNow')",
            $contents,
        );

        $this->assertStringContainsString(
            "BulkAction::make('verifySelected')",
            $contents,
        );

        $this->assertStringContainsString(
            "SelectFilter::make('verification_status')",
            $contents,
        );

        $this->assertStringContainsString(
            "Filter::make('never_verified')",
            $contents,
        );

        $this->assertStringContainsString(
            "->poll('30s')",
            $contents,
        );
    }

    public function test_domain_monitoring_page_opens_for_admin(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'verification_status' =>
                    DomainVerificationStatus::Healthy,

                'verification_score' => 100,
                'verified_at' => now(),
            ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/brand-domains')
            ->assertOk()
            ->assertSee('Domains');
    }

    public function test_domain_list_livewire_component_renders(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'monitoring.example.test',
                'verification_status' =>
                    DomainVerificationStatus::Warning,

                'verification_score' => 70,
                'verified_at' => now(),
            ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        Livewire::test(ListBrandDomains::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(
                BrandDomain::query()->get(),
            );
    }

    public function test_monitoring_widget_respects_brand_context(): void
    {
        $brandA = Brand::factory()->create();
        $brandB = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brandA)
            ->create([
                'verification_status' =>
                    DomainVerificationStatus::Healthy,

                'verification_score' => 100,
                'verified_at' => now(),
            ]);

        BrandDomain::factory()
            ->for($brandB)
            ->create([
                'verification_status' =>
                    DomainVerificationStatus::Critical,

                'verification_score' => 0,
                'verified_at' => now(),
            ]);

        app(BrandContext::class)->set($brandA);

        $this->assertSame(
            1,
            BrandDomain::query()
                ->where('brand_id', $brandA->getKey())
                ->count(),
        );

        $this->assertSame(
            0,
            BrandDomain::query()
                ->where('brand_id', $brandA->getKey())
                ->where(
                    'verification_status',
                    DomainVerificationStatus::Critical->value,
                )
                ->count(),
        );
    }

    public function test_verification_detail_view_exists(): void
    {
        $this->assertFileExists(
            resource_path(
                'views/filament/resources/brand-domain/verification-checks.blade.php',
            ),
        );
    }
}
