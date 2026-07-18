<?php

namespace Tests\Feature\Core\System;

use App\Core\System\Models\SystemStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchedulerHeartbeatTest extends TestCase
{
    use RefreshDatabase;

    public function test_scheduler_heartbeat_records_system_status(): void
    {
        $this->artisan('system:scheduler-heartbeat')
            ->assertSuccessful();

        $status = SystemStatus::query()
            ->where('key', 'scheduler')
            ->first();

        $this->assertNotNull($status);
        $this->assertSame('healthy', $status->status);
        $this->assertNotNull($status->last_success_at);
        $this->assertSame(
            config('app.name'),
            $status->payload['application']
        );
        $this->assertSame(
            config('app.timezone'),
            $status->payload['timezone']
        );
    }

    public function test_scheduler_heartbeat_updates_existing_record(): void
    {
        SystemStatus::query()->create([
            'key' => 'scheduler',
            'status' => 'unknown',
        ]);

        $this->artisan('system:scheduler-heartbeat')
            ->assertSuccessful();

        $this->assertSame(
            1,
            SystemStatus::query()
                ->where('key', 'scheduler')
                ->count()
        );

        $this->assertDatabaseHas('system_statuses', [
            'key' => 'scheduler',
            'status' => 'healthy',
        ]);
    }
}
