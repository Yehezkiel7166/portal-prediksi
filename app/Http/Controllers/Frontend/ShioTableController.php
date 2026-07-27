<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Shio\Models\ShioPeriod;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class ShioTableController extends Controller
{
    public function __invoke(): View
    {
        $period = ShioPeriod::query()
            ->where('status', 'published')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->with(['shios' => fn ($query) => $query->orderBy('sort_order')->orderBy('name')])
            ->orderByDesc('year')
            ->first();

        return view('frontend.tools.shio-table', [
            'period' => $period,
        ]);
    }
}
