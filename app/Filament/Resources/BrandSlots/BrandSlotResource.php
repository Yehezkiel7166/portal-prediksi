<?php

namespace App\Filament\Resources\BrandSlots;

use App\Domains\Rtp\Models\BrandSlot;
use App\Filament\Resources\BrandSlots\Pages\CreateBrandSlot;
use App\Filament\Resources\BrandSlots\Pages\EditBrandSlot;
use App\Filament\Resources\BrandSlots\Pages\ListBrandSlots;
use App\Filament\Resources\BrandSlots\RelationManagers\RtpSnapshotsRelationManager;
use App\Filament\Resources\BrandSlots\Schemas\BrandSlotForm;
use App\Filament\Resources\BrandSlots\Tables\BrandSlotsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BrandSlotResource extends Resource
{
    protected static ?string $model = BrandSlot::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Slot Gacor / RTP';
    protected static ?string $modelLabel = 'Slot Gacor';
    protected static ?string $pluralModelLabel = 'Slot Gacor';
    protected static ?int $navigationSort = 35;

    public static function form(Schema $schema): Schema { return BrandSlotForm::configure($schema); }
    public static function table(Table $table): Table { return BrandSlotsTable::configure($table); }
    public static function getRelations(): array { return [RtpSnapshotsRelationManager::class]; }
    public static function getPages(): array
    {
        return ['index'=>ListBrandSlots::route('/'),'create'=>CreateBrandSlot::route('/create'),'edit'=>EditBrandSlot::route('/{record}/edit')];
    }
}
