<?php

namespace App\Console\Commands;

use App\Domains\Brand\Models\Brand;
use App\Domains\DreamBook\Models\DreamBookEntry;
use Illuminate\Console\Command;

class ImportDreamBookConfig extends Command
{
    protected $signature = 'dream-book:import-config';

    protected $description = 'Import configured dream book entries';

    public function handle(): int
    {
        $brand = Brand::query()
            ->where('is_primary', true)
            ->where('is_active', true)
            ->firstOrFail();

        foreach (config('dream-book.entries', []) as $index => $entry) {
            DreamBookEntry::query()->withoutGlobalScopes()->updateOrCreate(
                [
                    'brand_id' => $brand->getKey(),
                    'number' => (string) $entry['number'],
                ],
                [
                    'slug' => $entry['slug'],
                    'title' => $entry['title'],
                    'keywords' => $entry['keywords'] ?? [],
                    'interpretation' => $entry['interpretation'],
                    'is_active' => true,
                    'sort_order' => $index,
                ],
            );
        }

        $this->info('Dream book entries imported: '.count(config('dream-book.entries', [])));

        return self::SUCCESS;
    }
}
