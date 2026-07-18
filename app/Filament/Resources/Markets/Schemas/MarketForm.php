<?php

namespace App\Filament\Resources\Markets\Schemas;

use App\Core\Support\TimezoneCatalog;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MarketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->placeholder('Contoh: SGP')
                    ->required()
                    ->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn (?string $state, callable $set) => $set(
                            'code',
                            Str::upper(trim((string) $state))
                        )
                    ),

                TextInput::make('name')
                    ->label('Nama pasaran')
                    ->placeholder('Contoh: Singapore')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn (?string $state, callable $set) => $set(
                            'slug',
                            Str::slug((string) $state)
                        )
                    ),

                TextInput::make('slug')
                    ->label('Slug')
                    ->placeholder('singapore')
                    ->required()
                    ->maxLength(120)
                    ->helperText(
                        'Digunakan untuk URL publik pasaran.'
                    ),

                Select::make('timezone')
                    ->label('Zona waktu')
                    ->options(
                        fn (): array => TimezoneCatalog::options()
                    )
                    ->default('Asia/Jakarta')
                    ->searchable()
                    ->required()
                    ->native(false)
                    ->helperText(
                        'Daftar mengikuti seluruh timezone IANA yang tersedia pada server.'
                    ),

                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText(
                        'Pasaran nonaktif tidak ditampilkan pada pilihan publik.'
                    ),

                Textarea::make('notes')
                    ->label('Catatan internal')
                    ->placeholder(
                        'Catatan ini hanya terlihat oleh administrator.'
                    )
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
