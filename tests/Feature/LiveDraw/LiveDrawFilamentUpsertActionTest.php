<?php

namespace Tests\Feature\LiveDraw;

use App\Domains\LiveDraw\Actions\UpsertLiveDrawAction;
use Tests\TestCase;

class LiveDrawFilamentUpsertActionTest extends TestCase
{
    public function test_create_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path(
                'Filament/Resources/LiveDraws/Pages/CreateLiveDraw.php'
            )
        );

        $this->assertStringContainsString(
            UpsertLiveDrawAction::class,
            $content
        );
    }

    public function test_edit_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path(
                'Filament/Resources/LiveDraws/Pages/EditLiveDraw.php'
            )
        );

        $this->assertStringContainsString(
            UpsertLiveDrawAction::class,
            $content
        );
    }
}
