<?php

namespace App\Filament\Resources\ResultMarkets;

use App\Domains\Market\Models\Market;
use App\Filament\Resources\ResultMarkets\Pages\ListResultMarkets;
use App\Filament\Resources\ResultMarkets\Tables\ResultMarketsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ResultMarketResource extends Resource
{
    protected static ?string $model = Market::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Result';

    protected static ?string $modelLabel =
        'pasaran result';

    protected static ?string $pluralModelLabel =
        'pasaran result';

    protected static ?int $navigationSort = 15;

    protected static ?string $slug = 'results';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            /*
             * Jangan membatasi kolom latestResult di sini.
             *
             * Relasi latestResult menggunakan ofMany(), yang membentuk
             * join/subquery internal. Select market_id tanpa qualifier
             * menghasilkan ambiguous column pada MySQL production.
             */
            ->with('latestResult')
            ->withCount('results')
            ->withMax('results', 'updated_at')
            ->ordered();
    }

    public static function table(Table $table): Table
    {
        return ResultMarketsTable::configure(
            $table
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResultMarkets::route('/'),
        ];
    }
}
