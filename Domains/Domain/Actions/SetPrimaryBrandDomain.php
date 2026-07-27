<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Exceptions\InvalidPrimaryBrandDomain;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Support\Facades\DB;

class SetPrimaryBrandDomain
{
    public function execute(BrandDomain $domain): BrandDomain
    {
        if (! $domain->exists) {
            throw new InvalidPrimaryBrandDomain(
                'Primary domain must already exist in the database.'
            );
        }

        if (! $domain->is_active) {
            throw new InvalidPrimaryBrandDomain(
                'Inactive domain cannot be selected as primary.'
            );
        }

        return DB::transaction(function () use ($domain): BrandDomain {
            $lockedDomain = BrandDomain::query()
                ->whereKey($domain->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (! $lockedDomain->is_active) {
                throw new InvalidPrimaryBrandDomain(
                    'Inactive domain cannot be selected as primary.'
                );
            }

            BrandDomain::query()
                ->where('brand_id', $lockedDomain->brand_id)
                ->where('type', $lockedDomain->type)
                ->whereKeyNot($lockedDomain->getKey())
                ->where('is_primary', true)
                ->update([
                    'is_primary' => false,
                    'updated_at' => now(),
                ]);

            if (! $lockedDomain->is_primary) {
                $lockedDomain->forceFill([
                    'is_primary' => true,
                ])->save();
            }

            return $lockedDomain->refresh();
        }, 3);
    }
}
