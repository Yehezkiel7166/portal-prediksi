<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\HttpsPolicyData;

final class HstsHeaderBuilder
{
    public function build(HttpsPolicyData $policy): ?string
    {
        if (! $policy->sendHsts) {
            return null;
        }

        $directives = [
            'max-age='.$policy->hstsMaxAge,
        ];

        if ($policy->includeSubDomains) {
            $directives[] = 'includeSubDomains';
        }

        if ($policy->preload) {
            $directives[] = 'preload';
        }

        return implode('; ', $directives);
    }
}
