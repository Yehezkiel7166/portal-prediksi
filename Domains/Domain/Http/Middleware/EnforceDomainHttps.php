<?php

declare(strict_types=1);

namespace App\Domains\Domain\Http\Middleware;

use App\Domains\Domain\Actions\ResolveHttpsPolicy;
use App\Domains\Domain\Actions\ShouldRedirectToHttps;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DomainHostNormalizer;
use App\Domains\Domain\Support\HstsHeaderBuilder;
use App\Domains\Domain\Support\HttpsUrlBuilder;
use App\Domains\Domain\Support\RequestSchemeResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnforceDomainHttps
{
    public function __construct(
        private readonly ResolveHttpsPolicy $policyResolver,
        private readonly ShouldRedirectToHttps $redirectDecision,
        private readonly HttpsUrlBuilder $httpsUrlBuilder,
        private readonly HstsHeaderBuilder $hstsHeaderBuilder,
        private readonly RequestSchemeResolver $schemeResolver,
        private readonly DomainHostNormalizer $hostNormalizer,
    ) {}

    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        $domain = $this->resolveDomain($request);
        $type = $domain?->type ?? DomainType::Frontend;

        $policy = $this->policyResolver->execute(
            domain: $domain,
            type: $type,
        );

        if ($this->redirectDecision->execute($request, $policy)) {
            return redirect()->to(
                $this->httpsUrlBuilder->build($request),
                $policy->redirectStatus,
            );
        }

        $response = $next($request);

        if ($this->schemeResolver->isSecure($request)) {
            $hsts = $this->hstsHeaderBuilder->build($policy);

            if ($hsts !== null) {
                $response->headers->set(
                    'Strict-Transport-Security',
                    $hsts,
                );
            }
        }

        return $response;
    }

    private function resolveDomain(
        Request $request,
    ): ?BrandDomain {
        $host = $this->hostNormalizer->normalize(
            $request->getHost(),
        );

        if ($host === null) {
            return null;
        }

        return BrandDomain::query()
            ->whereRaw('LOWER(host) = ?', [$host])
            ->where('is_active', true)
            ->first();
    }
}
