<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Data\DomainMigrationPlan;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Exceptions\InvalidBrandDomainMigration;
use App\Domains\Domain\Models\BrandDomain;

final class PrepareBrandDomainMigration
{
    public function execute(
        BrandDomain $domain,
        Brand $targetBrand,
        bool $makePrimary = false,
    ): DomainMigrationPlan {
        if (! $domain->exists || $domain->getKey() === null) {
            throw new InvalidBrandDomainMigration(
                'The domain must exist before it can be migrated.',
            );
        }

        if (! $targetBrand->exists || $targetBrand->getKey() === null) {
            throw new InvalidBrandDomainMigration(
                'The target brand must exist before a domain can be migrated.',
            );
        }

        if (! $targetBrand->is_active) {
            throw new InvalidBrandDomainMigration(
                'A domain cannot be migrated to an inactive brand.',
            );
        }

        if ($makePrimary && ! $domain->is_active) {
            throw new InvalidBrandDomainMigration(
                'An inactive domain cannot become primary during migration.',
            );
        }

        $type = $domain->type;

        if (! $type instanceof DomainType) {
            throw new InvalidBrandDomainMigration(
                'The domain type is invalid.',
            );
        }

        return new DomainMigrationPlan(
            domainId: (int) $domain->getKey(),
            host: (string) $domain->host,
            sourceBrandId: (int) $domain->brand_id,
            targetBrandId: (int) $targetBrand->getKey(),
            type: $type->value,
            wasPrimary: (bool) $domain->is_primary,
            willBecomePrimary: $makePrimary,
            brandWillChange: (int) $domain->brand_id
                !== (int) $targetBrand->getKey(),
        );
    }
}
