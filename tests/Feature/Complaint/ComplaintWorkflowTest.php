<?php

namespace Tests\Feature\Complaint;

use App\Domains\Brand\Models\Brand;
use App\Domains\Complaint\Actions\UpdateComplaintAction;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Models\ComplaintStatusHistory;
use App\Domains\Complaint\Notifications\NewComplaintSubmitted;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ComplaintWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_submission_creates_initial_history_and_notifies_administrators(): void
    {
        Notification::fake();
        $administrator = User::factory()->create(['is_admin' => true]);
        $brand = Brand::factory()->create(['domain' => 'complaint-notification.test']);

        $this->post('http://'.$brand->domain.'/keluhan', [
            'name' => 'Pengguna Satu',
            'contact' => 'user@example.test',
            'subject' => 'Kendala membuka halaman',
            'message' => 'Saya mengalami kendala saat membuka halaman hasil pada perangkat saya.',
            'website' => '',
        ])->assertRedirect(route('complaints.create'));

        $complaint = Complaint::withoutGlobalScopes()->sole();
        $history = ComplaintStatusHistory::withoutGlobalScopes()->sole();

        $this->assertNull($history->from_status);
        $this->assertSame(Complaint::STATUS_OPEN, $history->to_status);
        $this->assertSame($complaint->id, $history->complaint_id);
        Notification::assertSentTo($administrator, NewComplaintSubmitted::class);
    }

    public function test_admin_can_progress_and_resolve_complaint_with_auditable_response(): void
    {
        $administrator = User::factory()->create(['is_admin' => true]);
        $complaint = Complaint::factory()->create();
        $this->actingAs($administrator);

        $action = app(UpdateComplaintAction::class);
        $inProgress = $action->execute([
            'status' => Complaint::STATUS_IN_PROGRESS,
            'admin_notes' => 'Sudah diverifikasi oleh CS.',
        ], $complaint);

        $this->assertSame(Complaint::STATUS_IN_PROGRESS, $inProgress->status);
        $this->assertNotNull($inProgress->reviewed_at);
        $this->assertSame($administrator->id, $inProgress->handled_by);

        $resolved = $action->execute([
            'status' => Complaint::STATUS_RESOLVED,
            'admin_response' => 'Kendala telah diselesaikan. Silakan mencoba kembali.',
            'admin_notes' => 'Cache domain dibersihkan.',
        ], $inProgress);

        $this->assertSame(Complaint::STATUS_RESOLVED, $resolved->status);
        $this->assertNotNull($resolved->resolved_at);
        $this->assertNotNull($resolved->responded_at);
        $this->assertSame(2, ComplaintStatusHistory::withoutGlobalScopes()->count());

        $lastHistory = ComplaintStatusHistory::withoutGlobalScopes()->latest('id')->firstOrFail();
        $this->assertSame(Complaint::STATUS_IN_PROGRESS, $lastHistory->from_status);
        $this->assertSame(Complaint::STATUS_RESOLVED, $lastHistory->to_status);
        $this->assertSame($administrator->id, $lastHistory->actor_id);
        $this->assertSame('Kendala telah diselesaikan. Silakan mencoba kembali.', $lastHistory->admin_response);
    }

    public function test_invalid_status_transition_is_rejected_without_history(): void
    {
        $administrator = User::factory()->create(['is_admin' => true]);
        $complaint = Complaint::factory()->create();
        $this->actingAs($administrator);

        try {
            app(UpdateComplaintAction::class)->execute([
                'status' => Complaint::STATUS_RESOLVED,
            ], $complaint);
            $this->fail('Expected validation exception was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('status', $exception->errors());
        }

        $this->assertSame(Complaint::STATUS_OPEN, $complaint->fresh()->status);
        $this->assertDatabaseCount('complaint_status_histories', 0);
    }

    public function test_history_is_brand_scoped(): void
    {
        $brand = Brand::factory()->create(['domain' => 'complaint-history-a.test']);
        $otherBrand = Brand::factory()->create(['domain' => 'complaint-history-b.test']);
        $complaint = Complaint::factory()->create(['brand_id' => $brand->id]);
        $otherComplaint = Complaint::factory()->create(['brand_id' => $otherBrand->id]);

        ComplaintStatusHistory::query()->withoutGlobalScopes()->create([
            'complaint_id' => $complaint->id,
            'brand_id' => $brand->id,
            'to_status' => Complaint::STATUS_OPEN,
        ]);
        ComplaintStatusHistory::query()->withoutGlobalScopes()->create([
            'complaint_id' => $otherComplaint->id,
            'brand_id' => $otherBrand->id,
            'to_status' => Complaint::STATUS_OPEN,
        ]);

        $this->get('http://'.$brand->domain.'/')->assertOk();

        $this->assertSame([$complaint->id], ComplaintStatusHistory::query()->pluck('complaint_id')->all());
    }
}
