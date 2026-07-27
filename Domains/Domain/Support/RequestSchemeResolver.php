<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use Illuminate\Http\Request;

final class RequestSchemeResolver
{
    public function resolve(Request $request): string
    {
        $forwardedProto = $this->firstHeaderValue(
            $request->headers->get('X-Forwarded-Proto'),
        );

        if (in_array($forwardedProto, ['http', 'https'], true)) {
            return $forwardedProto;
        }

        $forwarded = $request->headers->get('Forwarded');

        if (is_string($forwarded)) {
            if (preg_match('/(?:^|[;,]\s*)proto=(?:"?)(https?)(?:"?)/i', $forwarded, $matches) === 1) {
                return strtolower($matches[1]);
            }
        }

        if ($request->server->get('HTTPS') !== null) {
            $https = strtolower(
                (string) $request->server->get('HTTPS'),
            );

            if (! in_array($https, ['', 'off', '0'], true)) {
                return 'https';
            }
        }

        return $request->isSecure() ? 'https' : 'http';
    }

    public function isSecure(Request $request): bool
    {
        return $this->resolve($request) === 'https';
    }

    private function firstHeaderValue(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $first = strtolower(
            trim(explode(',', $value)[0]),
        );

        return $first !== '' ? $first : null;
    }
}
