<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
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

            $query = Brand::query()
                ->withoutGlobalScopes()
                ->where('is_active', true);

            if ($request !== null) {

                $host = strtolower($request->getHost());

                if ($host !== '') {

                    $brand = (clone $query)
                        ->whereRaw('LOWER(domain)=?', [$host])
                        ->first();

                    if ($brand !== null) {
                        return $brand;
                    }
                }
            }

            return (clone $query)
                ->where('is_primary', true)
                ->first();

        } catch (QueryException) {
            return null;
        }
    }
}
