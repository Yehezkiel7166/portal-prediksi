<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

use App\Domains\Domain\Models\BrandDomain;

final readonly class DomainMigrationResult
{
    public function __construct(
        public BrandDomain $domain,
        public DomainMigrationPlan $plan,
        public bool $migrated,
    ) {}

    /**
     * @return array{
     *     migrated: bool,
     *     domain_id: int,
     *     host: string,
     *     brand_id: int,
     *     type: string,
     *     is_primary: bool,
     *     plan: array<string, int|string|bool>
     * }
     */
    public function toArray(): array
    {
        return [
            'migrated' => $this->migrated,
            'domain_id' => (int) $this->domain->getKey(),
            'host' => (string) $this->domain->host,
            'brand_id' => (int) $this->domain->brand_id,
            'type' => $this->domain->type->value,
            'is_primary' => (bool) $this->domain->is_primary,
            'plan' => $this->plan->toArray(),
        ];
    }
}
