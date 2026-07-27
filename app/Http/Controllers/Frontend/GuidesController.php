<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Guide\Models\Guide;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class GuidesController extends Controller
{
    public function __invoke(): View
    {
        return view('frontend.guides.index', [
            'guides' => Guide::query()->published()->ordered()->paginate(12),
        ]);
    }
}
