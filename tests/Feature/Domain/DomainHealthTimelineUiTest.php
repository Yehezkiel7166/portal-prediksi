<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Filament\Resources\BrandDomainResource;
use App\Filament\Resources\BrandDomainResource\Pages\ManageBrandDomainHealthHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DomainHealthTimelineUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_registers_health_history_page(): void
    {
        $pages = BrandDomainResource::getPages();

        $this->assertArrayHasKey(
            'health-history',
            $pages,
        );

        $this->assertSame(
            ManageBrandDomainHealthHistory::class,
            $pages['health-history']->getPage(),
        );
    }

    public function test_domain_table_contains_health_timeline_action(): void
    {
        $contents = file_get_contents(
            app_path(
                'Filament/Resources/BrandDomainResource/Tables/BrandDomainsTable.php',
            ),
        );

        $this->assertIsString($contents);

        $this->assertStringContainsString(
            "Action::make('healthTimeline')",
            $contents,
        );

        $this->assertStringContainsString(
            "->label('Health Timeline')",
            $contents,
        );

        $this->assertStringContainsString(
            "'health-history'",
            $contents,
        );

        $this->assertStringContainsString(
            'BrandDomainResource::getUrl',
            $contents,
        );
    }

    public function test_health_timeline_page_uses_health_history_relation(): void
    {
        $contents = file_get_contents(
            app_path(
                'Filament/Resources/BrandDomainResource/Pages/ManageBrandDomainHealthHistory.php',
            ),
        );

        $this->assertIsString($contents);

        $this->assertStringContainsString(
            'ManageRelatedRecords',
            $contents,
        );

        $this->assertStringContainsString(
            BrandDomainResource::class,
            $contents,
        );

        $this->assertStringContainsString(
            'healthHistories',
            $contents,
        );
    }

    public function test_health_timeline_page_contains_filtering_and_detail_controls(): void
    {
        $contents = file_get_contents(
            app_path(
                'Filament/Resources/BrandDomainResource/Pages/ManageBrandDomainHealthHistory.php',
            ),
        );

        $this->assertIsString($contents);

        $this->assertStringContainsString(
            'SelectFilter::make',
            $contents,
        );

        $this->assertStringContainsString(
            'Filter::make',
            $contents,
        );

        $this->assertStringContainsString(
            'health-timeline-detail',
            $contents,
        );
    }

    public function test_health_timeline_detail_view_exists(): void
    {
        $path = resource_path(
            'views/filament/resources/brand-domain/health-timeline-detail.blade.php',
        );

        $this->assertFileExists($path);

        $contents = file_get_contents($path);

        $this->assertIsString($contents);
        $this->assertNotSame('', trim($contents));
    }
}
