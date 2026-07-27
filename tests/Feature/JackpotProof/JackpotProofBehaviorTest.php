<?php

namespace Tests\Feature\JackpotProof;

use App\Domains\Brand\Models\Brand;
use App\Domains\JackpotProof\Models\JackpotProof;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JackpotProofBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_listing_is_brand_scoped_and_only_shows_approved_published_records(): void
    {
        $brand = Brand::factory()->create(['domain' => 'jackpot-proof.test']);
        $other = Brand::factory()->create(['domain' => 'other-jackpot-proof.test']);

        JackpotProof::factory()->published()->create(['brand_id' => $brand->id, 'title' => 'Visible Jackpot']);
        JackpotProof::factory()->create(['brand_id' => $brand->id, 'title' => 'Draft Jackpot']);
        JackpotProof::factory()->published()->create(['brand_id' => $other->id, 'title' => 'Other Brand Jackpot']);

        $this->get('http://'.$brand->domain.'/bukti-jackpot')
            ->assertOk()
            ->assertSee('Visible Jackpot')
            ->assertDontSee('Draft Jackpot')
            ->assertDontSee('Other Brand Jackpot');
    }

    public function test_public_listing_has_safe_empty_state_and_seo_metadata(): void
    {
        $brand = Brand::factory()->create(['domain' => 'empty-jackpot-proof.test']);
        $this->get('http://'.$brand->domain.'/bukti-jackpot')
            ->assertOk()
            ->assertSee('Belum ada bukti jackpot')
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:title"', false);
    }

    public function test_future_publication_is_not_visible(): void
    {
        $brand = Brand::factory()->create(['domain' => 'future-jackpot-proof.test']);
        JackpotProof::factory()->published()->create(['brand_id' => $brand->id, 'title' => 'Future Jackpot', 'published_at' => now()->addDay()]);
        $this->get('http://'.$brand->domain.'/bukti-jackpot')->assertOk()->assertDontSee('Future Jackpot');
    }
}
