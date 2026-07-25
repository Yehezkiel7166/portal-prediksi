<?php

declare(strict_types=1);

namespace App\Domains\Domain\Enums;

enum DomainHealthStatus: string
{
    case Healthy = 'healthy';
    case Degraded = 'degraded';
    case Unhealthy = 'unhealthy';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Healthy => 'Healthy',
            self::Degraded => 'Degraded',
            self::Unhealthy => 'Unhealthy',
            self::Unknown => 'Unknown',
        };
    }

    public function isOperational(): bool
    {
        return match ($this) {
            self::Healthy, self::Degraded => true,
            self::Unhealthy, self::Unknown => false,
        };
    }
}
