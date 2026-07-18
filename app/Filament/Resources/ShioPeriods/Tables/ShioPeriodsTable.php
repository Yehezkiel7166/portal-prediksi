<?php

namespace App\Filament\Resources\ShioPeriods\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShioPeriodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y'),

                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since(),
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
