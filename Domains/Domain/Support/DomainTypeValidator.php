<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Exceptions\InvalidDomainType;

final class DomainTypeValidator
{
    public function validate(DomainType|string $type): DomainType
    {
        if ($type instanceof DomainType) {
            return $type;
        }

        $normalized = strtolower(trim($type));

        $resolved = collect(DomainType::cases())
            ->first(
                fn (DomainType $case): bool => strtolower($case->value) === $normalized
                    || strtolower($case->name) === $normalized
            );

        if (! $resolved instanceof DomainType) {
            throw new InvalidDomainType(
                sprintf(
                    'Unsupported domain type [%s]. Supported types: %s.',
                    $type,
                    implode(', ', $this->supportedValues()),
                )
            );
        }

        return $resolved;
    }

    public function supports(DomainType|string $type): bool
    {
        try {
            $this->validate($type);

            return true;
        } catch (InvalidDomainType) {
            return false;
        }
    }

    /**
     * @return array<int, string>
     */
    public function supportedValues(): array
    {
        return array_map(
            static fn (DomainType $case): string => $case->value,
            DomainType::cases(),
        );
    }
}
