<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Models\ShioNumber;
use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShioFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_shio_period_can_have_numbers(): void
    {
        $period = ShioPeriod::factory()->create();

        ShioNumber::factory()->create([
            'shio_period_id' => $period->id,
        ]);

        $this->assertCount(1, $period->fresh()->shios);
        $this->assertSame('KAMBING', $period->fresh()->shios->first()->name);
    }
}
