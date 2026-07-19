<?php

namespace Tests\Feature\Shio;

use Tests\TestCase;

class ShioListPageCreateActionTest extends TestCase
{
    public function test_list_page_registers_create_action(): void
    {
        $contents = file_get_contents(
            app_path('Filament/Resources/ShioPeriods/Pages/ListShioPeriods.php')
        );

        $this->assertStringContainsString(
            'CreateAction::make()',
            $contents
        );
    }
}
