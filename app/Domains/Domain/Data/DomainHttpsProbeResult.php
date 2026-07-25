<?php

declare(strict_types=1);

namespace App\Domains\Domain\Data;

final readonly class DomainHttpsProbeResult
{
    public function __construct(
        public bool $reachable,
        public ?int $statusCode = null,
        public ?string $error = null,
    ) {}

    public function acceptsTraffic(): bool
    {
        return $this->reachable
            && $this->statusCode !== null
            && $this->statusCode >= 200
            && $this->statusCode < 400;
    }

    /**
     * @return array{
     *     reachable: bool,
     *     status_code: int|null,
     *     error: string|null
     * }
     */
    public function toArray(): array
    {
        return [
            'reachable' => $this->reachable,
            'status_code' => $this->statusCode,
            'error' => $this->error,
        ];
    }
}
