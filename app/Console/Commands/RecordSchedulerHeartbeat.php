<?php

namespace App\Console\Commands;

use App\Core\System\Models\SystemStatus;
use Illuminate\Console\Command;
use Throwable;

class RecordSchedulerHeartbeat extends Command
{
    protected $signature = 'system:scheduler-heartbeat';

    protected $description = 'Record the latest successful Laravel scheduler execution';

    public function handle(): int
    {
        try {
            $executedAt = now();

            SystemStatus::query()->updateOrCreate(
                ['key' => 'scheduler'],
                [
                    'status' => 'healthy',
                    'payload' => [
                        'application' => config('app.name'),
                        'environment' => app()->environment(),
                        'timezone' => config('app.timezone'),
                        'php_version' => PHP_VERSION,
                        'laravel_version' => app()->version(),
                        'executed_at' => $executedAt->toIso8601String(),
                    ],
                    'last_success_at' => $executedAt,
                ],
            );

            $this->info(
                'Scheduler heartbeat recorded at '.$executedAt->toIso8601String()
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);

            $this->error(
                'Scheduler heartbeat failed: '.$exception->getMessage()
            );

            return self::FAILURE;
        }
    }
}
