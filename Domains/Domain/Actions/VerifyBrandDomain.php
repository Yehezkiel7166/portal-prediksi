<?php

declare(strict_types=1);

namespace App\Domains\Domain\Actions;

use App\Domains\Domain\Data\DomainVerificationCheck;
use App\Domains\Domain\Data\DomainVerificationReport;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Domains\Domain\Models\BrandDomainHealthHistory;
use App\Domains\Domain\Support\DnsDomainVerifier;
use App\Domains\Domain\Support\HttpDomainVerifier;
use App\Domains\Domain\Support\SeoDomainVerifier;
use Carbon\CarbonImmutable;
use DomainException;
use Illuminate\Support\Facades\DB;

final class VerifyBrandDomain
{
    public function __construct(
        private readonly DnsDomainVerifier $dnsVerifier,
        private readonly HttpDomainVerifier $httpVerifier,
        private readonly SeoDomainVerifier $seoVerifier,
    ) {}

    public function execute(
        BrandDomain $domain,
    ): DomainVerificationReport {
        if (! $domain->exists || $domain->getKey() === null) {
            throw new DomainException(
                'The domain must exist before it can be verified.',
            );
        }

        if (! $domain->is_active) {
            $report = new DomainVerificationReport(
                domain: $domain,
                status: DomainVerificationStatus::Unknown,
                score: 0,
                checks: [],
                verifiedAt: CarbonImmutable::now(),
            );

            $this->persistReport($report);

            return $report;
        }

        $checks = [
            ...$this->dnsVerifier->verify($domain),
            ...$this->httpVerifier->verify($domain),
            ...$this->seoVerifier->verify($domain),
        ];

        $report = new DomainVerificationReport(
            domain: $domain,
            status: $this->resolveStatus($checks),
            score: $this->calculateScore($checks),
            checks: $checks,
            verifiedAt: CarbonImmutable::now(),
        );

        $this->persistReport($report);

        return $report;
    }

    private function persistReport(
        DomainVerificationReport $report,
    ): void {
        $checks = array_map(
            static fn (
                DomainVerificationCheck $check,
            ): array => $check->toArray(),
            $report->checks,
        );

        DB::transaction(
            function () use (
                $report,
                $checks,
            ): void {
                $report->domain->forceFill([
                    'verification_status' => $report->status,

                    'verification_score' => $report->score,

                    'verification_checks' => $checks,

                    'verified_at' => $report->verifiedAt,
                ]);

                $report->domain->saveQuietly();

                BrandDomainHealthHistory::query()->create([
                    'brand_domain_id' => $report->domain->getKey(),

                    'brand_id' => $report->domain->brand_id,

                    'host' => $report->domain->host,

                    'verification_status' => $report->status,

                    'verification_score' => $report->score,

                    'verification_checks' => $checks,

                    'verified_at' => $report->verifiedAt,
                ]);
            },
        );
    }

    /**
     * @param  list<DomainVerificationCheck>  $checks
     */
    private function resolveStatus(
        array $checks,
    ): DomainVerificationStatus {
        if ($checks === []) {
            return DomainVerificationStatus::Unknown;
        }

        foreach ($checks as $check) {
            if (
                $check->status
                === DomainVerificationStatus::Critical
            ) {
                return DomainVerificationStatus::Critical;
            }
        }

        foreach ($checks as $check) {
            if (
                $check->status
                    === DomainVerificationStatus::Warning
                || $check->status
                    === DomainVerificationStatus::Unknown
            ) {
                return DomainVerificationStatus::Warning;
            }
        }

        return DomainVerificationStatus::Healthy;
    }

    /**
     * @param  list<DomainVerificationCheck>  $checks
     */
    private function calculateScore(
        array $checks,
    ): int {
        if ($checks === []) {
            return 0;
        }

        $weightedScore = 0;
        $totalWeight = 0;

        foreach ($checks as $check) {
            $weight = max(1, $check->weight);

            $weightedScore +=
                $check->score() * $weight;

            $totalWeight += $weight;
        }

        if ($totalWeight === 0) {
            return 0;
        }

        return (int) round(
            $weightedScore / $totalWeight,
        );
    }
}
