<?php

declare(strict_types=1);

namespace App\Domains\Domain\Policies;

use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Support\DomainTypeCapabilities;

final class DomainTypePolicy
{
    public function __construct(
        private readonly DomainTypeCapabilities $capabilities,
    ) {}

    public function canBePrimary(DomainType $type): bool
    {
        return ! $this->capabilities->supportsPreview($type);
    }

    public function canBeCanonical(DomainType $type): bool
    {
        return $this->capabilities->supportsCanonical($type);
    }

    public function canBeIndexed(DomainType $type): bool
    {
        return $this->capabilities->isIndexable($type);
    }

    public function requiresAuthentication(DomainType $type): bool
    {
        return $this->capabilities->requiresAuthentication($type);
    }

    public function shouldForceNoIndex(DomainType $type): bool
    {
        return ! $this->canBeIndexed($type);
    }
}
