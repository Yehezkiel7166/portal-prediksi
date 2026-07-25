<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

final class DomainMonitoringStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $brand = app(BrandContext::class)->get();

        if ($brand === null) {
            return [
                Stat::make('Total Domain', 0)
                    ->description('Brand belum terdeteksi')
                    ->descriptionIcon('heroicon-o-exclamation-triangle')
                    ->color('gray'),

                Stat::make('Healthy', 0)
                    ->description('Tidak ada konteks brand')
                    ->descriptionIcon('heroicon-o-check-circle')
                    ->color('gray'),

                Stat::make('Perlu Perhatian', 0)
                    ->description('Warning dan critical')
                    ->descriptionIcon('heroicon-o-exclamation-circle')
                    ->color('gray'),

                Stat::make('Belum Diverifikasi', 0)
                    ->description('Menunggu verifikasi')
                    ->descriptionIcon('heroicon-o-clock')
                    ->color('gray'),
            ];
        }

        $baseQuery = BrandDomain::query()
            ->where('brand_id', $brand->getKey());

        $total = (clone $baseQuery)->count();

        $active = (clone $baseQuery)
            ->where('is_active', true)
            ->count();

        $healthy = $this->statusCount(
            $baseQuery,
            DomainVerificationStatus::Healthy,
        );

        $warning = $this->statusCount(
            $baseQuery,
            DomainVerificationStatus::Warning,
        );

        $critical = $this->statusCount(
            $baseQuery,
            DomainVerificationStatus::Critical,
        );

        $unknown = $this->statusCount(
            $baseQuery,
            DomainVerificationStatus::Unknown,
        );

        $neverVerified = (clone $baseQuery)
            ->whereNull('verified_at')
            ->count();

        $averageScore = (clone $baseQuery)
            ->whereNotNull('verification_score')
            ->avg('verification_score');

        $averageScoreLabel = $averageScore === null
            ? 'Belum ada skor'
            : sprintf(
                'Rata-rata skor %d/100',
                (int) round((float) $averageScore),
            );

        return [
            Stat::make('Total Domain', $total)
                ->description(
                    sprintf('%d domain aktif', $active),
                )
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('primary'),

            Stat::make('Healthy', $healthy)
                ->description($averageScoreLabel)
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(
                'Perlu Perhatian',
                $warning + $critical,
            )
                ->description(
                    sprintf(
                        '%d warning · %d critical',
                        $warning,
                        $critical,
                    ),
                )
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color(
                    $critical > 0
                        ? 'danger'
                        : ($warning > 0 ? 'warning' : 'success'),
                ),

            Stat::make(
                'Belum Diverifikasi',
                $neverVerified,
            )
                ->description(
                    sprintf('%d berstatus unknown', $unknown),
                )
                ->descriptionIcon('heroicon-o-clock')
                ->color(
                    $neverVerified > 0
                        ? 'gray'
                        : 'success',
                ),
        ];
    }

    private function statusCount(
        Builder $query,
        DomainVerificationStatus $status,
    ): int {
        return (clone $query)
            ->where(
                'verification_status',
                $status->value,
            )
            ->count();
    }
}
