<?php

namespace Tests\Feature\Prediction;

use App\Domains\Prediction\Actions\UpsertPredictionAction;
use App\Domains\Prediction\Models\Prediction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PredictionModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_a_normalized_draft_prediction(): void
    {
        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market' => ' singapore ',
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => ' 1234 5678 ',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => ' Catatan admin ',
        ]);

        $this->assertDatabaseHas('predictions', [
            'id' => $prediction->id,
            'market' => 'SINGAPORE',
            'prediction_date' => '2026-07-19 00:00:00',
            'predicted_numbers' => '1234 5678',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => 'Catatan admin',
            'published_at' => null,
        ]);
    }

    public function test_published_prediction_receives_publication_time(): void
    {
        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market' => 'Hongkong',
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '1111 2222',
            'status' => Prediction::STATUS_PUBLISHED,
            'notes' => null,
        ]);

        $this->assertSame(
            Prediction::STATUS_PUBLISHED,
            $prediction->status
        );

        $this->assertNotNull($prediction->published_at);
    }

    public function test_action_updates_an_existing_prediction(): void
    {
        $prediction = Prediction::factory()->create([
            'market' => 'SYDNEY',
            'prediction_date' => '2026-07-19',
        ]);

        $updated = app(UpsertPredictionAction::class)->execute(
            $prediction,
            [
                'market' => 'Sydney',
                'prediction_date' => '2026-07-19',
                'predicted_numbers' => '9999 8888',
                'status' => Prediction::STATUS_ARCHIVED,
                'notes' => null,
            ],
        );

        $this->assertSame($prediction->id, $updated->id);
        $this->assertSame('9999 8888', $updated->predicted_numbers);
        $this->assertSame(Prediction::STATUS_ARCHIVED, $updated->status);
        $this->assertNull($updated->published_at);
    }

    public function test_same_market_cannot_exist_twice_on_same_date(): void
    {
        Prediction::factory()->create([
            'market' => 'SINGAPORE',
            'prediction_date' => '2026-07-19',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertPredictionAction::class)->execute(null, [
            'market' => 'singapore',
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '1234',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
        ]);
    }

    public function test_same_market_can_exist_on_different_dates(): void
    {
        Prediction::factory()->create([
            'market' => 'SINGAPORE',
            'prediction_date' => '2026-07-19',
        ]);

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market' => 'singapore',
            'prediction_date' => '2026-07-20',
            'predicted_numbers' => '5678',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
        ]);

        $this->assertDatabaseHas('predictions', [
            'id' => $prediction->id,
            'market' => 'SINGAPORE',
            'prediction_date' => '2026-07-20 00:00:00',
        ]);
    }

    public function test_admin_can_open_prediction_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/predictions')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_prediction_resource(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/predictions')
            ->assertForbidden();
    }
}
