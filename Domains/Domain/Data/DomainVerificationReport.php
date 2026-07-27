<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use Carbon\CarbonImmutable;

final readonly class DomainVerificationReport
{
    /**
     * @param  list<DomainVerificationCheck>  $checks
     */
    public function __construct(
        public BrandDomain $domain,
        public DomainVerificationStatus $status,
        public int $score,
        public array $checks,
        public CarbonImmutable $verifiedAt,
    ) {}

    public function hasCriticalFailure(): bool
    {
        foreach ($this->checks as $check) {
            if ($check->status === DomainVerificationStatus::Critical) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{
     *     domain_id: int,
     *     host: string,
     *     status: string,
     *     status_label: string,
     *     operational: bool,
     *     score: int,
     *     verified_at: string,
     *     checks: list<array<string, mixed>>
     * }
     */
    public function toArray(): array
    {
        return [
            'domain_id' => (int) $this->domain->getKey(),
            'host' => (string) $this->domain->host,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'operational' => $this->status->isOperational(),
            'score' => $this->score,
            'verified_at' => $this->verifiedAt->toIso8601String(),
            'checks' => array_map(
                static fn (DomainVerificationCheck $check): array => $check->toArray(),
                $this->checks,
            ),
        ];
    }
}
