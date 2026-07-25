<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;

class ResolvePrimaryBrandDomain
{
    public function execute(
        Brand $brand,
        DomainType $type,
    ): ?BrandDomain {
        return BrandDomain::query()
            ->where('brand_id', $brand->getKey())
            ->where('type', $type)
            ->where('is_active', true)
            ->where('is_primary', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }
}
