<?php

namespace Tests\Feature\Result;

use App\Domains\Result\Actions\UpsertResultAction;
use Tests\TestCase;

class ResultFilamentUpsertActionTest extends TestCase
{
    public function test_create_page_uses_upsert_action(): void
    {
        $contents = file_get_contents(
            app_path('Filament/Resources/Results/Pages/CreateResult.php')
        );

        $this->assertStringContainsString(
            UpsertResultAction::class,
            $contents,
        );

        $this->assertStringContainsString(
            'handleRecordCreation',
            $contents,
        );
    }

    public function test_edit_page_uses_upsert_action(): void
    {
        $contents = file_get_contents(
            app_path('Filament/Resources/Results/Pages/EditResult.php')
        );

        $this->assertStringContainsString(
            UpsertResultAction::class,
            $contents,
        );

        $this->assertStringContainsString(
            'handleRecordUpdate',
            $contents,
        );
    }
}
