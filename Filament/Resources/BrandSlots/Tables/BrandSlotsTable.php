<?php

namespace App\Filament\Resources\BrandSlots\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class BrandSlotsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('provider_name')->label('Provider')->searchable()->sortable(),
            TextColumn::make('game_name')->label('Game')->searchable()->sortable(),
            TextColumn::make('latestSnapshot.rtp_value')->label('RTP terbaru')->suffix('%')->placeholder('Belum ada'),
            IconColumn::make('is_active')->label('Aktif')->boolean(),
            IconColumn::make('is_published')->label('Publik')->boolean(),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
            TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->defaultSort('sort_order')->recordActions([EditAction::make()])->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
