<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Domain\Actions\CheckBrandDomainHealth;
use App\Domains\Domain\Data\DomainDnsProbeResult;
use App\Domains\Domain\Data\DomainHttpsProbeResult;
use App\Domains\Domain\Enums\DomainHealthStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DomainDnsProbe;
use App\Domains\Domain\Support\DomainHostNormalizer;
use App\Domains\Domain\Support\DomainHttpsProbe;
use Illuminate\Http\Client\Factory;
use Tests\TestCase;

class DomainHealthEngineTest extends TestCase
{
    public function test_health_status_exposes_labels_and_operational_state(): void
    {
        $this->assertSame('Healthy', DomainHealthStatus::Healthy->label());
        $this->assertSame('Degraded', DomainHealthStatus::Degraded->label());
        $this->assertSame('Unhealthy', DomainHealthStatus::Unhealthy->label());
        $this->assertSame('Unknown', DomainHealthStatus::Unknown->label());

        $this->assertTrue(DomainHealthStatus::Healthy->isOperational());
        $this->assertTrue(DomainHealthStatus::Degraded->isOperational());
        $this->assertFalse(DomainHealthStatus::Unhealthy->isOperational());
        $this->assertFalse(DomainHealthStatus::Unknown->isOperational());
    }

    public function test_inactive_domain_returns_unknown_without_running_probes(): void
    {
        $domain = new BrandDomain([
            'host' => 'inactive.example.com',
            'is_active' => false,
            'force_https' => true,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.10'],
            ),
            https: new DomainHttpsProbeResult(
                reachable: true,
                statusCode: 200,
            ),
        )->execute($domain);

        $this->assertSame(DomainHealthStatus::Unknown, $report->status);
        $this->assertFalse($report->isOperational());
        $this->assertFalse($report->checks['registration']['passed']);
    }

    public function test_invalid_host_returns_unhealthy(): void
    {
        $domain = new BrandDomain([
            'host' => '',
            'is_active' => true,
            'force_https' => true,
        ]);

        $report = $this->action()->execute($domain);

        $this->assertSame(DomainHealthStatus::Unhealthy, $report->status);
        $this->assertFalse($report->checks['registration']['passed']);
    }

    public function test_unresolved_dns_returns_unhealthy(): void
    {
        $domain = new BrandDomain([
            'host' => 'missing.example.com',
            'is_active' => true,
            'force_https' => true,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: false,
                records: [],
                error: 'No DNS records were found.',
            ),
        )->execute($domain);

        $this->assertSame(DomainHealthStatus::Unhealthy, $report->status);
        $this->assertFalse($report->checks['dns']['passed']);
        $this->assertSame(
            'No DNS records were found.',
            $report->checks['dns']['error'],
        );
    }

    public function test_domain_without_required_https_is_healthy_after_dns_resolution(): void
    {
        $domain = new BrandDomain([
            'host' => 'http.example.com',
            'is_active' => true,
            'force_https' => false,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.20'],
            ),
        )->execute($domain);

        $this->assertSame(DomainHealthStatus::Healthy, $report->status);
        $this->assertTrue($report->isOperational());
        $this->assertTrue($report->checks['https']['skipped']);
    }

    public function test_resolved_domain_with_working_https_is_healthy(): void
    {
        $domain = new BrandDomain([
            'host' => 'https://Healthy.Example.com/path',
            'is_active' => true,
            'force_https' => true,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.30'],
            ),
            https: new DomainHttpsProbeResult(
                reachable: true,
                statusCode: 200,
            ),
        )->execute($domain);

        $this->assertSame('healthy.example.com', $report->host);
        $this->assertSame(DomainHealthStatus::Healthy, $report->status);
        $this->assertTrue($report->checks['https']['passed']);
        $this->assertSame(200, $report->checks['https']['status_code']);
    }

    public function test_https_redirect_is_accepted_as_healthy(): void
    {
        $domain = new BrandDomain([
            'host' => 'redirect.example.com',
            'is_active' => true,
            'force_https' => true,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.40'],
            ),
            https: new DomainHttpsProbeResult(
                reachable: true,
                statusCode: 308,
            ),
        )->execute($domain);

        $this->assertSame(DomainHealthStatus::Healthy, $report->status);
        $this->assertTrue($report->checks['https']['passed']);
    }

    public function test_reachable_https_with_error_response_is_degraded(): void
    {
        $domain = new BrandDomain([
            'host' => 'degraded.example.com',
            'is_active' => true,
            'force_https' => true,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.50'],
            ),
            https: new DomainHttpsProbeResult(
                reachable: true,
                statusCode: 503,
            ),
        )->execute($domain);

        $this->assertSame(DomainHealthStatus::Degraded, $report->status);
        $this->assertTrue($report->isOperational());
        $this->assertFalse($report->checks['https']['passed']);
        $this->assertSame(503, $report->checks['https']['status_code']);
    }

    public function test_unreachable_https_is_unhealthy(): void
    {
        $domain = new BrandDomain([
            'host' => 'offline.example.com',
            'is_active' => true,
            'force_https' => true,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.60'],
            ),
            https: new DomainHttpsProbeResult(
                reachable: false,
                error: 'Connection failed.',
            ),
        )->execute($domain);

        $this->assertSame(DomainHealthStatus::Unhealthy, $report->status);
        $this->assertFalse($report->isOperational());
        $this->assertSame(
            'Connection failed.',
            $report->checks['https']['error'],
        );
    }

    public function test_report_can_be_converted_to_array(): void
    {
        $domain = new BrandDomain([
            'host' => 'array.example.com',
            'is_active' => true,
            'force_https' => false,
        ]);

        $report = $this->action(
            dns: new DomainDnsProbeResult(
                resolved: true,
                records: ['192.0.2.70'],
            ),
        )->execute($domain);

        $data = $report->toArray();

        $this->assertSame('array.example.com', $data['host']);
        $this->assertSame('healthy', $data['status']);
        $this->assertTrue($data['operational']);
        $this->assertArrayHasKey('checked_at', $data);
        $this->assertArrayHasKey('dns', $data['checks']);
    }

    private function action(
        ?DomainDnsProbeResult $dns = null,
        ?DomainHttpsProbeResult $https = null,
    ): CheckBrandDomainHealth {
        $dns ??= new DomainDnsProbeResult(
            resolved: false,
            records: [],
            error: 'DNS probe should not have been required.',
        );

        $https ??= new DomainHttpsProbeResult(
            reachable: false,
            error: 'HTTPS probe should not have been required.',
        );

        $dnsProbe = new class($dns) extends DomainDnsProbe
        {
            public function __construct(
                private readonly DomainDnsProbeResult $result,
            ) {}

            public function probe(string $host): DomainDnsProbeResult
            {
                return $this->result;
            }
        };

        $httpsProbe = new class(app(Factory::class), $https) extends DomainHttpsProbe
        {
            public function __construct(
                Factory $http,
                private readonly DomainHttpsProbeResult $result,
            ) {
                parent::__construct($http);
            }

            public function probe(string $host): DomainHttpsProbeResult
            {
                return $this->result;
            }
        };

        return new CheckBrandDomainHealth(
            hostNormalizer: app(DomainHostNormalizer::class),
            dnsProbe: $dnsProbe,
            httpsProbe: $httpsProbe,
        );
    }
}
