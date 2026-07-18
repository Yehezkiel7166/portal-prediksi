<?php

namespace Tests\Feature\Prediction;

use App\Core\Contracts\Clock;
use App\Domains\Prediction\Actions\UpsertPredictionAction;
use App\Domains\Prediction\Models\Prediction;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionClockTest extends TestCase
{
    use RefreshDatabase;

    public function test_publishing_uses_the_application_clock(): void
    {
        $publicationTime = CarbonImmutable::parse(
            '2026-07-19 14:30:00',
            'Asia/Jakarta',
        );

        $this->app->instance(
            Clock::class,
            new class($publicationTime) implements Clock
            {
                public function __construct(
                    private readonly CarbonImmutable $time,
                ) {}

                public function now(): CarbonImmutable
                {
                    return $this->time;
                }
            },
        );

        $prediction = app(UpsertPredictionAction::class)->execute(null, [
            'market' => 'Hongkong',
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '1111 2222',
            'status' => Prediction::STATUS_PUBLISHED,
            'notes' => null,
        ]);

        $this->assertSame(
            Prediction::STATUS_PUBLISHED,
            $prediction->status,
        );

        $this->assertNotNull($prediction->published_at);

        $this->assertTrue(
            $prediction->published_at->equalTo($publicationTime),
        );
    }
}
