<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Support\DomainHostNormalizer;
use Illuminate\Validation\ValidationException;

final class NormalizeBrandDomainFormData
{
    public function __construct(
        private readonly BrandContext $brandContext,
        private readonly DomainHostNormalizer $hostNormalizer,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(array $data): array
    {
        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw ValidationException::withMessages([
                'host' => 'Brand context belum aktif.',
            ]);
        }

        $host = $this->hostNormalizer->normalize(
            isset($data['host'])
                ? (string) $data['host']
                : null,
        );

        if ($host === null) {
            throw ValidationException::withMessages([
                'host' => 'Host domain tidak valid.',
            ]);
        }

        $type = DomainType::tryFrom(
            (string) ($data['type'] ?? ''),
        );

        if ($type === null) {
            throw ValidationException::withMessages([
                'type' => 'Tipe domain tidak valid.',
            ]);
        }

        $isActive = (bool) ($data['is_active'] ?? false);
        $isPrimary = $isActive
            && (bool) ($data['is_primary'] ?? false);

        $forceHttps = (bool) ($data['force_https'] ?? true);

        $includeSubdomains = (bool) data_get(
            $data,
            'settings.hsts_include_subdomains',
            false,
        );

        $data['brand_id'] = $brand->getKey();
        $data['host'] = $host;
        $data['type'] = $type->value;
        $data['is_active'] = $isActive;
        $data['is_primary'] = $isPrimary;
        $data['force_https'] = $forceHttps;
        $data['sort_order'] = max(
            0,
            (int) ($data['sort_order'] ?? 0),
        );

        $data['settings'] = [
            'send_hsts' => (bool) data_get(
                $data,
                'settings.send_hsts',
                true,
            ),
            'hsts_max_age' => max(
                0,
                min(
                    63072000,
                    (int) data_get(
                        $data,
                        'settings.hsts_max_age',
                        31536000,
                    ),
                ),
            ),
            'hsts_include_subdomains' => $includeSubdomains,
            'hsts_preload' => $includeSubdomains
                && (bool) data_get(
                    $data,
                    'settings.hsts_preload',
                    false,
                ),
            'https_redirect_status' => $this->redirectStatus(
                data_get(
                    $data,
                    'settings.https_redirect_status',
                    308,
                ),
            ),
        ];

        return $data;
    }

    private function redirectStatus(mixed $value): int
    {
        $status = (int) $value;

        return in_array(
            $status,
            [301, 302, 307, 308],
            true,
        )
            ? $status
            : 308;
    }
}
