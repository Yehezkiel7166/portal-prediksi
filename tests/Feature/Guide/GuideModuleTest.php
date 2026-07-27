<?php
namespace Tests\Feature\Guide;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Guide\Actions\UpsertGuideAction;
use App\Domains\Guide\Models\Guide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class GuideModuleTest extends TestCase
{
    use RefreshDatabase;
    public function test_action_creates_brand_scoped_normalized_guide(): void
    {
        $brand=Brand::factory()->create(); app(BrandContext::class)->set($brand);
        $guide=app(UpsertGuideAction::class)->execute(['title'=>'  Panduan Keamanan Akun  ','slug'=>'','excerpt'=>'Ringkas','content'=>'<p>Aman</p>','category'=>' Keamanan ','status'=>'draft','published_at'=>null,'sort_order'=>1,'seo_title'=>null,'seo_description'=>null,'notes'=>null]);
        $this->assertSame($brand->id,$guide->brand_id); $this->assertSame('Panduan Keamanan Akun',$guide->title); $this->assertSame('panduan-keamanan-akun',$guide->slug); $this->assertSame('Keamanan',$guide->category);
    }
    public function test_published_scope_hides_draft_and_future_guides(): void
    {
        $published=Guide::factory()->published()->create(); Guide::factory()->create(); Guide::factory()->create(['status'=>Guide::STATUS_PUBLISHED,'published_at'=>now()->addDay()]);
        $results=Guide::query()->published()->get(); $this->assertCount(1,$results); $this->assertTrue($results->contains($published));
    }
}
