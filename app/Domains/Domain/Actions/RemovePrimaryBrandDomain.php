<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Support\Facades\DB;

class RemovePrimaryBrandDomain
{
    public function execute(BrandDomain $domain): BrandDomain
    {
        if (! $domain->exists) {
            return $domain;
        }

        return DB::transaction(function () use ($domain): BrandDomain {
            $lockedDomain = BrandDomain::query()
                ->whereKey($domain->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedDomain->is_primary) {
                $lockedDomain->forceFill([
                    'is_primary' => false,
                ])->save();
            }

            return $lockedDomain->refresh();
        }, 3);
    }
}
