<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Brand\Models\Brand;
use Illuminate\Database\Seeder;
use RuntimeException;

final class BrandDomainSeeder extends Seeder
{
    public function run(): void
    {
        $primaryDomain = 'santoto4d-prediksi.site';

        $brand = Brand::query()
            ->withoutGlobalScopes()
            ->where('domain', $primaryDomain)
            ->first();

        if ($brand === null) {
            $availableBrands = Brand::query()
                ->withoutGlobalScopes()
                ->orderBy('id')
                ->get(['code', 'domain'])
                ->map(
                    fn (Brand $brand): string =>
                        "{$brand->code}: {$brand->domain}"
                )
                ->implode(', ');

            throw new RuntimeException(
                "Brand untuk domain {$primaryDomain} tidak ditemukan. "
                . "Brand tersedia: {$availableBrands}"
            );
        }

        Brand::query()
            ->withoutGlobalScopes()
            ->whereKeyNot($brand->getKey())
            ->update([
                'is_primary' => false,
            ]);

        $brand->forceFill([
            'is_primary' => true,
        ])->saveQuietly();
    }
}
