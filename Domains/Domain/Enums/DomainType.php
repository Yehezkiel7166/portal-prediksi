<?php

declare(strict_types=1);

namespace App\Domains\Domain\Enums;

enum DomainType: string
{
    case Frontend = 'frontend';
    case Admin = 'admin';
    case Api = 'api';
    case Asset = 'asset';
    case Preview = 'preview';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $type): string => $type->value,
            self::cases(),
        );
    }
}
