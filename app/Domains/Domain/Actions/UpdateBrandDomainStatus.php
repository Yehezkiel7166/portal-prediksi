<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Support\Facades\DB;

class UpdateBrandDomainStatus
{
    public function execute(
        BrandDomain $domain,
        bool $isActive,
    ): BrandDomain {
        return DB::transaction(function () use ($domain, $isActive): BrandDomain {
            $lockedDomain = BrandDomain::query()
                ->whereKey($domain->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $changes = [
                'is_active' => $isActive,
            ];

            if (! $isActive) {
                $changes['is_primary'] = false;
            }

            $lockedDomain->forceFill($changes)->save();

            return $lockedDomain->refresh();
        }, 3);
    }
}
