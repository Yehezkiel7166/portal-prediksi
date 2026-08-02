<?php

declare(strict_types=1);

namespace Tests\Feature\Result;

use Tests\TestCase;

class ResultBulkImportFilamentActionTest extends TestCase
{
    public function test_result_list_registers_bulk_import_action(): void
    {
        $source = file_get_contents(
            app_path(
                'Filament/Resources/Results/Pages/ListResults.php'
            )
        );

        $this->assertIsString($source);

        $this->assertStringContainsString(
            "Action::make('importResults')",
            $source,
        );

        $this->assertStringContainsString(
            'BulkImportResultsAction::class',
            $source,
        );

        $this->assertStringContainsString(
            "FileUpload::make('file')",
            $source,
        );
    }
}
