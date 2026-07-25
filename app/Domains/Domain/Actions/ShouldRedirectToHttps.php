<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Data\HttpsPolicyData;
use App\Domains\Domain\Support\RequestSchemeResolver;
use Illuminate\Http\Request;

final class ShouldRedirectToHttps
{
    public function __construct(
        private readonly RequestSchemeResolver $schemeResolver,
    ) {}

    public function execute(
        Request $request,
        HttpsPolicyData $policy,
    ): bool {
        if (! $policy->forceHttps) {
            return false;
        }

        return ! $this->schemeResolver->isSecure($request);
    }
}
