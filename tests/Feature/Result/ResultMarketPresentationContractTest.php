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
        $this->assertStringContainsString(
            'md:grid-cols-[minmax(180px,1.5fr)',
            $view,
        );

        $this->assertStringNotContainsString(
            'grid gap-6 md:grid-cols-2 xl:grid-cols-3',
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
