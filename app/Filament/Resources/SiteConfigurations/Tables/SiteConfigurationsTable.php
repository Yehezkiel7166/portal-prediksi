<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class SiteConfigurationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')->label('Nama situs')->searchable(),
                TextColumn::make('default_seo_title')->label('Judul SEO')->limit(50),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }
}
