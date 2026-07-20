<?php

namespace App\Filament\Resources\LiveDraws\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LiveDrawsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('priority')
            ->columns([
                TextColumn::make('priority')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('market.name')
                    ->label('Pasaran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('provider')
                    ->label('Provider')
                    ->badge(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('draw_time')
                    ->label('Jam')
                    ->placeholder('Belum diatur'),

                TextColumn::make('timezone')
                    ->label('Zona waktu')
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
