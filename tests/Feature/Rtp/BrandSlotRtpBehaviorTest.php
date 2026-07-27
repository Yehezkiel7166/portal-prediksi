<?php

declare(strict_types=1);

namespace Tests\Feature\Rtp;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Rtp\Models\BrandSlot;
use App\Domains\Rtp\Models\RtpSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

final class BrandSlotRtpBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_listing_is_brand_scoped_ordered_and_uses_latest_snapshot(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'brand-slot-rtp.test',
        ]);

        $otherBrand = Brand::factory()->create([
            'domain' => 'other-brand-slot-rtp.test',
        ]);

        $visible = BrandSlot::factory()
            ->for($brand)
            ->published()
            ->create([
                'game_name' => 'Visible Game',
                'sort_order' => 1,
            ]);

        RtpSnapshot::factory()
            ->for($visible)
            ->create([
                'brand_id' => $brand->id,
                'rtp_value' => 91.25,
                'captured_at' => now()->subHour(),
            ]);

        RtpSnapshot::factory()
            ->for($visible)
            ->create([
                'brand_id' => $brand->id,
                'rtp_value' => 96.50,
                'captured_at' => now(),
            ]);

        BrandSlot::factory()
            ->for($brand)
            ->create([
                'game_name' => 'Draft Game',
            ]);

        BrandSlot::withoutGlobalScopes()->forceCreate([
            'brand_id' => $otherBrand->id,
            'provider_name' => 'Other',
            'game_name' => 'Other Game',
            'slug' => 'other-game',
            'is_active' => true,
            'is_published' => true,
            'sort_order' => 0,
        ]);

        $this
            ->get('http://'.$brand->domain.'/slot-gacor')
            ->assertOk()
            ->assertSee('Visible Game')
            ->assertSee('96.50%')
            ->assertDontSee('Draft Game')
            ->assertDontSee('Other Game');
    }

    public function test_public_listing_has_safe_empty_state(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'empty-brand-slot-rtp.test',
        ]);

        $this
            ->get('http://'.$brand->domain.'/slot-gacor')
            ->assertOk()
            ->assertSee('Belum ada data Slot Gacor');
    }

    public function test_snapshot_is_immutable(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $slot = BrandSlot::factory()
            ->for($brand)
            ->create();

        $snapshot = RtpSnapshot::factory()
            ->for($slot)
            ->create([
                'brand_id' => $brand->id,
            ]);

        $this->expectException(LogicException::class);

        $snapshot->update([
            'rtp_value' => 1,
        ]);
    }
}
