<?php

namespace App\Filament\Resources\Markets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MarketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->weight('bold'),

                TextColumn::make('name')
                    ->label('Nama pasaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('timezone')
                    ->label('Zona waktu')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('open_time')
                    ->label('Buka')
                    ->placeholder('—'),

                TextColumn::make('close_time')
                    ->label('Tutup')
                    ->placeholder('—'),

                TextColumn::make('result_time')
                    ->label('Hasil')
                    ->placeholder('—'),

                IconColumn::make('is_holiday')
                    ->label('Libur')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status aktif')
                    ->trueLabel('Pasaran aktif')
                    ->falseLabel('Pasaran nonaktif')
                    ->placeholder('Semua pasaran'),
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
