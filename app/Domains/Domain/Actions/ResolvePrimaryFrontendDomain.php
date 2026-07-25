<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;

final class ResolvePrimaryFrontendDomain
{
    public function __construct(
        private readonly ResolveBrandDomainByType $resolver,
    ) {}

    public function execute(Brand $brand): ?BrandDomain
    {
        return $this->resolver->execute(
            brand: $brand,
            type: DomainType::Frontend,
            primaryOnly: true,
        );
    }
}
