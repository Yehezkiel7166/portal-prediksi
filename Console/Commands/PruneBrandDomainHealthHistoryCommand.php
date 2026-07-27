<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Domain\Models\BrandDomainHealthHistory;
use Illuminate\Console\Command;

final class PruneBrandDomainHealthHistoryCommand extends Command
{
    protected $signature = 'domain:prune-health-history
        {--days=90 : Retention period in days}
        {--dry-run : Display total records without deleting them}';

    protected $description =
        'Prune old brand-domain health verification history';

    public function handle(): int
    {
        $days = max(
            1,
            (int) $this->option('days'),
        );

        $cutoff = now()->subDays($days);

        $query = BrandDomainHealthHistory::query()
            ->where('verified_at', '<', $cutoff);

        $total = (clone $query)->count();

        if ($this->option('dry-run')) {
            $this->components->info(
                sprintf(
                    '%d history record(s) would be deleted.',
                    $total,
                ),
            );

            return self::SUCCESS;
        }

        $deleted = $query->delete();

        $this->components->info(
            sprintf(
                '%d history record(s) deleted.',
                $deleted,
            ),
        );

        return self::SUCCESS;
    }
}
