<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Enums\DomainType;

final class DomainTypeRegistry
{
    public function __construct(
        private readonly DomainTypeCapabilities $capabilities,
    ) {}

    /**
     * @return array<string, array{
     *     value: string,
     *     name: string,
     *     label: string,
     *     capabilities: array<string, bool>
     * }>
     */
    public function all(): array
    {
        $registry = [];

        foreach (DomainType::cases() as $type) {
            $registry[$type->value] = [
                'value' => $type->value,
                'name' => $type->name,
                'label' => $this->label($type),
                'capabilities' => $this->capabilities->for($type),
            ];
        }

        return $registry;
    }

    /**
     * @return array<int, string>
     */
    public function values(): array
    {
        return array_map(
            static fn (DomainType $type): string => $type->value,
            DomainType::cases(),
        );
    }

    public function label(DomainType $type): string
    {
        return str($type->value)
            ->replace(['-', '_'], ' ')
            ->title()
            ->toString();
    }
}
