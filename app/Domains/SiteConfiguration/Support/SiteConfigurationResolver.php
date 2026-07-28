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

    public function resolve(?Brand $brand): ResolvedSiteConfiguration
    {
        if ($brand === null || ! Schema::hasTable('site_configurations')) {
            return $this->fallback($brand);
        }

        $cacheKey = sprintf('site-configuration:brand:%s', $brand->getKey());

        /** @var SiteConfiguration|null $configuration */
        $configuration = Cache::remember(
            $cacheKey,
            self::CACHE_TTL_SECONDS,
            fn (): ?SiteConfiguration => SiteConfiguration::query()
                ->where('brand_id', $brand->getKey())
                ->where('is_active', true)
                ->first(),
        );

        if ($configuration === null) {
            return $this->fallback($brand);
        }

        $siteName = $this->filled($configuration->site_name) ?? $brand->name;
        $seoTitle = $this->filled($configuration->default_seo_title) ?? $siteName;

        return new ResolvedSiteConfiguration(
            siteName: $siteName,
            tagline: $this->filled($configuration->tagline),
            logoUrl: $this->httpUrl($configuration->logo_url),
            faviconUrl: $this->httpUrl($configuration->favicon_url),
            defaultSeoTitle: $seoTitle,
            defaultSeoDescription: $this->filled($configuration->default_seo_description),
            contactEmail: $this->filled($configuration->contact_email),
            contactPhone: $this->filled($configuration->contact_phone),
            whatsappNumber: $this->filled($configuration->whatsapp_number),
            socialLinks: $this->normalizeSocialLinks($configuration->social_links),
            footerText: $this->filled($configuration->footer_text),
            fromDatabase: true,
        );
    }

    public function forget(Brand|int $brand): void
    {
        $brandId = $brand instanceof Brand ? $brand->getKey() : $brand;
        Cache::forget(sprintf('site-configuration:brand:%s', $brandId));
    }

    private function fallback(?Brand $brand): ResolvedSiteConfiguration
    {
        $siteName = $brand?->name ?: (string) config('app.name', 'Portal Prediksi');

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

        if ($url === null || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https'], true) ? $url : null;
    }
}
