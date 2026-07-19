<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class ResultsController extends Controller
{
    public function __invoke(): View
    {
        $results = Result::query()
            ->select([
                'id',
                'market_id',
                'result_date',
                'winning_numbers',
                'notes',
            ])
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query->active(),
            )
            ->with([
                'market:id,name,slug,code,is_active,sort_order',
            ])
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->paginate(12);

        return view('frontend.results.index', [
            'results' => $results,
        ]);
    }
}
