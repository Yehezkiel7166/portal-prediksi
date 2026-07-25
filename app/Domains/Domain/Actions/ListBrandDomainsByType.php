<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Database\Eloquent\Collection;

final class ListBrandDomainsByType
{
    /**
     * @return Collection<int, BrandDomain>
     */
    public function execute(
        Brand $brand,
        DomainType $type,
        bool $activeOnly = true,
    ): Collection {
        return BrandDomain::query()
            ->where('brand_id', $brand->getKey())
            ->where('type', $type)
            ->when(
                $activeOnly,
                fn ($query) => $query->where('is_active', true),
            )
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
