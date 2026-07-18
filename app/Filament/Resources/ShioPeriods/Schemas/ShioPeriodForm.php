<?php

namespace App\Filament\Resources\ShioPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ShioPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->label('Tahun Shio')
                    ->numeric()
                    ->required()
                    ->minValue(1900)
                    ->maxValue(2100),

                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(150),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->required()
                    ->native(false),

                TextInput::make('banner_template')
                    ->label('Template Banner')
                    ->maxLength(255),

                TextInput::make('generated_banner')
                    ->label('Banner Generated')
                    ->maxLength(255)
                    ->disabled(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required()
                    ->native(false),
            ])
            ->columns(2);
    }
}
