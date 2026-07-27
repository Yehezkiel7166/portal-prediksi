<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\CanonicalUrlData;

final class CanonicalMetaRenderer
{
    public function canonicalTag(CanonicalUrlData $data): string
    {
        return sprintf(
            '<link rel="canonical" href="%s">',
            e($data->url),
        );
    }

    public function robotsTag(CanonicalUrlData $data): string
    {
        return sprintf(
            '<meta name="robots" content="%s">',
            e($data->robots),
        );
    }

    public function render(CanonicalUrlData $data): string
    {
        return implode(PHP_EOL, [
            $this->canonicalTag($data),
            $this->robotsTag($data),
        ]);
    }
}
