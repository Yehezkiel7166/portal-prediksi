<?php

namespace Tests\Feature\LotteryTools;

use App\Domains\Brand\Models\Brand;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PaitoTest extends TestCase
{
    use RefreshDatabase;

    public function test_paito_is_derived_from_result_data(): void
    {
        $brand = Brand::factory()->create(['is_primary' => true]);
        $market = Market::factory()->create(['brand_id' => $brand->id, 'name' => 'Singapore', 'slug' => 'singapore']);
        Result::factory()->create([
            'market_id' => $market->id,
            'brand_id' => $brand->id,
            'result_date' => '2026-07-26',
            'winning_numbers' => '1234',
        ]);

        $this->get(route('tools.paito', ['market' => 'singapore']))
            ->assertOk()
            ->assertSee('Singapore')
            ->assertSee('1234')
            ->assertSee('Paito Togel Warna');
    }

    public function test_paito_filters_date_range(): void
    {
        $brand = Brand::factory()->create(['is_primary' => true]);
        $market = Market::factory()->create(['brand_id' => $brand->id, 'slug' => 'hongkong']);
        Result::factory()->create(['market_id' => $market->id, 'brand_id' => $brand->id, 'result_date' => '2026-07-20', 'winning_numbers' => '1111']);
        Result::factory()->create(['market_id' => $market->id, 'brand_id' => $brand->id, 'result_date' => '2026-07-25', 'winning_numbers' => '9999']);

        $this->get(route('tools.paito', ['from' => '2026-07-24', 'to' => '2026-07-26']))
            ->assertOk()
            ->assertSee('9999')
            ->assertDontSee('1111');
    }

    public function test_invalid_range_is_rejected(): void
    {
        $this->get(route('tools.paito', ['from' => '2026-07-26', 'to' => '2026-07-20']))
            ->assertSessionHasErrors('to');
    }
}
