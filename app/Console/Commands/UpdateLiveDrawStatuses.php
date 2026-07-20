<?php

namespace App\Console\Commands;

use App\Domains\LiveDraw\Actions\UpdateLiveDrawStatusAction;
use Illuminate\Console\Command;
use Throwable;

final class UpdateLiveDrawStatuses extends Command
{
    protected $signature = 'live-draw:update-status';

    protected $description =
        'Update Live Draw statuses from schedules and timezones';

    public function handle(
        UpdateLiveDrawStatusAction $action,
    ): int {
        try {
            $updated = $action->execute();
        } catch (Throwable $exception) {
            report($exception);

            $this->error(
                'Live Draw status automation failed: '
                .$exception->getMessage(),
            );

            return self::FAILURE;
        }

        $this->info(
            sprintf(
                'Live Draw status automation completed. Updated: %d.',
                $updated,
            ),
        );

        return self::SUCCESS;
    }
}
