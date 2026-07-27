<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\ResolveCanonicalUrl;
use App\Domains\Domain\Actions\ResolveDomainRobotsDirective;
use App\Domains\Domain\Actions\ResolvePrimaryFrontendDomain;
use App\Domains\Domain\Data\CanonicalUrlData;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\CanonicalMetaRenderer;
use App\Domains\Domain\Support\CanonicalPathNormalizer;
use App\Domains\Domain\Support\DomainHostNormalizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CanonicalDomainEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_host_normalizer_removes_scheme_port_path_and_case(): void
    {
        $result = app(DomainHostNormalizer::class)
            ->normalize('HTTPS://Example.COM:8443/path');

        $this->assertSame('example.com', $result);
    }

    public function test_host_normalizer_removes_trailing_dot(): void
    {
        $result = app(DomainHostNormalizer::class)
            ->normalize('Example.COM.');

        $this->assertSame('example.com', $result);
    }

    public function test_host_normalizer_returns_null_for_empty_value(): void
    {
        $normalizer = app(DomainHostNormalizer::class);

        $this->assertNull($normalizer->normalize(null));
        $this->assertNull($normalizer->normalize(''));
        $this->assertNull($normalizer->normalize('   '));
    }

    public function test_path_normalizer_removes_query_and_trailing_slash(): void
    {
        $result = app(CanonicalPathNormalizer::class)
            ->normalize('/prediksi/hari-ini/?page=2');

        $this->assertSame('/prediksi/hari-ini', $result);
    }

    public function test_path_normalizer_preserves_root_path(): void
    {
        $normalizer = app(CanonicalPathNormalizer::class);

        $this->assertSame('/', $normalizer->normalize('/'));
        $this->assertSame('/', $normalizer->normalize(''));
        $this->assertSame('/', $normalizer->normalize(null));
    }

    public function test_primary_frontend_resolver_returns_primary_frontend(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'secondary.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => false,
            ]);

        $primary = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'primary.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $resolved = app(ResolvePrimaryFrontendDomain::class)
            ->execute($brand);

        $this->assertNotNull($resolved);
        $this->assertTrue($primary->is($resolved));
    }

    public function test_primary_frontend_resolver_ignores_admin_domain(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'admin.example.test',
                'type' => DomainType::Admin,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $resolved = app(ResolvePrimaryFrontendDomain::class)
            ->execute($brand);

        $this->assertNull($resolved);
    }

    public function test_primary_frontend_resolver_ignores_inactive_domain(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'inactive.example.test',
                'type' => DomainType::Frontend,
                'is_active' => false,
                'is_primary' => true,
            ]);

        $resolved = app(ResolvePrimaryFrontendDomain::class)
            ->execute($brand);

        $this->assertNull($resolved);
    }

    public function test_canonical_url_uses_primary_frontend_domain(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'Primary.Example.Test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $request = Request::create(
            'http://alias.example.test/prediksi/togel/?page=2',
            'GET',
        );

        $result = app(ResolveCanonicalUrl::class)->execute(
            brand: $brand,
            request: $request,
            currentDomainType: DomainType::Frontend,
            currentDomainIsPrimary: false,
        );

        $this->assertSame(
            'http://primary.example.test/prediksi/togel',
            $result->url,
        );

        $this->assertSame(
            'primary.example.test',
            $result->host,
        );

        $this->assertTrue($result->usesPrimaryDomain);
        $this->assertFalse($result->indexable);
        $this->assertSame('noindex, follow', $result->robots);
    }

    public function test_primary_frontend_request_is_indexable(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'primary.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $request = Request::create(
            'https://primary.example.test/results',
            'GET',
        );

        $result = app(ResolveCanonicalUrl::class)->execute(
            brand: $brand,
            request: $request,
            currentDomainType: DomainType::Frontend,
            currentDomainIsPrimary: true,
        );

        $this->assertSame(
            'https://primary.example.test/results',
            $result->url,
        );

        $this->assertTrue($result->indexable);
        $this->assertSame('index, follow', $result->robots);
    }

    public function test_canonical_url_falls_back_to_request_host(): void
    {
        $brand = Brand::factory()->create();

        $request = Request::create(
            'https://fallback.example.test/blog/article/?utm_source=test',
            'GET',
        );

        $result = app(ResolveCanonicalUrl::class)->execute(
            brand: $brand,
            request: $request,
            currentDomainType: DomainType::Frontend,
            currentDomainIsPrimary: false,
        );

        $this->assertSame(
            'https://fallback.example.test/blog/article',
            $result->url,
        );

        $this->assertFalse($result->usesPrimaryDomain);
    }

    public function test_canonical_url_respects_forwarded_https_scheme(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'secure.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'is_primary' => true,
            ]);

        $request = Request::create(
            'http://secure.example.test/results',
            'GET',
        );

        $request->headers->set(
            'X-Forwarded-Proto',
            'https',
        );

        $result = app(ResolveCanonicalUrl::class)->execute(
            brand: $brand,
            request: $request,
            currentDomainType: DomainType::Frontend,
            currentDomainIsPrimary: true,
        );

        $this->assertSame(
            'https://secure.example.test/results',
            $result->url,
        );
    }

    public function test_query_string_is_removed_from_canonical_url(): void
    {
        $brand = Brand::factory()->create();

        $request = Request::create(
            'https://example.test/results?page=2&sort=latest',
            'GET',
        );

        $result = app(ResolveCanonicalUrl::class)->execute(
            brand: $brand,
            request: $request,
        );

        $this->assertSame(
            'https://example.test/results',
            $result->url,
        );
    }

    public function test_root_canonical_url_does_not_have_extra_slash(): void
    {
        $brand = Brand::factory()->create();

        $request = Request::create(
            'https://example.test/',
            'GET',
        );

        $result = app(ResolveCanonicalUrl::class)->execute(
            brand: $brand,
            request: $request,
        );

        $this->assertSame(
            'https://example.test/',
            $result->url,
        );
    }

    public function test_admin_domain_is_noindex_nofollow(): void
    {
        $resolver = app(ResolveDomainRobotsDirective::class);

        $this->assertSame(
            'noindex, nofollow',
            $resolver->execute(
                DomainType::Admin,
                true,
            ),
        );
    }

    public function test_api_domain_is_noindex_nofollow(): void
    {
        $resolver = app(ResolveDomainRobotsDirective::class);

        $this->assertSame(
            'noindex, nofollow',
            $resolver->execute(
                DomainType::Api,
                true,
            ),
        );
    }

    public function test_asset_domain_is_noindex_nofollow(): void
    {
        $resolver = app(ResolveDomainRobotsDirective::class);

        $this->assertSame(
            'noindex, nofollow',
            $resolver->execute(
                DomainType::Asset,
                true,
            ),
        );
    }

    public function test_preview_domain_is_noindex_nofollow(): void
    {
        $resolver = app(ResolveDomainRobotsDirective::class);

        $this->assertSame(
            'noindex, nofollow',
            $resolver->execute(
                DomainType::Preview,
                true,
            ),
        );
    }

    public function test_non_primary_frontend_is_noindex_follow(): void
    {
        $resolver = app(ResolveDomainRobotsDirective::class);

        $this->assertSame(
            'noindex, follow',
            $resolver->execute(
                DomainType::Frontend,
                false,
            ),
        );
    }

    public function test_inactive_domain_is_noindex_nofollow(): void
    {
        $resolver = app(ResolveDomainRobotsDirective::class);

        $this->assertSame(
            'noindex, nofollow',
            $resolver->execute(
                DomainType::Frontend,
                true,
                false,
            ),
        );
    }

    public function test_canonical_data_can_be_converted_to_array(): void
    {
        $data = new CanonicalUrlData(
            url: 'https://example.test/page',
            scheme: 'https',
            host: 'example.test',
            path: '/page',
            usesPrimaryDomain: true,
            indexable: true,
            robots: 'index, follow',
        );

        $this->assertSame([
            'url' => 'https://example.test/page',
            'scheme' => 'https',
            'host' => 'example.test',
            'path' => '/page',
            'uses_primary_domain' => true,
            'indexable' => true,
            'robots' => 'index, follow',
        ], $data->toArray());
    }

    public function test_meta_renderer_creates_canonical_and_robots_tags(): void
    {
        $data = new CanonicalUrlData(
            url: 'https://example.test/blog',
            scheme: 'https',
            host: 'example.test',
            path: '/blog',
            usesPrimaryDomain: true,
            indexable: true,
            robots: 'index, follow',
        );

        $html = app(CanonicalMetaRenderer::class)->render($data);

        $this->assertStringContainsString(
            '<link rel="canonical" href="https://example.test/blog">',
            $html,
        );

        $this->assertStringContainsString(
            '<meta name="robots" content="index, follow">',
            $html,
        );
    }

    public function test_meta_renderer_escapes_unsafe_values(): void
    {
        $data = new CanonicalUrlData(
            url: 'https://example.test/?value="unsafe"',
            scheme: 'https',
            host: 'example.test',
            path: '/',
            usesPrimaryDomain: true,
            indexable: false,
            robots: 'noindex, follow',
        );

        $html = app(CanonicalMetaRenderer::class)->canonicalTag($data);

        $this->assertStringNotContainsString(
            'value="unsafe"',
            $html,
        );

        $this->assertStringContainsString(
            '&quot;unsafe&quot;',
            $html,
        );
    }
}
