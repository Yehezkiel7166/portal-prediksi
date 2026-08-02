<?php

namespace Tests\Feature\Prediction;

use App\Domains\Brand\Models\Brand;
use App\Domains\Market\Models\Market;
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
        $market = Market::factory()->create();

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => ' 1234 5678 ',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => ' Catatan admin ',
        ]);

        $this->assertDatabaseHas('predictions', [
            'id' => $prediction->id,
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19 00:00:00',
            'predicted_numbers' => '1234 5678',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => 'Catatan admin',
            'published_at' => null,
        ]);

        $this->assertTrue($prediction->market->is($market));
    }

    public function test_published_prediction_receives_publication_time(): void
    {
        $market = Market::factory()->create();

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => $market->id,
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
        $market = Market::factory()->create();

        $prediction = Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
        ]);

        $updated = app(UpsertPredictionAction::class)->execute(
            $prediction,
            [
                'market_id' => $market->id,
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
        $market = Market::factory()->create();

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '1234',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
        ]);
    }

    public function test_same_market_can_exist_on_different_dates(): void
    {
        $market = Market::factory()->create();

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
        ]);

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => $market->id,
            'prediction_date' => '2026-07-20',
            'predicted_numbers' => '5678',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
        ]);

        $this->assertDatabaseHas('predictions', [
            'id' => $prediction->id,
            'market_id' => $market->id,
            'prediction_date' => '2026-07-20 00:00:00',
        ]);
    }

    public function test_prediction_requires_an_existing_market(): void
    {
        $this->expectException(ValidationException::class);

        app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => 999999,
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '1234',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
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

    public function test_action_assigns_market_brand_to_prediction(): void
    {
        $brand = Brand::factory()->create();

        $market = Market::factory()->create([
            'brand_id' => $brand->id,
        ]);

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => $market->id,
            'prediction_date' => '2026-07-21',
            'predicted_numbers' => '1234',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
        ]);

        $this->assertSame($brand->id, $prediction->brand_id);
    }

    public function test_changing_prediction_market_updates_its_brand(): void
    {
        $originalMarket = Market::factory()->create();
        $newMarket = Market::factory()->create();

        $prediction = Prediction::factory()->create([
            'market_id' => $originalMarket->id,
            'prediction_date' => '2026-07-21',
        ]);

        $updated = app(UpsertPredictionAction::class)->execute(
            $prediction,
            [
                'market_id' => $newMarket->id,
                'prediction_date' => '2026-07-21',
                'predicted_numbers' => '5678',
                'status' => Prediction::STATUS_DRAFT,
                'notes' => null,
            ],
        );

        $this->assertSame($newMarket->brand_id, $updated->brand_id);
    }
    public function test_action_creates_structured_prediction_fields(): void
    {
        $market = Market::factory()->create();

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market_id' => $market->id,
            'prediction_date' => '2026-08-02',
            'bbfs' => ' 209184 ',
            'colok_bebas' => ' 9-4 ',
            'prediction_2d' => ' 18,91,82 ',
            'prediction_3d' => ' 028,492 ',
            'prediction_4d' => ' 9482,8491 ',
            'kembar' => ' 88,99 ',
            'shio' => ' TIKUS ',
            'status' => Prediction::STATUS_DRAFT,
            'notes' => null,
        ]);

        $this->assertSame('209184', $prediction->bbfs);
        $this->assertSame('9-4', $prediction->colok_bebas);
        $this->assertSame('18,91,82', $prediction->prediction_2d);
        $this->assertSame('028,492', $prediction->prediction_3d);
        $this->assertSame('9482,8491', $prediction->prediction_4d);
        $this->assertSame('88,99', $prediction->kembar);
        $this->assertSame('TIKUS', $prediction->shio);

        $this->assertStringContainsString(
            'BBFS: 209184',
            $prediction->predicted_numbers,
        );
    }
}