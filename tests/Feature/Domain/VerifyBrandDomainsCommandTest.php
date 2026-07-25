<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Support\DnsDomainVerifier;
use App\Domains\Domain\Support\HttpDomainVerifier;
use App\Domains\Domain\Support\SeoDomainVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

final class VerifyBrandDomainsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_verifies_active_domains(): void
    {
        $activeDomain = BrandDomain::factory()->create([
            'is_active' => true,
        ]);

        $inactiveDomain = BrandDomain::factory()->create([
            'is_active' => false,
        ]);

        $this->mockHealthyVerifiers();

        $this->artisan('domain:verify')
            ->assertSuccessful();

        $activeDomain->refresh();
        $inactiveDomain->refresh();

        $this->assertSame(
            DomainVerificationStatus::Healthy,
            $activeDomain->verification_status,
        );

        $this->assertSame(100, $activeDomain->verification_score);
        $this->assertNotNull($activeDomain->verified_at);

        $this->assertNull($inactiveDomain->verification_status);
        $this->assertNull($inactiveDomain->verification_score);
        $this->assertNull($inactiveDomain->verified_at);
    }

    public function test_command_can_verify_selected_domain(): void
    {
        $selectedDomain = BrandDomain::factory()->create([
            'is_active' => true,
        ]);

        $otherDomain = BrandDomain::factory()->create([
            'is_active' => true,
        ]);

        $this->mockHealthyVerifiers();

        $this->artisan('domain:verify', [
            '--domain-id' => [$selectedDomain->getKey()],
        ])->assertSuccessful();

        $selectedDomain->refresh();
        $otherDomain->refresh();

        $this->assertSame(
            DomainVerificationStatus::Healthy,
            $selectedDomain->verification_status,
        );

        $this->assertNull($otherDomain->verification_status);
    }

    public function test_domain_verification_command_is_registered_in_scheduler(): void
    {
        $this->artisan('schedule:list')
            ->expectsOutputToContain('domain:verify')
            ->assertSuccessful();
    }

    private function mockHealthyVerifiers(): void
    {
        $this->mock(
            DnsDomainVerifier::class,
            static function ($mock): void {
                $mock->shouldReceive('verify')
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
