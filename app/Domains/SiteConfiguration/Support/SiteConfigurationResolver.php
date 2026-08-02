<?php

declare(strict_types=1);

namespace App\Domains\SiteConfiguration\Support;

use App\Domains\Brand\Models\Brand;
use App\Domains\SiteConfiguration\Data\ResolvedSiteConfiguration;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

final class SiteConfigurationResolver
{
    private const CACHE_TTL_SECONDS = 300;

    private const CACHE_KEY_VERSION = 'v2';

    public function resolve(?Brand $brand): ResolvedSiteConfiguration
    {
        if ($brand === null || ! Schema::hasTable('site_configurations')) {
            return $this->fallback($brand);
        }

        $cacheKey = $this->cacheKey($brand->getKey());

        /** @var array<string, mixed>|null $configuration */
        $configuration = Cache::remember(
            $cacheKey,
            self::CACHE_TTL_SECONDS,
            static fn (): ?array => SiteConfiguration::query()
                ->where('brand_id', $brand->getKey())
                ->where('is_active', true)
                ->first()
                ?->toArray(),
        );

        if ($configuration === null) {
            return $this->fallback($brand);
        }

        $siteName = $this->filled($configuration['site_name'] ?? null)
            ?? $brand->name;

        $seoTitle = $this->filled(
            $configuration['default_seo_title'] ?? null,
        ) ?? $siteName;

        return new ResolvedSiteConfiguration(
            siteName: $siteName,
            tagline: $this->filled($configuration['tagline'] ?? null),
            logoUrl: $this->httpUrl($configuration['logo_url'] ?? null),
            faviconUrl: $this->httpUrl($configuration['favicon_url'] ?? null),
            defaultSeoTitle: $seoTitle,
            defaultSeoDescription: $this->filled(
                $configuration['default_seo_description'] ?? null,
            ),
            contactEmail: $this->filled(
                $configuration['contact_email'] ?? null,
            ),
            contactPhone: $this->filled(
                $configuration['contact_phone'] ?? null,
            ),
            whatsappNumber: $this->filled(
                $configuration['whatsapp_number'] ?? null,
            ),
            socialLinks: $this->normalizeSocialLinks(
                $configuration['social_links'] ?? null,
            ),
            footerText: $this->filled(
                $configuration['footer_text'] ?? null,
            ),
            fromDatabase: true,
        );
    }

    public function forget(Brand|int $brand): void
    {
        $brandId = $brand instanceof Brand ? $brand->getKey() : $brand;

        Cache::forget($this->cacheKey($brandId));

        // Remove the pre-v2 key that may contain a serialized Eloquent model.
        Cache::forget(sprintf('site-configuration:brand:%s', $brandId));
    }

    private function cacheKey(int|string $brandId): string
    {
        return sprintf(
            'site-configuration:%s:brand:%s',
            self::CACHE_KEY_VERSION,
            $brandId,
        );
    }

    private function fallback(?Brand $brand): ResolvedSiteConfiguration
    {
        $siteName = $brand?->name
            ?: (string) config('app.name', 'Portal Prediksi');

        return new ResolvedSiteConfiguration(
            siteName: $siteName,
            tagline: null,
            logoUrl: null,
            faviconUrl: null,
            defaultSeoTitle: $siteName,
            defaultSeoDescription: null,
            contactEmail: null,
            contactPhone: null,
            whatsappNumber: null,
            socialLinks: [],
            footerText: null,
            fromDatabase: false,
        );
    }

    private function filled(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    /** @return array<string, string> */
    private function normalizeSocialLinks(mixed $links): array
    {
        if (! is_array($links)) {
            return [];
        }

        $normalized = [];

        foreach ($links as $network => $url) {
            if (! is_string($network) || ! is_string($url)) {
                continue;
            }

            $network = trim($network);
            $url = trim($url);

            if ($network !== '' && $this->httpUrl($url) !== null) {
                $normalized[$network] = $url;
            }
        }

        return $normalized;
    }

    private function httpUrl(mixed $value): ?string
    {
        $url = $this->filled($value);

        if (
            $url === null
            || filter_var($url, FILTER_VALIDATE_URL) === false
        ) {
            return null;
        }

        $scheme = strtolower(
            (string) parse_url($url, PHP_URL_SCHEME),
        );

        return in_array($scheme, ['http', 'https'], true)
            ? $url
            : null;
    }
}
