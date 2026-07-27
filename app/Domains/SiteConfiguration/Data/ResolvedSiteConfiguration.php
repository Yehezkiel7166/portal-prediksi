<?php

declare(strict_types=1);

namespace App\Domains\SiteConfiguration\Data;

final readonly class ResolvedSiteConfiguration
{
    /** @param array<string, string> $socialLinks */
    public function __construct(
        public string $siteName,
        public ?string $tagline,
        public ?string $logoUrl,
        public ?string $faviconUrl,
        public string $defaultSeoTitle,
        public ?string $defaultSeoDescription,
        public ?string $contactEmail,
        public ?string $contactPhone,
        public ?string $whatsappNumber,
        public array $socialLinks,
        public ?string $footerText,
        public bool $fromDatabase,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'site_name' => $this->siteName,
            'tagline' => $this->tagline,
            'logo_url' => $this->logoUrl,
            'favicon_url' => $this->faviconUrl,
            'default_seo_title' => $this->defaultSeoTitle,
            'default_seo_description' => $this->defaultSeoDescription,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'whatsapp_number' => $this->whatsappNumber,
            'social_links' => $this->socialLinks,
            'footer_text' => $this->footerText,
            'from_database' => $this->fromDatabase,
        ];
    }
}
