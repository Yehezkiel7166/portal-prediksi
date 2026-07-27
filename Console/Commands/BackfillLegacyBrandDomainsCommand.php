<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Domain\Actions\BackfillLegacyBrandDomains;
use Illuminate\Console\Command;

class BackfillLegacyBrandDomainsCommand extends Command
{
    protected $signature = 'brand-domains:backfill-legacy';

    protected $description =
        'Create registered brand domain records from legacy brands.domain values';

    public function handle(
        BackfillLegacyBrandDomains $backfill,
    ): int {
        $created = $backfill->execute();

        $this->components->info(
            "Legacy brand domains created: {$created}"
        );

        return self::SUCCESS;
    }
}
