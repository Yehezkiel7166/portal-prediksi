<?php

namespace Tests\Feature\LotteryTools;

use App\Domains\Shio\Models\ShioNumber;
use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShioTableFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_table_displays_current_published_period_and_ordered_numbers(): void
    {
        $period = ShioPeriod::factory()->create([
            'year' => 2026,
            'title' => 'Tabel Shio Aktif',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'status' => 'published',
        ]);

        ShioNumber::factory()->create([
            'shio_period_id' => $period->getKey(),
            'name' => 'NAGA',
            'numbers' => ['01', '13', '25'],
            'sort_order' => 1,
        ]);

        $this->get('/alat-togel/tabel-shio')
            ->assertOk()
            ->assertSee('Tabel Shio Aktif')
            ->assertSee('NAGA')
            ->assertSee('01')
            ->assertSee('rel="canonical"', false);
    }

    public function test_draft_or_expired_period_is_not_public(): void
    {
        ShioPeriod::factory()->create([
            'title' => 'Tabel Draft',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'status' => 'draft',
        ]);

        $this->get('/alat-togel/tabel-shio')
            ->assertOk()
            ->assertDontSee('Tabel Draft')
            ->assertSee('Tabel shio belum tersedia');
    }

    public function test_header_contains_both_completed_tool_routes(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('tools.lottery-schedule'), false)
            ->assertSee(route('tools.shio-table'), false);
    }
}
