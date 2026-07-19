<?php

namespace Tests\Feature\Shio;

use Tests\TestCase;

class ShioListGenerateActionTest extends TestCase
{
    public function test_list_page_registers_generate_banner_action(): void
    {
        $contents = file_get_contents(
            app_path('Filament/Resources/ShioPeriods/Pages/ListShioPeriods.php')
        );

        $this->assertStringContainsString(
            "Action::make('generateBanner')",
            $contents
        );
    }
}
