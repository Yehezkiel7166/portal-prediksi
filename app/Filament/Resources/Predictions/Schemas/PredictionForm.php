<?php

namespace App\Filament\Resources\Predictions\Schemas;

use App\Domains\Prediction\Models\Prediction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PredictionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('market_id')
                    ->label('Pasaran')
                    ->relationship(
                        name: 'market',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->orderBy('sort_order')
                            ->orderBy('name')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->columnSpan(1),

                DatePicker::make('prediction_date')
                    ->label('Tanggal prediksi')
                    ->required()
                    ->default(today())
                    ->native(false)
                    ->columnSpan(1),

                TextInput::make('bbfs')
                    ->label('BBFS')
                    ->placeholder('Contoh: 209184')
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),

                TextInput::make('colok_bebas')
                    ->label('Colok Bebas')
                    ->placeholder('Contoh: 9-4')
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),

                Textarea::make('prediction_2d')
                    ->label('Prediksi 2D')
                    ->placeholder('Contoh: 18, 91, 82, 12, 84')
                    ->required()
                    ->rows(3)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                Textarea::make('prediction_3d')
                    ->label('Prediksi 3D')
                    ->placeholder('Contoh: 028, 492, 241')
                    ->required()
                    ->rows(3)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                Textarea::make('prediction_4d')
                    ->label('Prediksi 4D')
                    ->placeholder('Contoh: 9482, 8491, 0981')
                    ->required()
                    ->rows(3)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                Textarea::make('kembar')
                    ->label('Angka Kembar')
                    ->placeholder('Contoh: 88, 99, 44, 00')
                    ->rows(2)
                    ->maxLength(1000)
                    ->columnSpanFull(),

                TextInput::make('shio')
                    ->label('Shio')
                    ->placeholder('Contoh: TIKUS')
                    ->maxLength(100)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options(Prediction::statusOptions())
                    ->default(Prediction::STATUS_DRAFT)
                    ->required()
                    ->native(false)
                    ->live()
                    ->columnSpan(1),

                DateTimePicker::make('published_at')
                    ->label('Waktu publikasi')
                    ->helperText(
                        'Akan diisi otomatis ketika status diterbitkan.'
                    )
                    ->seconds(false)
                    ->native(false)
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpan(1),

                Textarea::make('notes')
                    ->label('Catatan internal')
                    ->placeholder(
                        'Catatan ini hanya untuk pengelola admin.'
                    )
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
