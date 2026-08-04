<?php

namespace Tests\Feature\DreamBook;

use Tests\TestCase;

class DreamBookAdminContractTest extends TestCase
{
    public function test_admin_uses_classic_dream_book_fields(): void
    {
        $resource = file_get_contents(
            app_path(
                'Filament/Resources/DreamBookEntries/DreamBookEntryResource.php'
            )
        );

        $this->assertIsString($resource);

        $this->assertStringContainsString(
            "->label('Nomor')",
            $resource,
        );

        $this->assertStringContainsString(
            "->label('Keterangan')",
            $resource,
        );

        $this->assertStringContainsString(
            "->label('Kategori Angka')",
            $resource,
        );

        $this->assertStringContainsString(
            "->label('Angka')",
            $resource,
        );

        $this->assertStringNotContainsString(
            "TagsInput::make('keywords')",
            $resource,
        );

        $this->assertStringNotContainsString(
            "Textarea::make('interpretation')",
            $resource,
        );
    }
}
