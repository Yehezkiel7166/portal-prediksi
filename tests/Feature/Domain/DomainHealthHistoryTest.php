<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\VerifyBrandDomain;
use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Models\BrandDomainHealthHistory;
use App\Domains\Domain\Support\DnsDomainVerifier;
use App\Domains\Domain\Support\HttpDomainVerifier;
use App\Domains\Domain\Support\SeoDomainVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

final class DomainHealthHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_creates_health_history(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'history.example.test',
                'is_active' => true,
            ]);

        $report = $this->action(
            dnsChecks: [
                $this->check(
                    key: 'dns_a',
                    label: 'DNS A Record',
                    status: DomainVerificationStatus::Healthy,

                    metadata: [
                        'addresses' => ['192.0.2.10'],
                    ],
                ),
            ],
            httpChecks: [
                $this->check(
                    key: 'https',
                    label: 'HTTPS',
                    status: DomainVerificationStatus::Warning,

                    metadata: [
                        'status_code' => 301,
                        'response_time_ms' => 145,
                    ],
                ),
            ],
            seoChecks: [],
        )->execute($domain);

        $this->assertSame(
            DomainVerificationStatus::Warning,
            $report->status,
        );

        $this->assertDatabaseCount(
            'brand_domain_health_histories',
            1,
        );

        $history = BrandDomainHealthHistory::query()
            ->sole();

        $this->assertTrue(
            $history->domain->is($domain),
        );

        $this->assertTrue(
            $history->brand->is($brand),
        );

        $this->assertSame(
            'history.example.test',
            $history->host,
        );

        $this->assertSame(
            DomainVerificationStatus::Warning,
            $history->verification_status,
        );

        $this->assertCount(
            2,
            $history->verification_checks,
        );

        $this->assertSame(
            301,
            $history
                ->verification_checks[1]['metadata']['status_code'],
        );
    }

    public function test_each_verification_creates_new_history(): void
    {
        $domain = BrandDomain::factory()
            ->create([
                'is_active' => true,
            ]);

        $action = $this->action(
            dnsChecks: [
                $this->check(),
            ],
            httpChecks: [],
            seoChecks: [],
        );

        $action->execute($domain);
        $action->execute($domain);

        $this->assertDatabaseCount(
            'brand_domain_health_histories',
            2,
        );

        $this->assertCount(
            2,
            $domain->healthHistories()->get(),
        );
    }

    public function test_inactive_domain_creates_unknown_history(): void
    {
        $domain = BrandDomain::factory()
            ->create([
                'is_active' => false,
            ]);

        $dns = Mockery::mock(
            DnsDomainVerifier::class,
        );

        $http = Mockery::mock(
            HttpDomainVerifier::class,
        );

        $seo = Mockery::mock(
            SeoDomainVerifier::class,
        );

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

        $history = BrandDomainHealthHistory::query()
            ->sole();

        $this->assertSame(
            DomainVerificationStatus::Unknown,
            $history->verification_status,
        );

        $this->assertSame(
            [],
            $history->verification_checks,
        );
    }

    public function test_history_detects_active_issues(): void
    {
        $domain = BrandDomain::factory()->create();

        $history = BrandDomainHealthHistory::query()
            ->create([
                'brand_domain_id' => $domain->getKey(),

                'brand_id' => $domain->brand_id,

                'host' => $domain->host,

                'verification_status' => DomainVerificationStatus::Warning,

                'verification_score' => 60,

                'verification_checks' => [
                    [
                        'key' => 'dns',
                        'label' => 'DNS',
                        'status' => 'healthy',
                        'message' => 'DNS resolved.',
                        'weight' => 1,
                        'score' => 100,
                        'passed' => true,
                        'metadata' => [],
                    ],
                    [
                        'key' => 'ssl',
                        'label' => 'SSL',
                        'status' => 'warning',
                        'message' => 'Certificate expires soon.',

                        'weight' => 2,
                        'score' => 60,
                        'passed' => false,
                        'metadata' => [
                            'days_remaining' => 12,
                        ],
                    ],
                ],

                'verified_at' => now(),
            ]);

        $this->assertFalse(
            $history->hasCriticalIssue(),
        );

        $this->assertCount(
            1,
            $history->issues(),
        );

        $this->assertSame(
            'ssl',
            $history->issues()[0]['key'],
        );
    }

    public function test_deleting_domain_deletes_history(): void
    {
        $domain = BrandDomain::factory()->create();

        BrandDomainHealthHistory::query()->create([
            'brand_domain_id' => $domain->getKey(),

            'brand_id' => $domain->brand_id,

            'host' => $domain->host,

            'verification_status' => DomainVerificationStatus::Healthy,

            'verification_score' => 100,

            'verification_checks' => [],

            'verified_at' => now(),
        ]);

        $domain->delete();

        $this->assertDatabaseCount(
            'brand_domain_health_histories',
            0,
        );
    }

    public function test_prune_command_supports_dry_run(): void
    {
        $domain = BrandDomain::factory()->create();

        BrandDomainHealthHistory::query()->create([
            'brand_domain_id' => $domain->getKey(),

            'brand_id' => $domain->brand_id,

            'host' => $domain->host,

            'verification_status' => DomainVerificationStatus::Healthy,

            'verification_score' => 100,

            'verification_checks' => [],

            'verified_at' => now()->subDays(120),
        ]);

        $this->artisan(
            'domain:prune-health-history',
            [
                '--days' => 90,
                '--dry-run' => true,
            ],
        )
            ->expectsOutputToContain(
                '1 history record(s) would be deleted.',
            )
            ->assertSuccessful();

        $this->assertDatabaseCount(
            'brand_domain_health_histories',
            1,
        );
    }

    public function test_prune_command_deletes_old_history(): void
    {
        $domain = BrandDomain::factory()->create();

        BrandDomainHealthHistory::query()->create([
            'brand_domain_id' => $domain->getKey(),

            'brand_id' => $domain->brand_id,

            'host' => $domain->host,

            'verification_status' => DomainVerificationStatus::Healthy,

            'verification_score' => 100,

            'verification_checks' => [],

            'verified_at' => now()->subDays(120),
        ]);

        BrandDomainHealthHistory::query()->create([
            'brand_domain_id' => $domain->getKey(),

            'brand_id' => $domain->brand_id,

            'host' => $domain->host,

            'verification_status' => DomainVerificationStatus::Healthy,

            'verification_score' => 100,

            'verification_checks' => [],

            'verified_at' => now()->subDays(10),
        ]);

        $this->artisan(
            'domain:prune-health-history',
            [
                '--days' => 90,
            ],
        )
            ->expectsOutputToContain(
                '1 history record(s) deleted.',
            )
            ->assertSuccessful();

        $this->assertDatabaseCount(
            'brand_domain_health_histories',
            1,
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
        $dns = Mockery::mock(
            DnsDomainVerifier::class,
        );

        $http = Mockery::mock(
            HttpDomainVerifier::class,
        );

        $seo = Mockery::mock(
            SeoDomainVerifier::class,
        );

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

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function check(
        string $key = 'check',
        string $label = 'Check',
        DomainVerificationStatus $status =
            DomainVerificationStatus::Healthy,

        string $message = 'Check completed.',
        int $weight = 1,
        array $metadata = [],
    ): DomainVerificationCheck {
        return new DomainVerificationCheck(
            key: $key,
            label: $label,
            status: $status,
            message: $message,
            weight: $weight,
            metadata: $metadata,
        );
    }
}
