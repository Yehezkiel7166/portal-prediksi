<?php

namespace App\Filament\Resources\ShioPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

                FileUpload::make('banner_template')
                    ->label('Template Banner')
                    ->helperText(
                        'Unggah template JPG, PNG, atau WebP maksimal 10 MB.'
                    )
                    ->image()
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ])
                    ->maxSize(10240)
                    ->disk('public')
                    ->directory('shio/banner-templates')
                    ->visibility('public')
                    ->preventFilePathTampering()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),

                FileUpload::make('generated_banner')
                    ->label('Banner Hasil')
                    ->helperText(
                        'Banner ini akan dibuat otomatis pada update generator.'
                    )
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),

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
