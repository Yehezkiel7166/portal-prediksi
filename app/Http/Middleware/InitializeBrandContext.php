<?php

namespace App\Http\Middleware;

use App\Domains\Brand\Support\BrandContextInitializer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeBrandContext
{
    public function __construct(
        private readonly BrandContextInitializer $initializer,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->initializer->initialize($request);

        return $next($request);
    }
}
