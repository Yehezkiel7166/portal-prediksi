<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Enums\DomainType;

final class ResolveDomainRobotsDirective
{
    public function execute(
        DomainType $type,
        bool $isPrimary,
        bool $isActive = true,
    ): string {
        if (! $isActive) {
            return 'noindex, nofollow';
        }

        if ($type !== DomainType::Frontend) {
            return 'noindex, nofollow';
        }

        if (! $isPrimary) {
            return 'noindex, follow';
        }

        return 'index, follow';
    }

    public function isIndexable(
        DomainType $type,
        bool $isPrimary,
        bool $isActive = true,
    ): bool {
        return $this->execute(
            $type,
            $isPrimary,
            $isActive,
        ) === 'index, follow';
    }
}
