<?php

namespace Tests\Feature\DreamBook;

use Tests\TestCase;

class DreamBookFrontendListContractTest extends TestCase
{
    public function test_frontend_uses_list_and_category_filter(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/dream-book-index.blade.php'
            )
        );

        $controller = file_get_contents(
            app_path(
                'Http/Controllers/Frontend/DreamBookController.php'
            )
        );

        $this->assertStringContainsString(
            'name="category"',
            $view,
        );

        $this->assertStringContainsString(
            "['2D', '3D', '4D']",
            $controller,
        );

        $this->assertStringContainsString(
            "\$entry['description']",
            $view,
        );

        $this->assertStringContainsString(
            "\$entry['numbers']",
            $view,
        );

        $this->assertStringNotContainsString(
            'sm:grid-cols-2 lg:grid-cols-3',
            $view,
        );
    }

    public function test_public_filter_is_available(): void
    {
        $this->get(route(
            'tools.dream-book.index',
            ['category' => '2D'],
        ))
            ->assertOk()
            ->assertSee('Kategori')
            ->assertSee('Keterangan')
            ->assertSee('Angka');
    }
}
