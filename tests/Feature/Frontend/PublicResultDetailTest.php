<?php

namespace Tests\Feature\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Frontend\ResultDetailController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicResultDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_result_detail_route_uses_frontend_controller(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
        ]);

        $response = $this->get(route('results.show', [
            'marketSlug' => 'singapore',
            'resultDate' => '2026-07-19',
        ]));

        $response
            ->assertOk()
            ->assertViewIs('frontend.results.show');

        $this->assertSame(
            ResultDetailController::class,
            app('router')
                ->getRoutes()
                ->getByName('results.show')
                ->getActionName(),
        );
    }

    public function test_detail_displays_result_and_metadata(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
        ]);

        $result = Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => '1234',
            'notes' => 'Catatan result detail.',
        ]);

        $url = route('results.show', [
            'marketSlug' => 'singapore',
            'resultDate' => '2026-07-19',
        ]);

        $this->get($url)
            ->assertOk()
            ->assertViewHas(
                'result',
                fn (Result $viewResult): bool => $viewResult->is($result),
            )
            ->assertSee('Result Singapore')
            ->assertSee('SGP')
            ->assertSee('Asia/Singapore')
            ->assertSee('1234')
            ->assertSee('Catatan result detail.')
            ->assertSee('19 Juli 2026')
            ->assertSee('rel="canonical"', false)
            ->assertSee($url)
            ->assertSee(route('results.index', [
                'market' => 'singapore',
            ]));
    }

    public function test_inactive_market_result_returns_not_found(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => false,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
        ]);

        $this->get(route('results.show', [
            'marketSlug' => 'singapore',
            'resultDate' => '2026-07-19',
        ]))->assertNotFound();
    }

    public function test_detail_requires_matching_market_and_date(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
        ]);

        $this->get(route('results.show', [
            'marketSlug' => 'sydney',
            'resultDate' => '2026-07-19',
        ]))->assertNotFound();

        $this->get(route('results.show', [
            'marketSlug' => 'singapore',
            'resultDate' => '2026-07-20',
        ]))->assertNotFound();
    }
}
