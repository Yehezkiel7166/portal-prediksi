<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Domain\Actions\BackfillLegacyBrandDomains;
use Illuminate\Database\Seeder;

class LegacyBrandDomainSeeder extends Seeder
{
    public function run(
        BackfillLegacyBrandDomains $backfill,
    ): void {
        $created = $backfill->execute();

        $this->command?->info(
            "Legacy brand domains created: {$created}"
        );
    }
}
