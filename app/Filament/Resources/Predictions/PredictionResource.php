<?php

namespace App\Filament\Resources\Predictions;

use App\Domains\Prediction\Models\Prediction;
use App\Filament\Resources\Predictions\Pages\CreatePrediction;
use App\Filament\Resources\Predictions\Pages\EditPrediction;
use App\Filament\Resources\Predictions\Pages\ListPredictions;
use App\Filament\Resources\Predictions\Schemas\PredictionForm;
use App\Filament\Resources\Predictions\Tables\PredictionsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PredictionResource extends Resource
{
    protected static ?string $model = Prediction::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Prediksi';

    protected static ?string $modelLabel = 'prediksi';

    protected static ?string $pluralModelLabel = 'prediksi';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'predictions';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ;
    }

    public static function form(Schema $schema): Schema
    {
        return PredictionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PredictionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPredictions::route('/'),
            'create' => CreatePrediction::route('/create'),
            'edit' => EditPrediction::route('/{record}/edit'),
        ];
    }
}
