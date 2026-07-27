<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

final readonly class DomainDnsProbeResult
{
    /**
     * @param  array<int, string>  $records
     */
    public function __construct(
        public bool $resolved,
        public array $records,
        public ?string $error = null,
    ) {}

    /**
     * @return array{
     *     resolved: bool,
     *     records: array<int, string>,
     *     error: string|null
     * }
     */
    public function toArray(): array
    {
        return [
            'resolved' => $this->resolved,
            'records' => $this->records,
            'error' => $this->error,
        ];
    }
}
