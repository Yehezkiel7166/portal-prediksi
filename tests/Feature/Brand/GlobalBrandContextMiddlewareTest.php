<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class GlobalBrandContextMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')
            ->get('/__global-brand-context-test', function () {
                $context = app(BrandContext::class);

                return response()->json([
                    'has' => $context->has(),
                    'code' => optional($context->get())->code,
                ]);
            });
    }

    public function test_web_middleware_group_initializes_brand_context(): void
    {
        Brand::factory()->create([
            'code' => 'DEFAULT',
            'domain' => 'localhost',
            'is_active' => true,
            'is_primary' => true,
        ]);

        $this->get('/__global-brand-context-test')
            ->assertOk()
            ->assertJson([
                'has' => true,
                'code' => 'DEFAULT',
            ]);
    }

    public function test_web_request_continues_when_brand_cannot_be_resolved(): void
    {
        $this->get('/__global-brand-context-test')
            ->assertOk()
            ->assertJson([
                'has' => false,
                'code' => null,
            ]);
    }
}
