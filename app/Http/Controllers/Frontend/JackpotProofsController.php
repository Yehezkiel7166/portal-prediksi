<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\JackpotProof\Services\PublicJackpotProofService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class JackpotProofsController extends Controller
{
    public function __invoke(PublicJackpotProofService $service): View
    {
        return view('frontend.jackpot-proofs.index', ['proofs' => $service->paginate()]);
    }
}
