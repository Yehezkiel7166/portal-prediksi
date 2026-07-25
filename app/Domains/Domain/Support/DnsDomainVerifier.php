<?php

declare(strict_types=1);

namespace App\Domains\Domain\Support;

use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use Throwable;

class DnsDomainVerifier
{
    /**
     * @return list<DomainVerificationCheck>
     */
    public function verify(BrandDomain $domain): array
    {
        $host = strtolower(trim((string) $domain->host));

        if ($host === '' || filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            return [
                new DomainVerificationCheck(
                    key: 'dns_host',
                    label: 'DNS Host',
                    status: DomainVerificationStatus::Critical,
                    message: 'Domain host is invalid.',
                    weight: 3,
                ),
            ];
        }

        try {
            $records = @dns_get_record(
                $host,
                DNS_A | DNS_AAAA | DNS_CNAME | DNS_NS | DNS_TXT,
            );

            if (! is_array($records) || $records === []) {
                return [
                    new DomainVerificationCheck(
                        key: 'dns_resolution',
                        label: 'DNS Resolution',
                        status: DomainVerificationStatus::Critical,
                        message: 'No DNS records could be resolved.',
                        weight: 3,
                    ),
                ];
            }

            $types = [];
            $addresses = [];

            foreach ($records as $record) {
                $type = strtoupper((string) ($record['type'] ?? ''));

                if ($type !== '') {
                    $types[] = $type;
                }

                foreach (['ip', 'ipv6', 'target'] as $field) {
                    $value = $record[$field] ?? null;

                    if (is_string($value) && $value !== '') {
                        $addresses[] = $value;
                    }
                }
            }

            return [
                new DomainVerificationCheck(
                    key: 'dns_resolution',
                    label: 'DNS Resolution',
                    status: DomainVerificationStatus::Healthy,
                    message: 'DNS records resolved successfully.',
                    weight: 3,
                    metadata: [
                        'record_count' => count($records),
                        'record_types' => array_values(array_unique($types)),
                        'addresses' => array_values(array_unique($addresses)),
                    ],
                ),
            ];
        } catch (Throwable $exception) {
            return [
                new DomainVerificationCheck(
                    key: 'dns_resolution',
                    label: 'DNS Resolution',
                    status: DomainVerificationStatus::Critical,
                    message: 'DNS verification failed.',
                    weight: 3,
                    metadata: [
                        'error' => $exception->getMessage(),
                    ],
                ),
            ];
        }
    }
}
