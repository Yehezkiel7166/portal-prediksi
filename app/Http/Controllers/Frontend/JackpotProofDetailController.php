<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\JackpotProof\Services\PublicJackpotProofService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class JackpotProofDetailController extends Controller
{
    public function __invoke(string $slug, PublicJackpotProofService $service): View
    {
        return view('frontend.jackpot-proofs.show', ['proof' => $service->findPublishedBySlug($slug)]);
    }
}
