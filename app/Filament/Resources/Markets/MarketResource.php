<?php

namespace App\Filament\Resources\Markets;

use App\Domains\Market\Models\Market;
use App\Filament\Resources\Markets\Pages\CreateMarket;
use App\Filament\Resources\Markets\Pages\EditMarket;
use App\Filament\Resources\Markets\Pages\ListMarkets;
use App\Filament\Resources\Markets\Schemas\MarketForm;
use App\Filament\Resources\Markets\Tables\MarketsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MarketResource extends Resource
{
    protected static ?string $model = Market::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-globe-asia-australia';

    protected static ?string $navigationLabel = 'Pasaran';

    protected static ?string $modelLabel = 'pasaran';

    protected static ?string $pluralModelLabel = 'pasaran';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'markets';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function form(Schema $schema): Schema
    {
        return MarketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarkets::route('/'),
            'create' => CreateMarket::route('/create'),
            'edit' => EditMarket::route('/{record}/edit'),
        ];
    }
}
