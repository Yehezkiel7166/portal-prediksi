<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

final readonly class DomainMigrationPlan
{
    public function __construct(
        public int $domainId,
        public string $host,
        public int $sourceBrandId,
        public int $targetBrandId,
        public string $type,
        public bool $wasPrimary,
        public bool $willBecomePrimary,
        public bool $brandWillChange,
    ) {}

    /**
     * @return array{
     *     domain_id: int,
     *     host: string,
     *     source_brand_id: int,
     *     target_brand_id: int,
     *     type: string,
     *     was_primary: bool,
     *     will_become_primary: bool,
     *     brand_will_change: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'domain_id' => $this->domainId,
            'host' => $this->host,
            'source_brand_id' => $this->sourceBrandId,
            'target_brand_id' => $this->targetBrandId,
            'type' => $this->type,
            'was_primary' => $this->wasPrimary,
            'will_become_primary' => $this->willBecomePrimary,
            'brand_will_change' => $this->brandWillChange,
        ];
    }
}
