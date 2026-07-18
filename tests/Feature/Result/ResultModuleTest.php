<?php

namespace Tests\Feature\Result;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Actions\UpsertResultAction;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ResultModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_a_result(): void
    {
        $market = Market::factory()->create();

        $result = app(UpsertResultAction::class)->execute(null, [
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => '1234',
            'notes' => ' Result malam ',
        ]);

        $this->assertDatabaseHas('results', [
            'id' => $result->id,
            'market_id' => $market->id,
            'result_date' => '2026-07-19 00:00:00',
            'winning_numbers' => '1234',
            'notes' => 'Result malam',
        ]);
    }

    public function test_action_updates_existing_result(): void
    {
        $result = Result::factory()->create();

        $updated = app(UpsertResultAction::class)->execute(
            $result,
            [
                'market_id' => $result->market_id,
                'result_date' => $result->result_date,
                'winning_numbers' => '9999',
                'notes' => null,
            ],
        );

        $this->assertSame($result->id, $updated->id);
        $this->assertSame('9999', $updated->winning_numbers);
        $this->assertNull($updated->notes);
    }

    public function test_same_market_cannot_have_two_results_on_same_date(): void
    {
        $market = Market::factory()->create();

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertResultAction::class)->execute(null, [
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => '5678',
            'notes' => null,
        ]);
    }

    public function test_same_market_can_have_results_on_different_dates(): void
    {
        $market = Market::factory()->create();

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
        ]);

        $result = app(UpsertResultAction::class)->execute(null, [
            'market_id' => $market->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => '8888',
            'notes' => null,
        ]);

        $this->assertDatabaseHas('results', [
            'id' => $result->id,
        ]);
    }

    public function test_different_markets_can_have_results_on_same_date(): void
    {
        $marketA = Market::factory()->create();
        $marketB = Market::factory()->create();

        Result::factory()->create([
            'market_id' => $marketA->id,
            'result_date' => '2026-07-19',
        ]);

        $result = app(UpsertResultAction::class)->execute(null, [
            'market_id' => $marketB->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => '7777',
            'notes' => null,
        ]);

        $this->assertDatabaseHas('results', [
            'id' => $result->id,
            'market_id' => $marketB->id,
        ]);
    }
}
