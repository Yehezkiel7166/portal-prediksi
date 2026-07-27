<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Data\HttpsPolicyData;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;

final class ResolveHttpsPolicy
{
    public function execute(
        ?BrandDomain $domain,
        DomainType $type,
        ?bool $production = null,
    ): HttpsPolicyData {
        $production ??= app()->environment('production');

        $settings = is_array($domain?->settings)
            ? $domain->settings
            : [];

        $forceHttps = $this->booleanSetting(
            $settings,
            'force_https',
            $production,
        );

        $sendHsts = $this->booleanSetting(
            $settings,
            'send_hsts',
            $production && $forceHttps,
        );

        if ($type === DomainType::Preview) {
            $sendHsts = false;
        }

        $maxAge = $this->integerSetting(
            $settings,
            'hsts_max_age',
            31536000,
            minimum: 0,
            maximum: 63072000,
        );

        $includeSubDomains = $this->booleanSetting(
            $settings,
            'hsts_include_subdomains',
            false,
        );

        $preload = $this->booleanSetting(
            $settings,
            'hsts_preload',
            false,
        );

        if (! $includeSubDomains) {
            $preload = false;
        }

        $redirectStatus = $this->integerSetting(
            $settings,
            'https_redirect_status',
            308,
            minimum: 301,
            maximum: 308,
        );

        if (! in_array($redirectStatus, [301, 302, 307, 308], true)) {
            $redirectStatus = 308;
        }

        return new HttpsPolicyData(
            forceHttps: $forceHttps,
            sendHsts: $sendHsts,
            hstsMaxAge: $maxAge,
            includeSubDomains: $includeSubDomains,
            preload: $preload,
            redirectStatus: $redirectStatus,
        );
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function booleanSetting(
        array $settings,
        string $key,
        bool $default,
    ): bool {
        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        return filter_var(
            $settings[$key],
            FILTER_VALIDATE_BOOL,
            FILTER_NULL_ON_FAILURE,
        ) ?? $default;
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function integerSetting(
        array $settings,
        string $key,
        int $default,
        int $minimum,
        int $maximum,
    ): int {
        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        $value = filter_var(
            $settings[$key],
            FILTER_VALIDATE_INT,
        );

        if (! is_int($value)) {
            return $default;
        }

        return max(
            $minimum,
            min($maximum, $value),
        );
    }
}
