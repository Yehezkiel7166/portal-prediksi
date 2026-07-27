<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use Illuminate\Http\Request;

final class HttpsUrlBuilder
{
    public function __construct(
        private readonly DomainHostNormalizer $hostNormalizer,
    ) {}

    public function build(Request $request): string
    {
        $host = $this->hostNormalizer->normalize(
            $request->getHost(),
        ) ?? 'localhost';

        $path = $request->getRequestUri();

        if ($path === '') {
            $path = '/';
        }

        return 'https://'.$host.$path;
    }
}
