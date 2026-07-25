<?php

declare(strict_types=1);

namespace App\Domains\Domain\Enums;

enum DomainVerificationStatus: string
{
    case Healthy = 'healthy';
    case Warning = 'warning';
    case Critical = 'critical';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Healthy => 'Healthy',
            self::Warning => 'Warning',
            self::Critical => 'Critical',
            self::Unknown => 'Unknown',
        };
    }

    public function isOperational(): bool
    {
        return match ($this) {
            self::Healthy, self::Warning => true,
            self::Critical, self::Unknown => false,
        };
    }
}
