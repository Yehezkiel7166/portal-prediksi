<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Rtp\Services\PublicRtpListingService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class SlotGacorController extends Controller
{
    public function __invoke(PublicRtpListingService $service): View
    {
        return view('frontend.slot-gacor.index', ['slots' => $service->paginate()]);
    }
}
