<?php

namespace App\Filament\Resources\JackpotProofs;

use App\Domains\JackpotProof\Models\JackpotProof;
use App\Filament\Resources\JackpotProofs\Pages\CreateJackpotProof;
use App\Filament\Resources\JackpotProofs\Pages\EditJackpotProof;
use App\Filament\Resources\JackpotProofs\Pages\ListJackpotProofs;
use App\Filament\Resources\JackpotProofs\Schemas\JackpotProofForm;
use App\Filament\Resources\JackpotProofs\Tables\JackpotProofsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JackpotProofResource extends Resource
{
    protected static ?string $model = JackpotProof::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;
    protected static ?string $navigationLabel = 'Bukti Jackpot';
    protected static ?string $modelLabel = 'Bukti Jackpot';
    protected static ?string $pluralModelLabel = 'Bukti Jackpot';
    protected static ?int $navigationSort = 36;

    public static function form(Schema $schema): Schema { return JackpotProofForm::configure($schema); }
    public static function table(Table $table): Table { return JackpotProofsTable::configure($table); }
    public static function getPages(): array
    {
        return ['index' => ListJackpotProofs::route('/'), 'create' => CreateJackpotProof::route('/create'), 'edit' => EditJackpotProof::route('/{record}/edit')];
    }
}
