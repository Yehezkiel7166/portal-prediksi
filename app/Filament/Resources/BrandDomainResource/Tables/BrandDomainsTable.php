<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Tables;

use App\Domains\Domain\Actions\SetPrimaryBrandDomain;
use App\Domains\Domain\Actions\UpdateBrandDomainStatus;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource\Actions\DomainTypeOptions;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class BrandDomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
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
                Action::make('setPrimary')
                    ->label('Jadikan utama')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(
                        fn (BrandDomain $record): bool => $record->is_active
                            && ! $record->is_primary,
                    )
                    ->action(
                        function (BrandDomain $record): void {
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
                        fn (BrandDomain $record): string => $record->is_active
                            ? 'Nonaktifkan'
                            : 'Aktifkan',
                    )
                    ->icon(
                        fn (BrandDomain $record): string => $record->is_active
                            ? 'heroicon-o-no-symbol'
                            : 'heroicon-o-check-circle',
                    )
                    ->color(
                        fn (BrandDomain $record): string => $record->is_active
                            ? 'danger'
                            : 'success',
                    )
                    ->requiresConfirmation()
                    ->action(
                        function (BrandDomain $record): void {
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
                        fn (BrandDomain $record): bool => $record->is_primary,
                    )
                    ->tooltip(
                        fn (BrandDomain $record): ?string => $record->is_primary
                            ? 'Domain utama tidak dapat dihapus.'
                            : null,
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
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
}
