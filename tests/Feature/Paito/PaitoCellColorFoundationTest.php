<?php

namespace Tests\Feature\Paito;

use App\Domains\Paito\Models\PaitoCellColor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PaitoCellColorFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_paito_color_table_exists(): void
    {
        $this->assertTrue(
            Schema::hasColumns(
                'paito_cell_colors',
                [
                    'brand_id',
                    'market_id',
                    'result_id',
                    'position',
                    'color',
                ],
            ),
        );
    }

    public function test_supported_positions_are_complete(): void
    {
        $this->assertSame(
            [
                'as',
                'kop',
                'kepala',
                'ekor',
                'jumlah',
            ],
            PaitoCellColor::POSITIONS,
        );
    }

    public function test_supported_colors_are_complete(): void
    {
        $this->assertContains(
            'red',
            PaitoCellColor::COLORS,
        );

        $this->assertContains(
            'yellow',
            PaitoCellColor::COLORS,
        );

        $this->assertContains(
            'cyan',
            PaitoCellColor::COLORS,
        );
    }
}
