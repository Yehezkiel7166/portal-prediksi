<?php

namespace Tests\Feature\LotteryTools;

use App\Domains\Converter\Support\SgpNumberConverter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

final class SgpNumberConverterTest extends TestCase
{
    use RefreshDatabase;

    public function test_converter_preserves_leading_zero_and_maps_positions(): void
    {
        $result = app(SgpNumberConverter::class)->convert('0123');

        $this->assertSame('0123', $result['four_digit']);
        $this->assertSame('123', $result['three_digit']);
        $this->assertSame('23', $result['two_digit']);
        $this->assertSame('0', $result['as']);
        $this->assertSame('1', $result['kop']);
        $this->assertSame('2', $result['kepala']);
        $this->assertSame('3', $result['ekor']);
    }

    public function test_converter_rejects_non_four_digit_input(): void
    {
        $this->expectException(InvalidArgumentException::class);

        app(SgpNumberConverter::class)->convert('123');
    }

    public function test_public_converter_displays_documented_result(): void
    {
        $this->get(route('tools.sgp-converter.create'))
            ->assertOk()
            ->assertSee('Konversi Angka SGP')
            ->assertSee('canonical');

        $this->post(route('tools.sgp-converter.store'), [
            'number' => '0123',
        ])
            ->assertOk()
            ->assertSee('Hasil konversi 0123')
            ->assertSee('3D (KOP–EKOR)')
            ->assertSee('2D (KEPALA–EKOR)');
    }

    public function test_header_links_to_both_sprint_18c_tools(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('tools.bbfs.create'), false)
            ->assertSee(route('tools.sgp-converter.create'), false);
    }
}
