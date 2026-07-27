<?php

declare(strict_types=1);

namespace Tests\Feature\Rtp;

use Illuminate\Foundation\Testing\RefreshDatabase;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class BrandSlotRtpFoundationContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_slot_model_contract_exists(): void
    {
        $class = 'App\\Domains\\Rtp\\Models\\BrandSlot';

        $this->assertTrue(
            class_exists($class),
            '[SPRINT16B-RED] BrandSlot model has not been implemented.'
        );

        if (! class_exists($class)) {
            return;
        }

        $model = new $class();

        $this->assertInstanceOf(Model::class, $model);

        $this->assertSame(
            'brand_slots',
            $model->getTable(),
            '[SPRINT16B-RED] BrandSlot must own the brand_slots table.'
        );
    }

    public function test_rtp_snapshot_model_contract_exists(): void
    {
        $class = 'App\\Domains\\Rtp\\Models\\RtpSnapshot';

        $this->assertTrue(
            class_exists($class),
            '[SPRINT16B-RED] RtpSnapshot model has not been implemented.'
        );

        if (! class_exists($class)) {
            return;
        }

        $model = new $class();

        $this->assertInstanceOf(Model::class, $model);

        $this->assertSame(
            'rtp_snapshots',
            $model->getTable(),
            '[SPRINT16B-RED] RtpSnapshot must own the rtp_snapshots table.'
        );
    }

    public function test_brand_slot_and_rtp_snapshot_tables_exist(): void
    {
        $this->assertTrue(
            Schema::hasTable('brand_slots'),
            '[SPRINT16B-RED] brand_slots migration has not been implemented.'
        );

        $this->assertTrue(
            Schema::hasTable('rtp_snapshots'),
            '[SPRINT16B-RED] rtp_snapshots migration has not been implemented.'
        );
    }

    public function test_public_slot_gacor_route_exists(): void
    {
        $this->assertTrue(
            Route::has('slot-gacor.index'),
            '[SPRINT16B-RED] Public slot-gacor.index route has not been implemented.'
        );

        if (! Route::has('slot-gacor.index')) {
            return;
        }

        $route = Route::getRoutes()->getByName('slot-gacor.index');

        $this->assertNotNull($route);
        $this->assertSame(
            ['GET', 'HEAD'],
            $route->methods()
        );
        $this->assertSame(
            'slot-gacor',
            $route->uri()
        );
    }

    public function test_public_rtp_listing_is_available(): void
    {
        if (! Route::has('slot-gacor.index')) {
            $this->fail(
                '[SPRINT16B-RED] Public Slot Gacor / RTP listing is unavailable.'
            );
        }

        $this->get(route('slot-gacor.index'))
            ->assertOk()
            ->assertSee('Slot Gacor')
            ->assertSee('RTP');
    }

    public function test_public_rtp_listing_has_minimum_seo_metadata(): void
    {
        if (! Route::has('slot-gacor.index')) {
            $this->fail(
                '[SPRINT16B-RED] RTP SEO contract cannot run without its public route.'
            );
        }

        $response = $this->get(route('slot-gacor.index'));

        $response
            ->assertOk()
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:description"', false)
            ->assertSee('property="og:url"', false);
    }
}
