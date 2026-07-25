<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\ResolveHttpsPolicy;
use App\Domains\Domain\Actions\ShouldRedirectToHttps;
use App\Domains\Domain\Data\HttpsPolicyData;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Http\Middleware\EnforceDomainHttps;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DomainUrlSchemeConfigurator;
use App\Domains\Domain\Support\HstsHeaderBuilder;
use App\Domains\Domain\Support\HttpsUrlBuilder;
use App\Domains\Domain\Support\RequestSchemeResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HttpsDomainEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_scheme_resolver_detects_native_https(): void
    {
        $request = Request::create(
            'https://example.test/path',
            'GET',
        );

        $resolver = app(RequestSchemeResolver::class);

        $this->assertSame('https', $resolver->resolve($request));
        $this->assertTrue($resolver->isSecure($request));
    }

    public function test_scheme_resolver_detects_http(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $resolver = app(RequestSchemeResolver::class);

        $this->assertSame('http', $resolver->resolve($request));
        $this->assertFalse($resolver->isSecure($request));
    }

    public function test_scheme_resolver_uses_forwarded_proto(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $request->headers->set(
            'X-Forwarded-Proto',
            'https',
        );

        $this->assertSame(
            'https',
            app(RequestSchemeResolver::class)->resolve($request),
        );
    }

    public function test_scheme_resolver_uses_first_forwarded_proto_value(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $request->headers->set(
            'X-Forwarded-Proto',
            'https, http',
        );

        $this->assertSame(
            'https',
            app(RequestSchemeResolver::class)->resolve($request),
        );
    }

    public function test_scheme_resolver_supports_forwarded_header(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $request->headers->set(
            'Forwarded',
            'for=192.0.2.1;proto=https;host=example.test',
        );

        $this->assertSame(
            'https',
            app(RequestSchemeResolver::class)->resolve($request),
        );
    }

    public function test_production_policy_forces_https_by_default(): void
    {
        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: null,
            type: DomainType::Frontend,
            production: true,
        );

        $this->assertTrue($policy->forceHttps);
        $this->assertTrue($policy->sendHsts);
        $this->assertSame(31536000, $policy->hstsMaxAge);
        $this->assertSame(308, $policy->redirectStatus);
    }

    public function test_non_production_policy_does_not_force_https_by_default(): void
    {
        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: null,
            type: DomainType::Frontend,
            production: false,
        );

        $this->assertFalse($policy->forceHttps);
        $this->assertFalse($policy->sendHsts);
    }

    public function test_domain_settings_can_enable_https(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create([
                'type' => DomainType::Frontend,
                'settings' => [
                    'force_https' => true,
                    'send_hsts' => true,
                ],
            ]);

        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: $domain,
            type: DomainType::Frontend,
            production: false,
        );

        $this->assertTrue($policy->forceHttps);
        $this->assertTrue($policy->sendHsts);
    }

    public function test_domain_settings_can_disable_https(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create([
                'type' => DomainType::Frontend,
                'settings' => [
                    'force_https' => false,
                    'send_hsts' => false,
                ],
            ]);

        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: $domain,
            type: DomainType::Frontend,
            production: true,
        );

        $this->assertFalse($policy->forceHttps);
        $this->assertFalse($policy->sendHsts);
    }

    public function test_preview_domain_never_sends_hsts(): void
    {
        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: null,
            type: DomainType::Preview,
            production: true,
        );

        $this->assertTrue($policy->forceHttps);
        $this->assertFalse($policy->sendHsts);
    }

    public function test_policy_clamps_hsts_max_age(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create([
                'settings' => [
                    'hsts_max_age' => 999999999,
                ],
            ]);

        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: $domain,
            type: DomainType::Frontend,
            production: true,
        );

        $this->assertSame(
            63072000,
            $policy->hstsMaxAge,
        );
    }

    public function test_preload_requires_include_subdomains(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create([
                'settings' => [
                    'hsts_include_subdomains' => false,
                    'hsts_preload' => true,
                ],
            ]);

        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: $domain,
            type: DomainType::Frontend,
            production: true,
        );

        $this->assertFalse($policy->includeSubDomains);
        $this->assertFalse($policy->preload);
    }

    public function test_invalid_redirect_status_falls_back_to_308(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create([
                'settings' => [
                    'https_redirect_status' => 305,
                ],
            ]);

        $policy = app(ResolveHttpsPolicy::class)->execute(
            domain: $domain,
            type: DomainType::Frontend,
            production: true,
        );

        $this->assertSame(308, $policy->redirectStatus);
    }

    public function test_redirect_decision_redirects_insecure_request(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $policy = new HttpsPolicyData(
            forceHttps: true,
            sendHsts: true,
            hstsMaxAge: 31536000,
            includeSubDomains: false,
            preload: false,
            redirectStatus: 308,
        );

        $this->assertTrue(
            app(ShouldRedirectToHttps::class)
                ->execute($request, $policy),
        );
    }

    public function test_redirect_decision_does_not_redirect_secure_request(): void
    {
        $request = Request::create(
            'https://example.test/path',
            'GET',
        );

        $policy = new HttpsPolicyData(
            forceHttps: true,
            sendHsts: true,
            hstsMaxAge: 31536000,
            includeSubDomains: false,
            preload: false,
            redirectStatus: 308,
        );

        $this->assertFalse(
            app(ShouldRedirectToHttps::class)
                ->execute($request, $policy),
        );
    }

    public function test_redirect_decision_respects_disabled_policy(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $policy = new HttpsPolicyData(
            forceHttps: false,
            sendHsts: false,
            hstsMaxAge: 31536000,
            includeSubDomains: false,
            preload: false,
            redirectStatus: 308,
        );

        $this->assertFalse(
            app(ShouldRedirectToHttps::class)
                ->execute($request, $policy),
        );
    }

    public function test_https_url_builder_preserves_path_and_query(): void
    {
        $request = Request::create(
            'http://Example.Test/path/page?foo=bar',
            'GET',
        );

        $url = app(HttpsUrlBuilder::class)
            ->build($request);

        $this->assertSame(
            'https://example.test/path/page?foo=bar',
            $url,
        );
    }

    public function test_hsts_builder_returns_null_when_disabled(): void
    {
        $policy = new HttpsPolicyData(
            forceHttps: true,
            sendHsts: false,
            hstsMaxAge: 31536000,
            includeSubDomains: false,
            preload: false,
            redirectStatus: 308,
        );

        $this->assertNull(
            app(HstsHeaderBuilder::class)->build($policy),
        );
    }

    public function test_hsts_builder_creates_basic_header(): void
    {
        $policy = new HttpsPolicyData(
            forceHttps: true,
            sendHsts: true,
            hstsMaxAge: 31536000,
            includeSubDomains: false,
            preload: false,
            redirectStatus: 308,
        );

        $this->assertSame(
            'max-age=31536000',
            app(HstsHeaderBuilder::class)->build($policy),
        );
    }

    public function test_hsts_builder_includes_subdomains_and_preload(): void
    {
        $policy = new HttpsPolicyData(
            forceHttps: true,
            sendHsts: true,
            hstsMaxAge: 63072000,
            includeSubDomains: true,
            preload: true,
            redirectStatus: 308,
        );

        $this->assertSame(
            'max-age=63072000; includeSubDomains; preload',
            app(HstsHeaderBuilder::class)->build($policy),
        );
    }

    public function test_https_policy_data_converts_to_array(): void
    {
        $policy = new HttpsPolicyData(
            forceHttps: true,
            sendHsts: true,
            hstsMaxAge: 31536000,
            includeSubDomains: true,
            preload: false,
            redirectStatus: 308,
        );

        $this->assertSame([
            'force_https' => true,
            'send_hsts' => true,
            'hsts_max_age' => 31536000,
            'include_sub_domains' => true,
            'preload' => false,
            'redirect_status' => 308,
        ], $policy->toArray());
    }

    public function test_middleware_redirects_http_to_https(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'secure.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'settings' => [
                    'force_https' => true,
                    'send_hsts' => true,
                ],
            ]);

        $request = Request::create(
            'http://secure.example.test/path?foo=bar',
            'GET',
        );

        $response = app(EnforceDomainHttps::class)->handle(
            $request,
            static fn (): Response => new Response('OK'),
        );

        $this->assertSame(308, $response->getStatusCode());

        $this->assertSame(
            'https://secure.example.test/path?foo=bar',
            $response->headers->get('Location'),
        );
    }

    public function test_middleware_does_not_redirect_https_request(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'secure-response.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'settings' => [
                    'force_https' => true,
                    'send_hsts' => true,
                ],
            ]);

        $request = Request::create(
            'https://secure-response.example.test/path',
            'GET',
        );

        $response = app(EnforceDomainHttps::class)->handle(
            $request,
            static fn (): Response => new Response('OK'),
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getContent());
    }

    public function test_middleware_adds_hsts_on_https_response(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'hsts.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'settings' => [
                    'force_https' => true,
                    'send_hsts' => true,
                    'hsts_max_age' => 31536000,
                    'hsts_include_subdomains' => true,
                    'hsts_preload' => true,
                ],
            ]);

        $request = Request::create(
            'https://hsts.example.test/path',
            'GET',
        );

        $response = app(EnforceDomainHttps::class)->handle(
            $request,
            static fn (): Response => new Response('OK'),
        );

        $this->assertSame(
            'max-age=31536000; includeSubDomains; preload',
            $response->headers->get('Strict-Transport-Security'),
        );
    }

    public function test_middleware_does_not_add_hsts_on_http_response(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'no-hsts-http.example.test',
                'type' => DomainType::Frontend,
                'is_active' => true,
                'settings' => [
                    'force_https' => false,
                    'send_hsts' => true,
                ],
            ]);

        $request = Request::create(
            'http://no-hsts-http.example.test/path',
            'GET',
        );

        $response = app(EnforceDomainHttps::class)->handle(
            $request,
            static fn (): Response => new Response('OK'),
        );

        $this->assertNull(
            $response->headers->get('Strict-Transport-Security'),
        );
    }

    public function test_url_scheme_configurator_forces_https(): void
    {
        $request = Request::create(
            'http://example.test/path',
            'GET',
        );

        $urlGenerator = app(UrlGenerator::class);

        app(DomainUrlSchemeConfigurator::class)
            ->configure($request, true);

        $this->assertStringStartsWith(
            'https://',
            $urlGenerator->to('/test'),
        );
    }
}
