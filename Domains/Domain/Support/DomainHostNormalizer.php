<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

final class DomainHostNormalizer
{
    public function normalize(?string $host): ?string
    {
        if ($host === null) {
            return null;
        }

        $host = trim($host);

        if ($host === '') {
            return null;
        }

        $candidate = str_contains($host, '://')
            ? $host
            : 'https://'.$host;

        $parsedHost = parse_url($candidate, PHP_URL_HOST);

        if (! is_string($parsedHost) || $parsedHost === '') {
            return null;
        }

        return strtolower(rtrim($parsedHost, '.'));
    }
}
