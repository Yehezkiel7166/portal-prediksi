<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

final class CanonicalPathNormalizer
{
    public function normalize(?string $path): string
    {
        if ($path === null) {
            return '/';
        }

        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        $parsedPath = parse_url($path, PHP_URL_PATH);

        if (! is_string($parsedPath) || $parsedPath === '') {
            return '/';
        }

        $normalized = '/'.ltrim($parsedPath, '/');

        if ($normalized !== '/') {
            $normalized = rtrim($normalized, '/');
        }

        return $normalized;
    }
}
