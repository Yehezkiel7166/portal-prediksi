<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

use App\Domains\Domain\Enums\DomainHealthStatus;
use Carbon\CarbonImmutable;

final readonly class DomainHealthReport
{
    /**
     * @param  array<string, array<string, mixed>>  $checks
     */
    public function __construct(
        public string $host,
        public DomainHealthStatus $status,
        public array $checks,
        public CarbonImmutable $checkedAt,
    ) {}

    public function isOperational(): bool
    {
        return $this->status->isOperational();
    }

    /**
     * @return array{
     *     host: string,
     *     status: string,
     *     operational: bool,
     *     checks: array<string, array<string, mixed>>,
     *     checked_at: string
     * }
     */
    public function toArray(): array
    {
        return [
            'host' => $this->host,
            'status' => $this->status->value,
            'operational' => $this->isOperational(),
            'checks' => $this->checks,
            'checked_at' => $this->checkedAt->toIso8601String(),
        ];
    }
}
