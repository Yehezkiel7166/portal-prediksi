<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class DatabaseBrandResolver implements BrandResolver
{
    public function resolve(): ?Brand
    {
        $defaultCode = trim((string) config('brand.default_code'));

        if ($defaultCode === '') {
            return null;
        }

        try {
            if (! Schema::hasTable('brands')) {
                return null;
            }

            return Brand::query()
                ->where('code', $defaultCode)
                ->where('is_active', true)
                ->first();
        } catch (QueryException) {
            return null;
        }
    }
}
