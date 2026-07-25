<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackfillLegacyBrandDomains
{
    public function execute(): int
    {
        if (
            ! Schema::hasTable('brands')
            || ! Schema::hasTable('brand_domains')
        ) {
            return 0;
        }

        $created = 0;

        Brand::query()
            ->withoutGlobalScopes()
            ->whereNotNull('domain')
            ->where('domain', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($brands) use (&$created): void {
                foreach ($brands as $brand) {
                    $host = $this->normalizeHost((string) $brand->domain);

                    if ($host === '') {
                        continue;
                    }

                    $exists = BrandDomain::query()
                        ->whereRaw('LOWER(host) = ?', [$host])
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::transaction(function () use ($brand, $host, &$created): void {
                        BrandDomain::query()->create([
                            'brand_id' => $brand->getKey(),
                            'host' => $host,
                            'type' => DomainType::Frontend,
                            'is_primary' => true,
                            'is_active' => true,
                            'force_https' => true,
                            'sort_order' => 0,
                            'settings' => [
                                'source' => 'legacy_brand_domain',
                            ],
                        ]);

                        $created++;
                    });
                }
            });

        return $created;
    }

    private function normalizeHost(string $host): string
    {
        $host = strtolower(trim($host));

        if ($host === '') {
            return '';
        }

        if (str_contains($host, '://')) {
            $parsedHost = parse_url($host, PHP_URL_HOST);

            if (is_string($parsedHost)) {
                $host = $parsedHost;
            }
        }

        $host = preg_replace('/:\d+$/', '', $host) ?? $host;

        return trim($host, " \t\n\r\0\x0B.");
    }
}
