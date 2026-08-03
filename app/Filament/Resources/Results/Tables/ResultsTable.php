<?php

namespace App\Filament\Resources\Results\Tables;

use App\Domains\Market\Models\Market;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResultsTable
{
    public static function configure(
        Table $table
    ): Table {
        return $table
            ->defaultSort('result_date', 'desc')
            ->columns([
                TextColumn::make('market.name')
                    ->label('Pasaran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('result_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make(
                    'winning_numbers'
                )
                    ->label('Hasil')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('market_id')
                    ->label('Pasaran')
                    ->options(
                        fn (): array => Market::query()
                            ->ordered()
                            ->pluck(
                                'name',
                                'id'
                            )
                            ->all()
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
