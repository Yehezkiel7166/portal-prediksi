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
                TextInput::make('market')
                    ->label('Pasaran')
                    ->placeholder('Contoh: SINGAPORE')
                    ->required()
                    ->maxLength(100)
                    ->columnSpan(1),

                DatePicker::make('prediction_date')
                    ->label('Tanggal prediksi')
                    ->required()
                    ->default(today())
                    ->native(false)
                    ->columnSpan(1),

                Textarea::make('predicted_numbers')
                    ->label('Angka prediksi')
                    ->placeholder(
                        'Masukkan angka prediksi, misalnya: 1234 5678 9012'
                    )
                    ->required()
                    ->rows(5)
                    ->maxLength(500)
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
