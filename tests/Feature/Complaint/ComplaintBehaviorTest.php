<?php

namespace Tests\Feature\Complaint;

use App\Domains\Brand\Models\Brand;
use App\Domains\Complaint\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_form_is_available_and_noindex(): void
    {
        $brand = Brand::factory()->create(['domain' => 'complaint-form.test']);

        $this->get('http://'.$brand->domain.'/keluhan')
            ->assertOk()
            ->assertSee('Form Keluhan')
            ->assertSee('name="robots" content="noindex,follow"', false);
    }

    public function test_public_submission_is_brand_scoped_and_returns_reference_code(): void
    {
        $brand = Brand::factory()->create(['domain' => 'complaint-submit.test']);

        $response = $this->post('http://'.$brand->domain.'/keluhan', [
            'name' => 'Pengguna Satu',
            'contact' => 'user@example.test',
            'subject' => 'Kendala membuka halaman',
            'message' => 'Saya mengalami kendala saat membuka halaman hasil pada perangkat saya.',
            'website' => '',
        ]);

        $response->assertRedirect(route('complaints.create'))
            ->assertSessionHas('complaint_submitted', true)
            ->assertSessionHas('complaint_reference');

        $complaint = Complaint::withoutGlobalScopes()->sole();
        $this->assertSame($brand->id, $complaint->brand_id);
        $this->assertSame(Complaint::STATUS_OPEN, $complaint->status);
        $this->assertStringStartsWith('KLG-', $complaint->reference_code);
    }

    public function test_submission_rejects_short_message_and_honeypot_content(): void
    {
        $brand = Brand::factory()->create(['domain' => 'complaint-validation.test']);

        $this->from('http://'.$brand->domain.'/keluhan')
            ->post('http://'.$brand->domain.'/keluhan', [
                'name' => 'Spam',
                'contact' => 'spam@example.test',
                'subject' => 'Spam',
                'message' => 'terlalu singkat',
                'website' => 'https://spam.example',
            ])
            ->assertRedirect('http://'.$brand->domain.'/keluhan')
            ->assertSessionHasErrors(['message', 'website']);

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_complaints_are_isolated_by_brand_scope(): void
    {
        $brand = Brand::factory()->create(['domain' => 'complaint-a.test']);
        $other = Brand::factory()->create(['domain' => 'complaint-b.test']);
        Complaint::factory()->create(['brand_id' => $brand->id, 'subject' => 'Brand A']);
        Complaint::factory()->create(['brand_id' => $other->id, 'subject' => 'Brand B']);

        $this->get('http://'.$brand->domain.'/')->assertOk();

        $this->assertSame(['Brand A'], Complaint::query()->pluck('subject')->all());
    }
}
