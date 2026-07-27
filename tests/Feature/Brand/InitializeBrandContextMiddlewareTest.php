<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Models\Brand;
use App\Http\Middleware\InitializeBrandContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class InitializeBrandContextMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(InitializeBrandContext::class)
            ->get('/__brand-context-test', function () {
                $context = app(\App\Domains\Brand\Support\BrandContext::class);

                return response()->json([
                    'has' => $context->has(),
                    'code' => optional($context->get())->code,
                ]);
            });
    }

    public function test_middleware_initializes_brand_context(): void
    {
        Brand::factory()->create([
            'code' => 'DEFAULT',
            'domain' => 'localhost',
            'is_active' => true,
            'is_primary' => true,
        ]);

        $this->get('/__brand-context-test')
            ->assertOk()
            ->assertJson([
                'has' => true,
                'code' => 'DEFAULT',
            ]);
    }
}
