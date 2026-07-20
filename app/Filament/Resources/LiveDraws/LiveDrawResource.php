<?php

namespace App\Filament\Resources\LiveDraws;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Filament\Resources\LiveDraws\Pages\CreateLiveDraw;
use App\Filament\Resources\LiveDraws\Pages\EditLiveDraw;
use App\Filament\Resources\LiveDraws\Pages\ListLiveDraws;
use App\Filament\Resources\LiveDraws\Schemas\LiveDrawForm;
use App\Filament\Resources\LiveDraws\Tables\LiveDrawsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LiveDrawResource extends Resource
{
    protected static ?string $model = LiveDraw::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Live Draw';

    protected static ?string $modelLabel = 'Live Draw';

    protected static ?string $pluralModelLabel = 'Live Draw';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 30;

    protected static ?string $slug = 'live-draws';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function form(Schema $schema): Schema
    {
        return LiveDrawForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiveDrawsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLiveDraws::route('/'),
            'create' => CreateLiveDraw::route('/create'),
            'edit' => EditLiveDraw::route('/{record}/edit'),
        ];
    }
}
