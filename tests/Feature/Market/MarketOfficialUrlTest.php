<?php

declare(strict_types=1);

namespace Tests\Feature\Market;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Actions\UpsertMarketAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class MarketOfficialUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_saves_manual_official_url(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $market = app(UpsertMarketAction::class)->execute(
            null,
            [
                'code' => 'PCS',
                'name' => 'PCSO',
                'slug' => 'pcso',
                'official_url' =>
                    'https://www.pcso.gov.ph/',
                'timezone' => 'Asia/Jakarta',
                'active_days' => [1, 2, 3, 4, 5, 6],
                'close_time' => '19:45',
                'result_time' => '20:00',
                'open_time' => '20:10',
                'is_holiday' => false,
                'is_active' => true,
                'sort_order' => 1,
                'notes' => null,
            ],
        );

        $this->assertSame(
            'https://www.pcso.gov.ph/',
            $market->official_url,
        );

        $this->assertDatabaseHas('markets', [
            'id' => $market->id,
            'official_url' =>
                'https://www.pcso.gov.ph/',
        ]);
    }

    public function test_blank_url_becomes_null(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $market = app(UpsertMarketAction::class)->execute(
            null,
            [
                'code' => 'OPT',
                'name' => 'Optional URL',
                'slug' => 'optional-url',
                'official_url' => ' ',
                'timezone' => 'Asia/Jakarta',
                'active_days' => [1, 2, 3],
                'is_holiday' => false,
                'is_active' => true,
                'sort_order' => 1,
                'notes' => null,
            ],
        );

        $this->assertNull($market->official_url);
    }

    public function test_unsafe_url_is_rejected(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $this->expectException(
            ValidationException::class
        );

        app(UpsertMarketAction::class)->execute(
            null,
            [
                'code' => 'BAD',
                'name' => 'Invalid URL',
                'slug' => 'invalid-url',
                'official_url' => 'javascript:alert(1)',
                'timezone' => 'Asia/Jakarta',
                'active_days' => [1, 2, 3],
                'is_holiday' => false,
                'is_active' => true,
                'sort_order' => 1,
                'notes' => null,
            ],
        );
    }

    public function test_admin_form_has_official_url_field(): void
    {
        $source = file_get_contents(
            app_path(
                'Filament/Resources/Markets/Schemas/MarketForm.php'
            )
        );

        $this->assertIsString($source);

        $this->assertStringContainsString(
            "TextInput::make('official_url')",
            $source,
        );

        $this->assertStringContainsString(
            "->label('Link Pasaran Resmi')",
            $source,
        );
    }

    public function test_schedule_replaces_paito_link(): void
    {
        $source = file_get_contents(
            resource_path(
                'views/frontend/tools/lottery-schedule.blade.php'
            )
        );

        $this->assertIsString($source);

        $this->assertStringNotContainsString(
            "route('tools.paito')",
            $source,
        );

        $this->assertStringContainsString(
            '$market->official_url',
            $source,
        );

        $this->assertStringContainsString(
            'Link Official',
            $source,
        );

        $this->assertStringContainsString(
            'noopener noreferrer nofollow',
            $source,
        );
    }
}
