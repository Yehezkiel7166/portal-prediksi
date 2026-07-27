<?php

namespace App\Filament\Resources\Results\Schemas;

use App\Domains\Market\Models\Market;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ResultForm
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
                    ->native(false),

                DatePicker::make('result_date')
                    ->label('Tanggal Result')
                    ->default(today())
                    ->native(false)
                    ->required(),

                Textarea::make('winning_numbers')
                    ->label('Hasil')
                    ->rows(4)
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('notes')
                    ->label('Catatan Internal')
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
