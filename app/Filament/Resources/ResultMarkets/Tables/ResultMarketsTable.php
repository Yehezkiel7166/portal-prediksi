<?php

namespace App\Filament\Resources\ResultMarkets\Tables;

use App\Filament\Resources\Results\ResultResource;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResultMarketsTable
{
    public static function configure(
        Table $table
    ): Table {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Pasaran')
                    ->description(
                        fn ($record): string => (string) $record->code
                    )
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make(
                    'latestResult.winning_numbers'
                )
                    ->label('Result Terbaru')
                    ->placeholder('Belum ada result')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('results_count')
                    ->label('Jumlah History')
                    ->numeric()
                    ->sortable(),

                TextColumn::make(
                    'results_max_updated_at'
                )
                    ->label('Update Terakhir')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum pernah')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('manage')
                    ->label('Kelola')
                    ->icon(
                        'heroicon-o-cog-6-tooth'
                    )
                    ->url(
                        fn ($record): string => ResultResource::getUrl(
                            'index',
                            [
                                'tableFilters' => [
                                    'market_id' => [
                                        'value' => $record->getKey(),
                                    ],
                                ],
                            ],
                        ),
                    ),
            ]);
    }
}
