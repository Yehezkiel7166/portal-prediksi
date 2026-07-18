<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Actions\GenerateShioBannerAction;
use App\Domains\Shio\Events\ShioChanged;
use App\Domains\Shio\Models\ShioPeriod;
use App\Filament\Resources\ShioPeriods\Pages\EditShioPeriod;
use Filament\Actions\Action;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use ReflectionMethod;
use Tests\TestCase;

class ShioBannerPageActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([ShioChanged::class]);
    }

    public function test_edit_page_registers_generate_banner_action(): void
    {
        $method = new ReflectionMethod(
            EditShioPeriod::class,
            'getHeaderActions',
        );

        $this->assertTrue($method->isProtected());

        $source = file_get_contents(
            app_path(
                'Filament/Resources/ShioPeriods/Pages/EditShioPeriod.php'
            )
        );

        $this->assertIsString($source);

        $this->assertStringContainsString(
            "Action::make('generateBanner')",
            $source,
        );

        $this->assertStringContainsString(
            GenerateShioBannerAction::class,
            $source,
        );

        $this->assertStringContainsString(
            'refreshFormData',
            $source,
        );

        $this->assertTrue(class_exists(Action::class));
    }

    public function test_generate_action_is_only_available_on_edit_page(): void
    {
        $period = ShioPeriod::factory()->create([
            'banner_template' =>
                'shio/banner-templates/template.png',
        ]);

        $this->assertNotNull($period->getKey());

        $editSource = file_get_contents(
            app_path(
                'Filament/Resources/ShioPeriods/Pages/EditShioPeriod.php'
            )
        );

        $createSource = file_get_contents(
            app_path(
                'Filament/Resources/ShioPeriods/Pages/CreateShioPeriod.php'
            )
        );

        $this->assertIsString($editSource);
        $this->assertIsString($createSource);

        $this->assertStringContainsString(
            "Action::make('generateBanner')",
            $editSource,
        );

        $this->assertStringNotContainsString(
            "Action::make('generateBanner')",
            $createSource,
        );
    }
}
