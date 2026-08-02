<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\BrandContextInitializer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class InitializeFilamentBrandContext
{
    public function __construct(
        private readonly BrandContext $context,
        private readonly BrandContextInitializer $initializer,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->context->has()) {
            $this->initializer->initialize($request);
        }

        return $next($request);
    }
}
