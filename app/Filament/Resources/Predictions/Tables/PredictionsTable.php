<?php

namespace App\Filament\Resources\Predictions\Tables;

use App\Domains\Prediction\Models\Prediction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PredictionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('prediction_date', 'desc')
            ->columns([
                TextColumn::make('market')
                    ->label('Pasaran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('prediction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('predicted_numbers')
                    ->label('Angka prediksi')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => Prediction::statusOptions()[$state]
                            ?? $state
                    )
                    ->color(fn (string $state): string => match ($state) {
                        Prediction::STATUS_PUBLISHED => 'success',
                        Prediction::STATUS_ARCHIVED => 'gray',
                        default => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum diterbitkan')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Prediction::statusOptions()),
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
