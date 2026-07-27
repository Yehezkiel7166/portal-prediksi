<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class ResultDetailController extends Controller
{
    public function __invoke(
        string $marketSlug,
        string $resultDate,
    ): View {
        $result = Result::query()
            
            ->select([
                'id',
                'market_id',
                'result_date',
                'winning_numbers',
                'notes',
            ])
            ->whereDate('result_date', $resultDate)
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query
                    ->active()
                    ->where('slug', $marketSlug),
            )
            ->with([
                'market:id,name,slug,code,timezone,is_active',
            ])
            ->firstOrFail();

        return view('frontend.results.show', [
            'result' => $result,
        ]);
    }
}
