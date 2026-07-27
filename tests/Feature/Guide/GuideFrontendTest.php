<?php
namespace Tests\Feature\Guide;
use App\Domains\Guide\Models\Guide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class GuideFrontendTest extends TestCase
{
    use RefreshDatabase;
    public function test_public_listing_only_shows_published_guides(): void
    {
        Guide::factory()->published()->create(['title'=>'Panduan Publik','slug'=>'panduan-publik']); Guide::factory()->create(['title'=>'Panduan Draft','slug'=>'panduan-draft']);
        $this->get('/panduan')->assertOk()->assertSee('Panduan Publik')->assertDontSee('Panduan Draft')->assertSee('rel="canonical"',false);
    }
    public function test_published_detail_has_content_and_seo_metadata(): void
    {
        Guide::factory()->published()->create(['title'=>'Panduan Aman','slug'=>'panduan-aman','content'=>'<p>Konten aman.</p>','seo_title'=>'SEO Panduan Aman','seo_description'=>'Deskripsi panduan aman.']);
        $this->get('/panduan/panduan-aman')->assertOk()->assertSee('Konten aman.',false)->assertSee('SEO Panduan Aman')->assertSee('Deskripsi panduan aman.');
    }
    public function test_draft_detail_returns_not_found(): void
    {
        Guide::factory()->create(['slug'=>'draft-guide']); $this->get('/panduan/draft-guide')->assertNotFound();
    }
}
