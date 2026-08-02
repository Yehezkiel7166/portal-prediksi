<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domains\Shio\Models\ShioPeriod;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

final class ShioTableController extends Controller
{
    public function __invoke(): View
    {
        $period = ShioPeriod::query()
            ->where('status', 'published')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->with([
                'shios' => fn ($query) => $query
                    ->orderBy('sort_order')
                    ->orderBy('name'),
            ])
            ->orderByDesc('year')
            ->first();

        $bannerUrl = null;

        if (
            $period !== null
            && filled($period->generated_banner)
            && Storage::disk('public')->exists(
                $period->generated_banner
            )
        ) {
            $bannerUrl = Storage::disk('public')->url(
                $period->generated_banner
            );
        }

        return view('frontend.tools.shio-table', [
            'period' => $period,
            'bannerUrl' => $bannerUrl,
        ]);
    }
}
