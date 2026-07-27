<?php

namespace Tests\Feature\LotteryTools;

use Tests\TestCase;

final class DreamBookTest extends TestCase
{
    public function test_index_is_searchable_and_contains_canonical_metadata(): void
    {
        $this->get(route('tools.dream-book.index', ['q' => 'bulan']))
            ->assertOk()
            ->assertSee('Buku Mimpi dan Tafsir Angka')
            ->assertSee('Bulan')
            ->assertDontSee('Matahari');
    }

    public function test_detail_uses_slug_and_displays_related_content(): void
    {
        $this->get(route('tools.dream-book.show', 'bulan'))
            ->assertOk()
            ->assertSee('Bulan')
            ->assertSee('Referensi terkait')
            ->assertSee(route('tools.dream-book.show', 'matahari'), false);
    }

    public function test_unknown_slug_returns_not_found(): void
    {
        $this->get('/alat-togel/buku-mimpi/tidak-ada')->assertNotFound();
    }

    public function test_sitemap_contains_dream_book_routes(): void
    {
        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('content-type', 'application/xml; charset=UTF-8')
            ->assertSee(route('tools.dream-book.index'), false)
            ->assertSee(route('tools.dream-book.show', 'bulan'), false);
    }
}
