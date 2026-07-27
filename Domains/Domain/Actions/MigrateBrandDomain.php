<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Data\DomainMigrationResult;
use App\Domains\Domain\Exceptions\InvalidBrandDomainMigration;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Support\Facades\DB;

final class MigrateBrandDomain
{
    public function __construct(
        private readonly PrepareBrandDomainMigration $prepareMigration,
        private readonly SetPrimaryBrandDomain $setPrimaryDomain,
    ) {}

    public function execute(
        BrandDomain $domain,
        Brand $targetBrand,
        bool $makePrimary = false,
    ): DomainMigrationResult {
        $plan = $this->prepareMigration->execute(
            domain: $domain,
            targetBrand: $targetBrand,
            makePrimary: $makePrimary,
        );

        if (! $plan->brandWillChange) {
            if ($makePrimary && ! $domain->is_primary) {
                $domain = $this->setPrimaryDomain->execute($domain);
            }

            return new DomainMigrationResult(
                domain: $domain->fresh() ?? $domain,
                plan: $plan,
                migrated: false,
            );
        }

        return DB::transaction(function () use (
            $domain,
            $targetBrand,
            $makePrimary,
            $plan,
        ): DomainMigrationResult {
            $lockedDomain = BrandDomain::query()
                ->lockForUpdate()
                ->find($domain->getKey());

            if (! $lockedDomain instanceof BrandDomain) {
                throw new InvalidBrandDomainMigration(
                    'The domain no longer exists.',
                );
            }

            $lockedTargetBrand = Brand::query()
                ->lockForUpdate()
                ->find($targetBrand->getKey());

            if (! $lockedTargetBrand instanceof Brand) {
                throw new InvalidBrandDomainMigration(
                    'The target brand no longer exists.',
                );
            }

            if (! $lockedTargetBrand->is_active) {
                throw new InvalidBrandDomainMigration(
                    'A domain cannot be migrated to an inactive brand.',
                );
            }

            if ($makePrimary && ! $lockedDomain->is_active) {
                throw new InvalidBrandDomainMigration(
                    'An inactive domain cannot become primary during migration.',
                );
            }

            $lockedDomain->forceFill([
                'brand_id' => $lockedTargetBrand->getKey(),
                'is_primary' => false,
            ])->save();

            if ($makePrimary) {
                $lockedDomain = $this->setPrimaryDomain->execute(
                    $lockedDomain,
                );
            }

            $migratedDomain = $lockedDomain->fresh();

            if (! $migratedDomain instanceof BrandDomain) {
                throw new InvalidBrandDomainMigration(
                    'The migrated domain could not be reloaded.',
                );
            }

            return new DomainMigrationResult(
                domain: $migratedDomain,
                plan: $plan,
                migrated: true,
            );
        });
    }
}
