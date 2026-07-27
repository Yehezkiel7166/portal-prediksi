<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Guide\Models\Guide;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class GuideDetailController extends Controller
{
    public function __invoke(string $slug): View
    {
        return view('frontend.guides.show', [
            'guide' => Guide::query()->published()->where('slug', $slug)->firstOrFail(),
        ]);
    }
}
