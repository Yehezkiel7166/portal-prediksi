<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Domain\Actions\VerifyBrandDomain;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Console\Command;
use Throwable;

final class VerifyBrandDomainsCommand extends Command
{
    protected $signature = 'domain:verify
        {--domain-id=* : Verify only the selected brand domain IDs}';

    protected $description =
        'Verify active brand domains and persist their verification results';

    public function handle(
        VerifyBrandDomain $verifyBrandDomain,
    ): int {
        $domainIds = collect($this->option('domain-id'))
            ->filter(
                static fn (mixed $id): bool => is_numeric($id),
            )
            ->map(
                static fn (mixed $id): int => (int) $id,
            )
            ->filter(
                static fn (int $id): bool => $id > 0,
            )
            ->unique()
            ->values();

        $query = BrandDomain::query()
            ->where('is_active', true)
            ->orderBy('id');

        if ($domainIds->isNotEmpty()) {
            $query->whereKey($domainIds->all());
        }

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->components->info(
                'No active brand domains were found for verification.',
            );

            return self::SUCCESS;
        }

        $summary = [
            DomainVerificationStatus::Healthy->value => 0,
            DomainVerificationStatus::Warning->value => 0,
            DomainVerificationStatus::Critical->value => 0,
            DomainVerificationStatus::Unknown->value => 0,
            'failed' => 0,
        ];

        $this->components->info(
            sprintf('Verifying %d active brand domain(s).', $total),
        );

        $query->eachById(
            function (BrandDomain $domain) use (
                $verifyBrandDomain,
                &$summary,
            ): void {
                try {
                    $report = $verifyBrandDomain->execute($domain);

                    $summary[$report->status->value]++;

                    $this->line(sprintf(
                        '[%s] %s — score %d',
                        strtoupper($report->status->value),
                        $domain->host,
                        $report->score,
                    ));
                } catch (Throwable $exception) {
                    $summary['failed']++;

                    report($exception);

                    $this->components->error(sprintf(
                        '%s — %s',
                        $domain->host,
                        $exception->getMessage(),
                    ));
                }
            },
        );

        $this->newLine();

        $this->table(
            ['Status', 'Total'],
            [
                [
                    DomainVerificationStatus::Healthy->label(),
                    $summary[DomainVerificationStatus::Healthy->value],
                ],
                [
                    DomainVerificationStatus::Warning->label(),
                    $summary[DomainVerificationStatus::Warning->value],
                ],
                [
                    DomainVerificationStatus::Critical->label(),
                    $summary[DomainVerificationStatus::Critical->value],
                ],
                [
                    DomainVerificationStatus::Unknown->label(),
                    $summary[DomainVerificationStatus::Unknown->value],
                ],
                [
                    'Failed',
                    $summary['failed'],
                ],
            ],
        );

        if ($summary['failed'] > 0) {
            $this->components->warn(
                'Domain verification completed with failures.',
            );

            return self::FAILURE;
        }

        $this->components->info(
            'Domain verification completed successfully.',
        );

        return self::SUCCESS;
    }
}
