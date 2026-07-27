<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Market\Support\MarketScheduleStatus;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class LotteryScheduleController extends Controller
{
    public function __invoke(MarketScheduleStatus $statusResolver): View
    {
        $markets = Market::query()
            ->active()
            ->ordered()
            ->get()
            ->map(function (Market $market) use ($statusResolver): Market {
                $market->setAttribute('schedule_status', $statusResolver->resolve($market));

                return $market;
            });

        return view('frontend.tools.lottery-schedule', [
            'markets' => $markets,
        ]);
    }
}
