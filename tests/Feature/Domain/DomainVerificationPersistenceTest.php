<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Domain\Actions\VerifyBrandDomain;
use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DnsDomainVerifier;
use App\Domains\Domain\Support\HttpDomainVerifier;
use App\Domains\Domain\Support\SeoDomainVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

final class DomainVerificationPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_result_is_persisted(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => true,
        ]);

        $this->mockHealthyVerifiers();

        $report = app(VerifyBrandDomain::class)->execute($domain);

        $domain->refresh();

        $this->assertSame(
            DomainVerificationStatus::Healthy,
            $domain->verification_status,
        );

        $this->assertSame(100, $domain->verification_score);

        $this->assertNotNull($domain->verified_at);

        $this->assertIsArray($domain->verification_checks);

        $this->assertCount(3, $domain->verification_checks);

        $this->assertSame(
            DomainVerificationStatus::Healthy,
            $report->status,
        );
    }

    public function test_new_verification_replaces_previous_result(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => true,
            'verification_status' => DomainVerificationStatus::Critical,
            'verification_score' => 0,
            'verification_checks' => [
                [
                    'key' => 'old',
                    'status' => 'critical',
                ],
            ],
            'verified_at' => now()->subDay(),
        ]);

        $oldVerifiedAt = $domain->verified_at;

        $this->mockHealthyVerifiers();

        app(VerifyBrandDomain::class)->execute($domain);

        $domain->refresh();

        $this->assertSame(
            DomainVerificationStatus::Healthy,
            $domain->verification_status,
        );

        $this->assertSame(100, $domain->verification_score);

        $this->assertCount(3, $domain->verification_checks);

        $this->assertTrue(
            $domain->verified_at->greaterThan($oldVerifiedAt),
        );
    }

    public function test_inactive_domain_persists_unknown_result_without_running_verifiers(): void
    {
        $domain = BrandDomain::factory()->create([
            'is_active' => false,
        ]);

        $this->mock(
            DnsDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldNotReceive('verify');
            },
        );

        $this->mock(
            HttpDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldNotReceive('verify');
            },
        );

        $this->mock(
            SeoDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldNotReceive('verify');
            },
        );

        app(VerifyBrandDomain::class)->execute($domain);

        $domain->refresh();

        $this->assertSame(
            DomainVerificationStatus::Unknown,
            $domain->verification_status,
        );

        $this->assertSame(0, $domain->verification_score);
        $this->assertSame([], $domain->verification_checks);
        $this->assertNotNull($domain->verified_at);
    }

    private function mockHealthyVerifiers(): void
    {
        $this->mock(
            DnsDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldReceive('verify')
                    ->once()
                    ->andReturn([
                        new DomainVerificationCheck(
                            key: 'dns',
                            label: 'DNS',
                            status: DomainVerificationStatus::Healthy,
                            message: 'DNS verification passed.',
                        ),
                    ]);
            },
        );

        $this->mock(
            HttpDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldReceive('verify')
                    ->once()
                    ->andReturn([
                        new DomainVerificationCheck(
                            key: 'http',
                            label: 'HTTP',
                            status: DomainVerificationStatus::Healthy,
                            message: 'HTTP verification passed.',
                        ),
                    ]);
            },
        );

        $this->mock(
            SeoDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldReceive('verify')
                    ->once()
                    ->andReturn([
                        new DomainVerificationCheck(
                            key: 'seo',
                            label: 'SEO',
                            status: DomainVerificationStatus::Healthy,
                            message: 'SEO verification passed.',
                        ),
                    ]);
            },
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
