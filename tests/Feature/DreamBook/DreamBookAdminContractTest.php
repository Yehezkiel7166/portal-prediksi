<?php

namespace Tests\Feature\DreamBook;

use Tests\TestCase;

class DreamBookAdminContractTest extends TestCase
{
    public function test_admin_resource_and_database_repository_exist(): void
    {
        $resource = file_get_contents(
            app_path('Filament/Resources/DreamBookEntries/DreamBookEntryResource.php')
        );

        $repository = file_get_contents(
            app_path('Domains/DreamBook/Support/DreamBookRepository.php')
        );

        $this->assertStringContainsString("'Tabel Mimpi'", $resource);
        $this->assertStringContainsString("'dream-book'", $resource);
        $this->assertStringContainsString("Schema::hasTable('dream_book_entries')", $repository);
        $this->assertStringContainsString("config('dream-book.entries', [])", $repository);
    }
}
