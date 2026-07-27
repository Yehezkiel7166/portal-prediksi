<?php

namespace App\Filament\Resources\BrandSlots\RelationManagers;

use App\Domains\Rtp\Actions\CreateRtpSnapshotAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RtpSnapshotsRelationManager extends RelationManager
{
    protected static string $relationship = 'snapshots';
    protected static ?string $title = 'Riwayat RTP';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('rtp_value')->label('RTP (%)')->numeric()->minValue(0)->maxValue(100)->step(0.01)->required(),
            DateTimePicker::make('captured_at')->label('Waktu snapshot')->default(now())->seconds(false)->required(),
            TextInput::make('source_label')->label('Sumber')->maxLength(120),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('rtp_value')->label('RTP')->suffix('%')->sortable(),
            TextColumn::make('captured_at')->label('Waktu')->dateTime()->sortable(),
            TextColumn::make('source_label')->label('Sumber')->placeholder('-'),
        ])->defaultSort('captured_at', 'desc')->headerActions([
            CreateAction::make()->using(fn (array $data): Model => app(CreateRtpSnapshotAction::class)->execute($this->getOwnerRecord(), $data)),
        ]);
    }
}
