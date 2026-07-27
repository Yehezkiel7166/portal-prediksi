<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Tables;

use App\Domains\Domain\Actions\SetPrimaryBrandDomain;
use App\Domains\Domain\Actions\UpdateBrandDomainStatus;
use App\Domains\Domain\Actions\VerifyBrandDomain;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource;
use App\Filament\Resources\BrandDomainResource\Actions\DomainTypeOptions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

final class BrandDomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->poll('30s')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('host')
                    ->label('Host')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-globe-alt')
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(
                        static function (
                            DomainType|string|null $state,
                        ): string {
                            $type = $state instanceof DomainType
                                ? $state
                                : DomainType::tryFrom(
                                    (string) $state,
                                );

                            if ($type === null) {
                                return (string) $state;
                            }

                            return app(DomainTypeOptions::class)
                                ->execute()[$type->value]
                                ?? $type->value;
                        },
                    )
                    ->sortable(),

                TextColumn::make('verification_status')
                    ->label('Status Verifikasi')
                    ->badge()
                    ->placeholder('Belum diverifikasi')
                    ->formatStateUsing(
                        static fn (
                            DomainVerificationStatus|string|null $state,
                        ): string => self::verificationStatusLabel($state),
                    )
                    ->color(
                        static fn (
                            DomainVerificationStatus|string|null $state,
                        ): string => self::verificationStatusColor($state),
                    )
                    ->icon(
                        static fn (
                            DomainVerificationStatus|string|null $state,
                        ): string => self::verificationStatusIcon($state),
                    )
                    ->sortable(),

                TextColumn::make('verification_score')
                    ->label('Skor')
                    ->formatStateUsing(
                        static fn (
                            int|string|null $state,
                        ): string => $state === null
                            ? '—'
                            : sprintf('%d/100', (int) $state),
                    )
                    ->badge()
                    ->color(
                        static function (
                            int|string|null $state,
                        ): string {
                            if ($state === null) {
                                return 'gray';
                            }

                            $score = (int) $state;

                            return match (true) {
                                $score >= 90 => 'success',
                                $score >= 60 => 'warning',
                                default => 'danger',
                            };
                        },
                    )
                    ->sortable(),

                TextColumn::make('verified_at')
                    ->label('Terakhir Diverifikasi')
                    ->placeholder('Belum pernah')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),

                IconColumn::make('is_primary')
                    ->label('Utama')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('force_https')
                    ->label('HTTPS')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true,
                    ),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe domain')
                    ->options(
                        fn (): array => app(
                            DomainTypeOptions::class,
                        )->execute(),
                    ),

                SelectFilter::make('verification_status')
                    ->label('Status verifikasi')
                    ->options(
                        self::verificationStatusOptions(),
                    ),

                Filter::make('never_verified')
                    ->label('Belum pernah diverifikasi')
                    ->query(
                        static fn (Builder $query): Builder => $query
                            ->whereNull('verified_at'),
                    ),

                TernaryFilter::make('is_active')
                    ->label('Status aktif')
                    ->trueLabel('Domain aktif')
                    ->falseLabel('Domain nonaktif')
                    ->placeholder('Semua domain'),

                TernaryFilter::make('is_primary')
                    ->label('Domain utama')
                    ->trueLabel('Domain utama')
                    ->falseLabel('Bukan domain utama')
                    ->placeholder('Semua domain'),
            ])
            ->recordActions([
                Action::make('verifyNow')
                    ->label('Verify Now')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi domain sekarang')
                    ->modalDescription(
                        'Sistem akan menjalankan pemeriksaan DNS, HTTP, HTTPS, dan SEO untuk domain ini.',
                    )
                    ->visible(
                        static fn (
                            BrandDomain $record,
                        ): bool => $record->is_active,
                    )
                    ->action(
                        static function (
                            BrandDomain $record,
                        ): void {
                            try {
                                $report = app(
                                    VerifyBrandDomain::class,
                                )->execute($record);

                                Notification::make()
                                    ->title(
                                        'Verifikasi domain selesai',
                                    )
                                    ->body(
                                        sprintf(
                                            '%s: %s dengan skor %d/100.',
                                            $record->host,
                                            $report->status->label(),
                                            $report->score,
                                        ),
                                    )
                                    ->color(
                                        self::verificationStatusColor(
                                            $report->status,
                                        ),
                                    )
                                    ->success()
                                    ->send();
                            } catch (Throwable $exception) {
                                report($exception);

                                Notification::make()
                                    ->title(
                                        'Verifikasi domain gagal',
                                    )
                                    ->body(
                                        $exception->getMessage(),
                                    )
                                    ->danger()
                                    ->send();
                            }
                        },
                    ),

                Action::make('healthTimeline')
                    ->label('Health Timeline')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->url(
                        static fn (
                            BrandDomain $record,
                        ): string => BrandDomainResource::getUrl(
                            'health-history',
                            ['record' => $record],
                        ),
                    ),

                Action::make('viewChecks')
                    ->label('Detail Pemeriksaan')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('gray')
                    ->modalHeading(
                        static fn (
                            BrandDomain $record,
                        ): string => sprintf(
                            'Hasil Verifikasi: %s',
                            $record->host,
                        ),
                    )
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(
                        static function (
                            BrandDomain $record,
                        ): View {
                            return view(
                                'filament.resources.brand-domain.verification-checks',
                                [
                                    'record' => $record,
                                    'checks' => $record->verification_checks ?? [],
                                ],
                            );
                        },
                    )
                    ->visible(
                        static fn (
                            BrandDomain $record,
                        ): bool => $record->verified_at !== null,
                    ),

                Action::make('setPrimary')
                    ->label('Jadikan utama')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(
                        static fn (
                            BrandDomain $record,
                        ): bool => $record->is_active
                            && ! $record->is_primary,
                    )
                    ->action(
                        static function (
                            BrandDomain $record,
                        ): void {
                            app(SetPrimaryBrandDomain::class)
                                ->execute($record);

                            Notification::make()
                                ->title(
                                    'Domain utama berhasil diperbarui',
                                )
                                ->success()
                                ->send();
                        },
                    ),

                Action::make('toggleStatus')
                    ->label(
                        static fn (
                            BrandDomain $record,
                        ): string => $record->is_active
                            ? 'Nonaktifkan'
                            : 'Aktifkan',
                    )
                    ->icon(
                        static fn (
                            BrandDomain $record,
                        ): string => $record->is_active
                            ? 'heroicon-o-no-symbol'
                            : 'heroicon-o-check-circle',
                    )
                    ->color(
                        static fn (
                            BrandDomain $record,
                        ): string => $record->is_active
                            ? 'danger'
                            : 'success',
                    )
                    ->requiresConfirmation()
                    ->action(
                        static function (
                            BrandDomain $record,
                        ): void {
                            $updated = app(
                                UpdateBrandDomainStatus::class,
                            )->execute(
                                $record,
                                ! $record->is_active,
                            );

                            Notification::make()
                                ->title(
                                    $updated->is_active
                                        ? 'Domain berhasil diaktifkan'
                                        : 'Domain berhasil dinonaktifkan',
                                )
                                ->success()
                                ->send();
                        },
                    ),

                EditAction::make(),

                DeleteAction::make()
                    ->disabled(
                        static fn (
                            BrandDomain $record,
                        ): bool => $record->is_primary,
                    )
                    ->tooltip(
                        static fn (
                            BrandDomain $record,
                        ): ?string => $record->is_primary
                            ? 'Domain utama tidak dapat dihapus.'
                            : null,
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('verifySelected')
                        ->label('Verify Selected')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading(
                            'Verifikasi domain terpilih',
                        )
                        ->modalDescription(
                            'Hanya domain aktif yang akan diverifikasi.',
                        )
                        ->deselectRecordsAfterCompletion()
                        ->action(
                            static function (
                                Collection $records,
                            ): void {
                                $verified = 0;
                                $skipped = 0;
                                $failed = 0;

                                foreach ($records as $record) {
                                    if (
                                        ! $record instanceof BrandDomain
                                        || ! $record->is_active
                                    ) {
                                        $skipped++;

                                        continue;
                                    }

                                    try {
                                        app(
                                            VerifyBrandDomain::class,
                                        )->execute($record);

                                        $verified++;
                                    } catch (Throwable $exception) {
                                        $failed++;

                                        report($exception);
                                    }
                                }

                                if ($failed > 0) {
                                    Notification::make()
                                        ->title(
                                            'Verifikasi selesai dengan kegagalan',
                                        )
                                        ->body(
                                            sprintf(
                                                '%d berhasil, %d dilewati, %d gagal.',
                                                $verified,
                                                $skipped,
                                                $failed,
                                            ),
                                        )
                                        ->warning()
                                        ->send();

                                    return;
                                }

                                Notification::make()
                                    ->title(
                                        'Verifikasi domain selesai',
                                    )
                                    ->body(
                                        sprintf(
                                            '%d berhasil dan %d dilewati.',
                                            $verified,
                                            $skipped,
                                        ),
                                    )
                                    ->success()
                                    ->send();
                            },
                        ),

                    DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('Belum ada domain')
            ->emptyStateDescription(
                'Tambahkan domain frontend, admin, API, asset, atau preview.',
            )
            ->emptyStateIcon('heroicon-o-globe-alt');
    }

    /**
     * @return array<string, string>
     */
    private static function verificationStatusOptions(): array
    {
        $options = [];

        foreach (DomainVerificationStatus::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }

    private static function verificationStatusLabel(
        DomainVerificationStatus|string|null $status,
    ): string {
        $resolved = self::resolveVerificationStatus($status);

        return $resolved?->label() ?? 'Belum diverifikasi';
    }

    private static function verificationStatusColor(
        DomainVerificationStatus|string|null $status,
    ): string {
        return match (self::resolveVerificationStatus($status)) {
            DomainVerificationStatus::Healthy => 'success',
            DomainVerificationStatus::Warning => 'warning',
            DomainVerificationStatus::Critical => 'danger',
            DomainVerificationStatus::Unknown => 'gray',
            null => 'gray',
        };
    }

    private static function verificationStatusIcon(
        DomainVerificationStatus|string|null $status,
    ): string {
        return match (self::resolveVerificationStatus($status)) {
            DomainVerificationStatus::Healthy => 'heroicon-o-check-circle',

            DomainVerificationStatus::Warning => 'heroicon-o-exclamation-triangle',

            DomainVerificationStatus::Critical => 'heroicon-o-x-circle',

            DomainVerificationStatus::Unknown => 'heroicon-o-question-mark-circle',

            null => 'heroicon-o-clock',
        };
    }

    private static function resolveVerificationStatus(
        DomainVerificationStatus|string|null $status,
    ): ?DomainVerificationStatus {
        if ($status instanceof DomainVerificationStatus) {
            return $status;
        }

        if ($status === null || $status === '') {
            return null;
        }

        return DomainVerificationStatus::tryFrom(
            (string) $status,
        );
    }
}
