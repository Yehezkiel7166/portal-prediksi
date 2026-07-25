<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Actions;

use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Support\DomainTypeRegistry;

final class DomainTypeOptions
{
    /**
     * @return array<string, string>
     */
    public function execute(): array
    {
        $registry = app(DomainTypeRegistry::class);

        return collect(DomainType::cases())
            ->mapWithKeys(
                static fn (DomainType $type): array => [
                    $type->value => $registry->label($type),
                ],
            )
            ->all();
    }
}
