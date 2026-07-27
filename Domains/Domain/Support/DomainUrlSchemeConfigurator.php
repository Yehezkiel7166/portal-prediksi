<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

final class DomainUrlSchemeConfigurator
{
    public function __construct(
        private readonly UrlGenerator $urlGenerator,
        private readonly RequestSchemeResolver $schemeResolver,
    ) {}

    public function configure(
        Request $request,
        bool $forceHttps,
    ): void {
        if (
            $forceHttps
            || $this->schemeResolver->isSecure($request)
        ) {
            $this->urlGenerator->forceScheme('https');

            return;
        }

        $this->urlGenerator->forceScheme('http');
    }
}
