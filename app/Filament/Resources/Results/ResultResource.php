<?php

namespace App\Filament\Resources\Results;

use App\Domains\Result\Models\Result;
use App\Filament\Resources\Results\Pages\CreateResult;
use App\Filament\Resources\Results\Pages\EditResult;
use App\Filament\Resources\Results\Pages\ListResults;
use App\Filament\Resources\Results\Schemas\ResultForm;
use App\Filament\Resources\Results\Tables\ResultsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ResultResource extends Resource
{
    protected static ?string $model = Result::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Result';

    protected static ?string $modelLabel = 'result';

    protected static ?string $pluralModelLabel = 'result';

    protected static ?string $recordTitleAttribute = 'result_date';

    protected static ?int $navigationSort = 15;

    protected static ?string $slug = 'results';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->forCurrentBrand();
    }

    public static function form(Schema $schema): Schema
    {
        return ResultForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResultsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResults::route('/'),
            'create' => CreateResult::route('/create'),
            'edit' => EditResult::route('/{record}/edit'),
        ];
    }
}
