<?php

declare(strict_types=1);

namespace Tests\Feature\Brand;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Http\Middleware\InitializeFilamentBrandContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class InitializeFilamentBrandContextMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_resolves_brand_when_context_is_empty(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'santoto4d-prediksi.site',
            'is_active' => true,
            'is_primary' => true,
        ]);

        $context = app(BrandContext::class);
        $context->clear();

        $request = Request::create(
            'https://santoto4d-prediksi.site/admin',
            'GET',
        );

        app(InitializeFilamentBrandContext::class)->handle(
            $request,
            static fn (): Response => new Response('OK'),
        );

        $this->assertTrue($context->has());
        $this->assertTrue($context->get()?->is($brand));
    }

    public function test_it_preserves_existing_brand_context(): void
    {
        $existingBrand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => false,
        ]);

        Brand::factory()->create([
            'domain' => 'localhost',
            'is_active' => true,
            'is_primary' => true,
        ]);

        $context = app(BrandContext::class);
        $context->set($existingBrand);

        $request = Request::create('http://localhost/admin', 'GET');

        app(InitializeFilamentBrandContext::class)->handle(
            $request,
            static fn (): Response => new Response('OK'),
        );

        $this->assertTrue($context->get()?->is($existingBrand));
    }
}
