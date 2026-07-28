<?php

declare(strict_types=1);

namespace Tests\Feature\SiteConfiguration;

use App\Domains\Brand\Models\Brand;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SiteConfigurationFrontendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_layout_consumes_active_brand_configuration(): void
    {
        $brand = Brand::factory()->create(['name' => 'Fallback Brand', 'is_active' => true, 'is_primary' => true]);
        SiteConfiguration::query()->create([
            'brand_id' => $brand->id,
            'site_name' => 'Database Brand',
            'tagline' => 'Trusted predictions',
            'logo_url' => 'https://cdn.example.com/logo.png',
            'favicon_url' => 'https://cdn.example.com/favicon.ico',
            'default_seo_title' => 'Database SEO Title',
            'default_seo_description' => 'Database SEO description.',
            'contact_email' => 'help@example.com',
            'contact_phone' => '+62 21 555 0100',
            'whatsapp_number' => '+62 812 3456 7890',
            'social_links' => ['instagram' => 'https://instagram.com/database-brand'],
            'footer_text' => 'Database footer text.',
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertViewHas('siteConfiguration')
            ->assertSee('<title>Database SEO Title</title>', false)
            ->assertSee('content="Database SEO description."', false)
            ->assertSee('href="https://cdn.example.com/favicon.ico"', false)
            ->assertSee('src="https://cdn.example.com/logo.png"', false)
            ->assertSee('Database Brand')
            ->assertSee('Trusted predictions')
            ->assertSee('help@example.com')
            ->assertSee('+62 21 555 0100')
            ->assertSee('https://instagram.com/database-brand', false)
            ->assertSee('Database footer text.');
    }

    public function test_frontend_uses_safe_seo_and_identity_fallbacks_without_configuration(): void
    {
        Brand::factory()->create(['name' => 'Fallback Brand', 'is_active' => true, 'is_primary' => true]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<title>Fallback Brand</title>', false)
            ->assertSee('Fallback Brand')
            ->assertSee('Portal informasi prediksi, hasil pasaran, live draw, dan kalender shio.')
            ->assertDontSee('<link rel="icon"', false);
    }

    public function test_unsafe_asset_and_social_url_schemes_are_not_rendered(): void
    {
        $brand = Brand::factory()->create(['is_active' => true, 'is_primary' => true]);
        SiteConfiguration::query()->create([
            'brand_id' => $brand->id,
            'site_name' => 'Safe Brand',
            'logo_url' => 'javascript:alert(1)',
            'favicon_url' => 'data:text/html,unsafe',
            'social_links' => [
                'unsafe' => 'ftp://example.com/file',
                'safe' => 'http://example.com/profile',
            ],
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('javascript:alert(1)', false)
            ->assertDontSee('data:text/html,unsafe', false)
            ->assertDontSee('ftp://example.com/file', false)
            ->assertSee('http://example.com/profile', false);
    }
}
