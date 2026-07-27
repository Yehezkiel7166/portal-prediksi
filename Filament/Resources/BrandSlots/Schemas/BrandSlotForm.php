<?php

namespace App\Filament\Resources\BrandSlots\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class BrandSlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Game')->schema([
                TextInput::make('provider_name')->label('Provider')->required()->maxLength(120),
                TextInput::make('game_name')->label('Nama game')->required()->maxLength(180),
                TextInput::make('slug')->helperText('Kosongkan untuk membuat slug otomatis dari nama game.')->maxLength(180),
                TextInput::make('image_url')->label('Image URL')->url()->maxLength(2048)->columnSpanFull(),
            ])->columns(2),
            Section::make('Publication')->schema([
                Toggle::make('is_active')->label('Aktif')->default(true),
                Toggle::make('is_published')->label('Dipublikasikan')->default(false),
                TextInput::make('sort_order')->label('Urutan')->numeric()->minValue(0)->default(0)->required(),
                Textarea::make('notes')->rows(3)->columnSpanFull(),
            ])->columns(3),
        ]);
    }
}
