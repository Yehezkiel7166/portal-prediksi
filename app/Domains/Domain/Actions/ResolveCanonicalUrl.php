<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Data\CanonicalUrlData;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Support\CanonicalPathNormalizer;
use App\Domains\Domain\Support\DomainHostNormalizer;
use Illuminate\Http\Request;

final class ResolveCanonicalUrl
{
    public function __construct(
        private readonly ResolvePrimaryFrontendDomain $primaryFrontendResolver,
        private readonly ResolveDomainRobotsDirective $robotsResolver,
        private readonly DomainHostNormalizer $hostNormalizer,
        private readonly CanonicalPathNormalizer $pathNormalizer,
    ) {}

    public function execute(
        Brand $brand,
        Request $request,
        ?DomainType $currentDomainType = null,
        ?bool $currentDomainIsPrimary = null,
    ): CanonicalUrlData {
        $primaryDomain = $this->primaryFrontendResolver->execute($brand);

        $primaryHost = $this->hostNormalizer->normalize(
            $primaryDomain?->host,
        );

        $requestHost = $this->hostNormalizer->normalize(
            $request->getHost(),
        );

        $fallbackHost = $this->hostNormalizer->normalize(
            parse_url((string) config('app.url'), PHP_URL_HOST),
        );

        $host = $primaryHost
            ?? $requestHost
            ?? $fallbackHost
            ?? 'localhost';

        $scheme = $this->resolveScheme($request);
        $path = $this->pathNormalizer->normalize(
            $request->getRequestUri(),
        );

        $type = $currentDomainType ?? DomainType::Frontend;

        $isPrimary = $currentDomainIsPrimary
            ?? ($primaryHost !== null && $requestHost === $primaryHost);

        $robots = $this->robotsResolver->execute(
            type: $type,
            isPrimary: $isPrimary,
            isActive: true,
        );

        return new CanonicalUrlData(
            url: $this->buildUrl($scheme, $host, $path),
            scheme: $scheme,
            host: $host,
            path: $path,
            usesPrimaryDomain: $primaryHost !== null,
            indexable: $robots === 'index, follow',
            robots: $robots,
        );
    }

    private function resolveScheme(Request $request): string
    {
        $forwardedProto = $request->headers->get('X-Forwarded-Proto');

        if (is_string($forwardedProto)) {
            $forwardedProto = strtolower(
                trim(explode(',', $forwardedProto)[0]),
            );

            if (in_array($forwardedProto, ['http', 'https'], true)) {
                return $forwardedProto;
            }
        }

        return $request->isSecure() ? 'https' : 'http';
    }

    private function buildUrl(
        string $scheme,
        string $host,
        string $path,
    ): string {
        return sprintf(
            '%s://%s%s',
            $scheme,
            $host,
            $path,
        );
    }
}
