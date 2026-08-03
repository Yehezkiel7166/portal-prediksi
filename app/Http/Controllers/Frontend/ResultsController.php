<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Market\Support\MarketScheduleStatusResolver;
use App\Domains\Result\Support\LatestResultResolver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ResultIndexRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class ResultsController extends Controller
{
    public function __invoke(
        ResultIndexRequest $request,
        LatestResultResolver $latestResultResolver,
        MarketScheduleStatusResolver $statusResolver,
    ): View {
        $filters = $request->filters();

        $markets = Market::query()
            ->select([
                'id',
                'brand_id',
                'name',
                'slug',
                'code',
                'timezone',
                'active_days',
                'open_time',
                'close_time',
                'is_holiday',
                'holiday_note',
                'is_active',
                'sort_order',
            ])
            ->active()
            ->when(
                $filters['market'],
                static fn (
                    Builder $query,
                    string $market
                ): Builder => $query->where(
                    'slug',
                    $market,
                ),
            )
            ->withCount('results')
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $latestResultResolver->attachToMarkets(
            $markets->getCollection(),
        );

        $statuses = $markets
            ->getCollection()
            ->mapWithKeys(
                static fn (Market $market): array => [
                    $market->getKey() => $statusResolver->resolve($market),
                ],
            );

        $marketOptions = Market::query()
            ->select([
                'id',
                'name',
                'slug',
                'code',
                'sort_order',
            ])
            ->active()
            ->ordered()
            ->get();

        return view('frontend.results.index', [
            'filters' => $filters,
            'markets' => $markets,
            'marketOptions' => $marketOptions,
            'statuses' => $statuses,
        ]);
    }
}
