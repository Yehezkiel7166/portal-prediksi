<?php

declare(strict_types=1);

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DatabaseBrandResolver implements BrandResolver
{
    public function resolve(?Request $request = null): ?Brand
    {
        try {
            if (! Schema::hasTable('brands')) {
                return null;
            }

            $activeBrands = Brand::query()
                ->withoutGlobalScopes()
                ->where('is_active', true);

            if ($request !== null) {
                $host = $this->normalizeHost($request->getHost());

                if ($host !== '') {
                    $brand = $this->resolveFromBrandDomains($host);

                    if ($brand !== null) {
                        return $brand;
                    }

                    $legacyBrand = (clone $activeBrands)
                        ->whereRaw('LOWER(domain) = ?', [$host])
                        ->first();

                    if ($legacyBrand !== null) {
                        return $legacyBrand;
                    }
                }
            }

            return (clone $activeBrands)
                ->where('is_primary', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();
        } catch (QueryException) {
            return null;
        }
    }

    private function resolveFromBrandDomains(string $host): ?Brand
    {
        if (! Schema::hasTable('brand_domains')) {
            return null;
        }

        $domain = BrandDomain::query()
            ->whereRaw('LOWER(host) = ?', [$host])
            ->where('is_active', true)
            ->whereHas('brand', function (Builder $query): void {
                $query
                    ->withoutGlobalScopes()
                    ->where('is_active', true);
            })
            ->with([
                'brand' => function ($query): void {
                    $query->withoutGlobalScopes();
                },
            ])
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();

        return $domain?->brand;
    }

    private function normalizeHost(string $host): string
    {
        return strtolower(trim($host, " \t\n\r\0\x0B."));
    }
}
