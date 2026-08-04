<?php

namespace Tests\Feature\DreamBook;

use Tests\TestCase;

class DreamBookAdminContractTest extends TestCase
{
    public function test_admin_uses_classic_fields(): void
    {
        $resource = file_get_contents(
            app_path(
                'Filament/Resources/DreamBookEntries/DreamBookEntryResource.php'
            )
        );

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
    }

    public function test_create_maps_brand_and_legacy_fields(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/DreamBookEntries/Pages/CreateDreamBookEntry.php'
            )
        );

        $this->assertStringContainsString(
            'BrandContext::class',
            $page,
        );

        $this->assertStringContainsString(
            "\$data['brand_id']",
            $page,
        );

        $this->assertStringContainsString(
            "\$data['title']",
            $page,
        );

        $this->assertStringContainsString(
            "\$data['slug']",
            $page,
        );

        $this->assertStringContainsString(
            "\$data['interpretation']",
            $page,
        );
    }
}
