<?php

namespace App\Filament\Resources\ShioPeriods;

use App\Domains\Shio\Models\ShioPeriod;
use App\Filament\Resources\ShioPeriods\Pages\CreateShioPeriod;
use App\Filament\Resources\ShioPeriods\Pages\EditShioPeriod;
use App\Filament\Resources\ShioPeriods\Pages\ListShioPeriods;
use App\Filament\Resources\ShioPeriods\Schemas\ShioPeriodForm;
use App\Filament\Resources\ShioPeriods\Tables\ShioPeriodsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ShioPeriodResource extends Resource
{
    protected static ?string $model = ShioPeriod::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Shio';

    protected static ?string $modelLabel = 'shio';

    protected static ?string $pluralModelLabel = 'shio';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'shio-periods';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function form(Schema $schema): Schema
    {
        return ShioPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShioPeriodsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShioPeriods::route('/'),
            'create' => CreateShioPeriod::route('/create'),
            'edit' => EditShioPeriod::route('/{record}/edit'),
        ];
    }
}
