<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Data\DomainHealthReport;
use App\Domains\Domain\Enums\DomainHealthStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DomainDnsProbe;
use App\Domains\Domain\Support\DomainHostNormalizer;
use App\Domains\Domain\Support\DomainHttpsProbe;
use Carbon\CarbonImmutable;

final class CheckBrandDomainHealth
{
    public function __construct(
        private readonly DomainHostNormalizer $hostNormalizer,
        private readonly DomainDnsProbe $dnsProbe,
        private readonly DomainHttpsProbe $httpsProbe,
    ) {}

    public function execute(BrandDomain $domain): DomainHealthReport
    {
        $checkedAt = CarbonImmutable::now();
        $host = $this->hostNormalizer->normalize($domain->host);

        if (! $domain->is_active) {
            return new DomainHealthReport(
                host: $host ?? (string) $domain->host,
                status: DomainHealthStatus::Unknown,
                checks: [
                    'registration' => [
                        'passed' => false,
                        'message' => 'Domain health check was skipped because the domain is inactive.',
                    ],
                ],
                checkedAt: $checkedAt,
            );
        }

        if ($host === null) {
            return new DomainHealthReport(
                host: (string) $domain->host,
                status: DomainHealthStatus::Unhealthy,
                checks: [
                    'registration' => [
                        'passed' => false,
                        'message' => 'Domain host is empty or invalid.',
                    ],
                ],
                checkedAt: $checkedAt,
            );
        }

        $dns = $this->dnsProbe->probe($host);

        $checks = [
            'registration' => [
                'passed' => true,
                'message' => 'Domain registration is active.',
            ],
            'dns' => [
                'passed' => $dns->resolved,
                'records' => $dns->records,
                'error' => $dns->error,
            ],
        ];

        if (! $dns->resolved) {
            return new DomainHealthReport(
                host: $host,
                status: DomainHealthStatus::Unhealthy,
                checks: $checks,
                checkedAt: $checkedAt,
            );
        }

        if (! $domain->force_https) {
            $checks['https'] = [
                'passed' => true,
                'skipped' => true,
                'message' => 'HTTPS probing is not mandatory for this domain.',
            ];

            return new DomainHealthReport(
                host: $host,
                status: DomainHealthStatus::Healthy,
                checks: $checks,
                checkedAt: $checkedAt,
            );
        }

        $https = $this->httpsProbe->probe($host);

        $checks['https'] = [
            'passed' => $https->acceptsTraffic(),
            'reachable' => $https->reachable,
            'status_code' => $https->statusCode,
            'error' => $https->error,
        ];

        if (! $https->reachable) {
            return new DomainHealthReport(
                host: $host,
                status: DomainHealthStatus::Unhealthy,
                checks: $checks,
                checkedAt: $checkedAt,
            );
        }

        if (! $https->acceptsTraffic()) {
            return new DomainHealthReport(
                host: $host,
                status: DomainHealthStatus::Degraded,
                checks: $checks,
                checkedAt: $checkedAt,
            );
        }

        return new DomainHealthReport(
            host: $host,
            status: DomainHealthStatus::Healthy,
            checks: $checks,
            checkedAt: $checkedAt,
        );
    }
}
