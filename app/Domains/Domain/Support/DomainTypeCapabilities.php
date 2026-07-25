<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Enums\DomainType;

final class DomainTypeCapabilities
{
    /**
     * @return array<string, bool>
     */
    public function for(DomainType $type): array
    {
        return [
            'serves_public_content' => $this->servesPublicContent($type),
            'serves_admin_panel' => $this->servesAdminPanel($type),
            'serves_api' => $this->servesApi($type),
            'serves_static_assets' => $this->servesStaticAssets($type),
            'supports_canonical' => $this->supportsCanonical($type),
            'supports_preview' => $this->supportsPreview($type),
            'requires_authentication' => $this->requiresAuthentication($type),
            'indexable' => $this->isIndexable($type),
        ];
    }

    public function servesPublicContent(DomainType $type): bool
    {
        return $this->is($type, 'frontend');
    }

    public function servesAdminPanel(DomainType $type): bool
    {
        return $this->is($type, 'admin');
    }

    public function servesApi(DomainType $type): bool
    {
        return $this->is($type, 'api');
    }

    public function servesStaticAssets(DomainType $type): bool
    {
        return $this->isAny($type, ['cdn', 'asset', 'assets']);
    }

    public function supportsCanonical(DomainType $type): bool
    {
        return $this->is($type, 'frontend');
    }

    public function supportsPreview(DomainType $type): bool
    {
        return $this->is($type, 'preview');
    }

    public function requiresAuthentication(DomainType $type): bool
    {
        return $this->is($type, 'admin');
    }

    public function isIndexable(DomainType $type): bool
    {
        return $this->is($type, 'frontend');
    }

    private function is(DomainType $type, string $value): bool
    {
        return strtolower($type->value) === strtolower($value);
    }

    /**
     * @param  array<int, string>  $values
     */
    private function isAny(DomainType $type, array $values): bool
    {
        $normalizedType = strtolower($type->value);

        return in_array(
            $normalizedType,
            array_map('strtolower', $values),
            true,
        );
    }
}
