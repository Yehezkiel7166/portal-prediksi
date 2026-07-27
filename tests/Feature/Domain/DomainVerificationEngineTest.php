<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\VerifyBrandDomain;
use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DnsDomainVerifier;
use App\Domains\Domain\Support\HttpDomainVerifier;
use App\Domains\Domain\Support\SeoDomainVerifier;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DomainVerificationEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_exposes_labels_and_operational_state(): void
    {
        $this->assertSame(
            'Healthy',
            DomainVerificationStatus::Healthy->label(),
        );

        $this->assertTrue(
            DomainVerificationStatus::Healthy->isOperational(),
        );

        $this->assertTrue(
            DomainVerificationStatus::Warning->isOperational(),
        );

        $this->assertFalse(
            DomainVerificationStatus::Critical->isOperational(),
        );

        $this->assertFalse(
            DomainVerificationStatus::Unknown->isOperational(),
        );
    }

    public function test_verification_check_calculates_score(): void
    {
        $healthy = $this->check(
            status: DomainVerificationStatus::Healthy,
        );

        $warning = $this->check(
            status: DomainVerificationStatus::Warning,
        );

        $critical = $this->check(
            status: DomainVerificationStatus::Critical,
        );

        $this->assertSame(100, $healthy->score());
        $this->assertSame(60, $warning->score());
        $this->assertSame(0, $critical->score());
        $this->assertTrue($healthy->passed());
        $this->assertFalse($warning->passed());
    }

    public function test_healthy_checks_create_healthy_report(): void
    {
        $domain = $this->domain();

        $action = $this->action(
            dnsChecks: [
                $this->check(
                    key: 'dns',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            httpChecks: [
                $this->check(
                    key: 'http',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            seoChecks: [
                $this->check(
                    key: 'seo',
                    status: DomainVerificationStatus::Healthy,
                    weight: 2,
                ),
            ],
        );

        $report = $action->execute($domain);

        $this->assertSame(
            DomainVerificationStatus::Healthy,
            $report->status,
        );

        $this->assertSame(100, $report->score);
        $this->assertFalse($report->hasCriticalFailure());
        $this->assertCount(3, $report->checks);
    }

    public function test_warning_check_creates_warning_report(): void
    {
        $domain = $this->domain();

        $action = $this->action(
            dnsChecks: [
                $this->check(
                    key: 'dns',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            httpChecks: [
                $this->check(
                    key: 'http',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            seoChecks: [
                $this->check(
                    key: 'canonical',
                    status: DomainVerificationStatus::Warning,
                    weight: 2,
                ),
            ],
        );

        $report = $action->execute($domain);

        $this->assertSame(
            DomainVerificationStatus::Warning,
            $report->status,
        );

        $this->assertSame(90, $report->score);
        $this->assertFalse($report->hasCriticalFailure());
    }

    public function test_unknown_check_creates_warning_report(): void
    {
        $domain = $this->domain();

        $action = $this->action(
            dnsChecks: [
                $this->check(
                    key: 'dns',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            httpChecks: [
                $this->check(
                    key: 'http',
                    status: DomainVerificationStatus::Unknown,
                    weight: 1,
                ),
            ],
            seoChecks: [],
        );

        $report = $action->execute($domain);

        $this->assertSame(
            DomainVerificationStatus::Warning,
            $report->status,
        );

        $this->assertSame(75, $report->score);
    }

    public function test_critical_check_creates_critical_report(): void
    {
        $domain = $this->domain();

        $action = $this->action(
            dnsChecks: [
                $this->check(
                    key: 'dns',
                    status: DomainVerificationStatus::Critical,
                    weight: 3,
                ),
            ],
            httpChecks: [
                $this->check(
                    key: 'http',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            seoChecks: [],
        );

        $report = $action->execute($domain);

        $this->assertSame(
            DomainVerificationStatus::Critical,
            $report->status,
        );

        $this->assertSame(50, $report->score);
        $this->assertTrue($report->hasCriticalFailure());
    }

    public function test_inactive_domain_returns_unknown_without_verifiers(): void
    {
        $domain = $this->domain([
            'is_active' => false,
        ]);

        $dns = Mockery::mock(DnsDomainVerifier::class);
        $http = Mockery::mock(HttpDomainVerifier::class);
        $seo = Mockery::mock(SeoDomainVerifier::class);

        $dns->shouldNotReceive('verify');
        $http->shouldNotReceive('verify');
        $seo->shouldNotReceive('verify');

        $report = (new VerifyBrandDomain(
            dnsVerifier: $dns,
            httpVerifier: $http,
            seoVerifier: $seo,
        ))->execute($domain);

        $this->assertSame(
            DomainVerificationStatus::Unknown,
            $report->status,
        );

        $this->assertSame(0, $report->score);
        $this->assertSame([], $report->checks);
    }

    public function test_unsaved_domain_is_rejected(): void
    {
        $domain = new BrandDomain([
            'host' => 'unsaved.example.com',
            'type' => DomainType::Frontend,
            'is_active' => true,
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'The domain must exist before it can be verified.',
        );

        $this->action([], [], [])->execute($domain);
    }

    public function test_report_can_be_converted_to_array(): void
    {
        $domain = $this->domain([
            'host' => 'verification.example.com',
        ]);

        $action = $this->action(
            dnsChecks: [
                $this->check(
                    key: 'dns_resolution',
                    label: 'DNS Resolution',
                    status: DomainVerificationStatus::Healthy,
                    weight: 3,
                ),
            ],
            httpChecks: [],
            seoChecks: [],
        );

        $report = $action->execute($domain);
        $data = $report->toArray();

        $this->assertSame($domain->id, $data['domain_id']);
        $this->assertSame(
            'verification.example.com',
            $data['host'],
        );

        $this->assertSame('healthy', $data['status']);
        $this->assertSame('Healthy', $data['status_label']);
        $this->assertTrue($data['operational']);
        $this->assertSame(100, $data['score']);
        $this->assertCount(1, $data['checks']);
        $this->assertSame(
            'dns_resolution',
            $data['checks'][0]['key'],
        );
    }

    public function test_check_can_be_converted_to_array(): void
    {
        $check = new DomainVerificationCheck(
            key: 'canonical',
            label: 'Canonical',
            status: DomainVerificationStatus::Warning,
            message: 'Canonical points to another host.',
            weight: 2,
            metadata: [
                'url' => 'https://other.example.com',
            ],
        );

        $data = $check->toArray();

        $this->assertSame('canonical', $data['key']);
        $this->assertSame('warning', $data['status']);
        $this->assertSame(60, $data['score']);
        $this->assertFalse($data['passed']);
        $this->assertSame(2, $data['weight']);
        $this->assertSame(
            'https://other.example.com',
            $data['metadata']['url'],
        );
    }

    /**
     * @param  list<DomainVerificationCheck>  $dnsChecks
     * @param  list<DomainVerificationCheck>  $httpChecks
     * @param  list<DomainVerificationCheck>  $seoChecks
     */
    private function action(
        array $dnsChecks,
        array $httpChecks,
        array $seoChecks,
    ): VerifyBrandDomain {
        $dns = Mockery::mock(DnsDomainVerifier::class);
        $http = Mockery::mock(HttpDomainVerifier::class);
        $seo = Mockery::mock(SeoDomainVerifier::class);

        $dns->shouldReceive('verify')
            ->zeroOrMoreTimes()
            ->andReturn($dnsChecks);

        $http->shouldReceive('verify')
            ->zeroOrMoreTimes()
            ->andReturn($httpChecks);

        $seo->shouldReceive('verify')
            ->zeroOrMoreTimes()
            ->andReturn($seoChecks);

        return new VerifyBrandDomain(
            dnsVerifier: $dns,
            httpVerifier: $http,
            seoVerifier: $seo,
        );
    }

    private function check(
        string $key = 'check',
        string $label = 'Check',
        DomainVerificationStatus $status = DomainVerificationStatus::Healthy,
        int $weight = 1,
    ): DomainVerificationCheck {
        return new DomainVerificationCheck(
            key: $key,
            label: $label,
            status: $status,
            message: 'Verification result.',
            weight: $weight,
        );
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function domain(
        array $attributes = [],
    ): BrandDomain {
        $brand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        return BrandDomain::factory()->create(array_merge([
            'brand_id' => $brand->id,
            'host' => fake()->unique()->domainName(),
            'type' => DomainType::Frontend,
            'is_active' => true,
            'is_primary' => false,
            'force_https' => true,
        ], $attributes));
    }
}
