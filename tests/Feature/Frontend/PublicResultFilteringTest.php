<?php

namespace Tests\Feature\Frontend;

use App\Domains\Market\Models\Market;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicResultFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_options_use_active_market_order(): void
    {
        Market::factory()->create([
            'name' => 'Second Market',
            'slug' => 'second-market',
            'is_active' => true,
            'sort_order' => 20,
        ]);

        Market::factory()->create([
            'name' => 'First Market',
            'slug' => 'first-market',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        Market::factory()->create([
            'name' => 'Hidden Market',
            'slug' => 'hidden-market',
            'is_active' => false,
            'sort_order' => 1,
        ]);

        $this->get(route('results.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'First Market',
                'Second Market',
            ])
            ->assertDontSee('Hidden Market');
    }

    public function test_listing_can_be_filtered_by_market_slug(): void
    {
        Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Market::factory()->create([
            'name' => 'Sydney',
            'slug' => 'sydney',
            'is_active' => true,
        ]);

        $this->get(route('results.index', [
            'market' => 'singapore',
        ]))
            ->assertOk()
            ->assertViewHas(
                'markets',
                fn ($markets): bool => $markets->total() === 1
                    && $markets->count() === 1
                    && $markets->first()->slug === 'singapore',
            )
            ->assertSee('Singapore')
            ->assertSee('Filter aktif');
    }

    public function test_invalid_market_redirects_with_error(): void
    {
        $this->from(route('results.index'))
            ->get(route('results.index', [
                'market' => 'unknown-market',
            ]))
            ->assertRedirect(
                route('results.index')
            )
            ->assertSessionHasErrors([
                'market',
            ]);
    }
}
