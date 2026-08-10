<?php

declare(strict_types=1);

namespace Tests\Feature\Result;

use Tests\TestCase;

final class ResultMarketPresentationContractTest extends TestCase
{
    public function test_frontend_index_is_market_centric(): void
    {
        $controller = file_get_contents(
            app_path(
                'Http/Controllers/Frontend/ResultsController.php'
            )
        );

        $view = file_get_contents(
            resource_path(
                'views/frontend/results/index.blade.php'
            )
        );

        $this->assertIsString($controller);
        $this->assertIsString($view);

        $this->assertStringContainsString(
            'Market::query()',
            $controller,
        );

        $this->assertStringContainsString(
            'LatestResultResolver',
            $controller,
        );

        /*
        |--------------------------------------------------------------------------
        | BUSINESS CONTRACT
        |--------------------------------------------------------------------------
        |
        | Result listing tetap market-centric:
        | satu market = satu presentation card.
        |
        */

        $this->assertStringContainsString(
            '@foreach ($markets as $market)',
            $view,
        );

        $this->assertStringNotContainsString(
            '@forelse ($markets as $market)',
            $view,
        );

        $this->assertStringContainsString(
            "route('results.history'",
            $view,
        );

        $this->assertStringNotContainsString(
            '@forelse ($results as $result)',
            $view,
        );

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE CARD CONTRACT
        |--------------------------------------------------------------------------
        */

        $this->assertStringContainsString(
            'data-theme-result-grid',
            $view,
        );

        $this->assertStringContainsString(
            'data-theme-result-card',
            $view,
        );

        $this->assertStringContainsString(
            'sm:grid-cols-2',
            $view,
        );

        $this->assertStringContainsString(
            'xl:grid-cols-3',
            $view,
        );

        $this->assertStringNotContainsString(
            'md:grid-cols-[minmax(180px,1.5fr)',
            $view,
        );

        /*
        |--------------------------------------------------------------------------
        | MARKET IDENTITY CONTRACT
        |--------------------------------------------------------------------------
        */

        $this->assertStringContainsString(
            '{{ $market->name }}',
            $view,
        );

        $this->assertStringContainsString(
            '{{ $market->code }}',
            $view,
        );

        $this->assertStringContainsString(
            'Detail {{ $market->name }}',
            $view,
        );
    }

    public function test_admin_index_is_market_centric(): void
    {
        $resource = file_get_contents(
            app_path(
                'Filament/Resources/ResultMarkets/ResultMarketResource.php'
            )
        );

        $table = file_get_contents(
            app_path(
                'Filament/Resources/ResultMarkets/Tables/ResultMarketsTable.php'
            )
        );

        $this->assertIsString($resource);
        $this->assertIsString($table);

        $this->assertStringContainsString(
            'protected static ?string $model = Market::class;',
            $resource,
        );

        $this->assertStringContainsString(
            "->with('latestResult')",
            $resource,
        );

        $this->assertStringNotContainsString(
            'latestResult:id,brand_id,market_id',
            $resource,
        );

        $this->assertStringContainsString(
            "->withCount('results')",
            $resource,
        );

        $this->assertStringContainsString(
            "Action::make('manage')",
            $table,
        );
    }
}
