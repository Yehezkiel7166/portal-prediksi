<?php

declare(strict_types=1);

namespace App\Domains\SiteConfiguration\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use App\Domains\SiteConfiguration\Support\SiteConfigurationResolver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final readonly class UpsertSiteConfiguration
{
    public function __construct(private SiteConfigurationResolver $resolver) {}

    /** @param array<string, mixed> $data */
    public function execute(Brand $brand, array $data): SiteConfiguration
    {
        return DB::transaction(function () use ($brand, $data): SiteConfiguration {
            $payload = Arr::only($data, [
                'site_name', 'tagline', 'logo_url', 'favicon_url',
                'default_seo_title', 'default_seo_description',
                'contact_email', 'contact_phone', 'whatsapp_number',
                'social_links', 'footer_text', 'is_active',
            ]);

            foreach ($payload as $key => $value) {
                if (is_string($value)) {
                    $payload[$key] = trim($value) ?: null;
                }
            }

            $configuration = SiteConfiguration::query()->updateOrCreate(
                ['brand_id' => $brand->getKey()],
                $payload,
            );

            $this->resolver->forget($brand);

            return $configuration->refresh();
        });
    }
}
