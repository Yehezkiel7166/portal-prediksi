<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;

final class ResolveBrandDomainByType
{
    public function execute(
        Brand $brand,
        DomainType $type,
        bool $primaryOnly = false,
    ): ?BrandDomain {
        return BrandDomain::query()
            ->where('brand_id', $brand->getKey())
            ->where('type', $type)
            ->where('is_active', true)
            ->when(
                $primaryOnly,
                fn ($query) => $query->where('is_primary', true),
            )
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }
}
