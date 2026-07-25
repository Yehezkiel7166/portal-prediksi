<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\DomainDnsProbeResult;
use Throwable;

class DomainDnsProbe
{
    public function probe(string $host): DomainDnsProbeResult
    {
        try {
            $records = @dns_get_record(
                $host,
                DNS_A | DNS_AAAA | DNS_CNAME,
            );

            if ($records === false || $records === []) {
                return new DomainDnsProbeResult(
                    resolved: false,
                    records: [],
                    error: 'No DNS records were found.',
                );
            }

            $resolvedRecords = [];

            foreach ($records as $record) {
                $value = $record['ip']
                    ?? $record['ipv6']
                    ?? $record['target']
                    ?? null;

                if (! is_string($value) || trim($value) === '') {
                    continue;
                }

                $resolvedRecords[] = strtolower(
                    rtrim(trim($value), '.'),
                );
            }

            $resolvedRecords = array_values(
                array_unique($resolvedRecords),
            );

            if ($resolvedRecords === []) {
                return new DomainDnsProbeResult(
                    resolved: false,
                    records: [],
                    error: 'DNS records did not contain a usable target.',
                );
            }

            return new DomainDnsProbeResult(
                resolved: true,
                records: $resolvedRecords,
            );
        } catch (Throwable $exception) {
            return new DomainDnsProbeResult(
                resolved: false,
                records: [],
                error: $exception->getMessage(),
            );
        }
    }
}
