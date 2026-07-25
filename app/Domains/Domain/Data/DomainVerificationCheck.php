<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

use App\Domains\Domain\Enums\DomainVerificationStatus;

final readonly class DomainVerificationCheck
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public string $key,
        public string $label,
        public DomainVerificationStatus $status,
        public string $message,
        public int $weight = 1,
        public array $metadata = [],
    ) {}

    public function passed(): bool
    {
        return $this->status === DomainVerificationStatus::Healthy;
    }

    public function score(): int
    {
        return match ($this->status) {
            DomainVerificationStatus::Healthy => 100,
            DomainVerificationStatus::Warning => 60,
            DomainVerificationStatus::Critical => 0,
            DomainVerificationStatus::Unknown => 0,
        };
    }

    /**
     * @return array{
     *     key: string,
     *     label: string,
     *     status: string,
     *     message: string,
     *     weight: int,
     *     score: int,
     *     passed: bool,
     *     metadata: array<string, mixed>
     * }
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'status' => $this->status->value,
            'message' => $this->message,
            'weight' => $this->weight,
            'score' => $this->score(),
            'passed' => $this->passed(),
            'metadata' => $this->metadata,
        ];
    }
}
