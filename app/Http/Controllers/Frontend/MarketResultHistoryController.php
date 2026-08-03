<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Market\Support\MarketScheduleStatusResolver;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class MarketResultHistoryController extends Controller
{
    public function __invoke(
        string $marketSlug,
        MarketScheduleStatusResolver $statusResolver,
    ): View {
        $market = Market::query()
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
            ->where('slug', $marketSlug)
            ->firstOrFail();

        $results = Result::query()
            ->select([
                'id',
                'brand_id',
                'market_id',
                'result_date',
                'winning_numbers',
                'notes',
                'updated_at',
            ])
            ->where(
                'market_id',
                $market->getKey(),
            )
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('frontend.results.history', [
            'market' => $market,
            'results' => $results,
            'status' => $statusResolver->resolve(
                $market
            ),
        ]);
    }
}
