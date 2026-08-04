<?php

namespace App\Filament\Resources\DreamBookEntries;

use App\Domains\DreamBook\Models\DreamBookEntry;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DreamBookEntryResource extends Resource
{
    protected static ?string $model = DreamBookEntry::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Tabel Mimpi';

    protected static ?string $modelLabel = 'tabel mimpi';

    protected static ?string $pluralModelLabel = 'tabel mimpi';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $slug = 'dream-book';

    protected static ?int $navigationSort = 25;

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->label('Nomor')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('00'),

                Select::make('category')
                    ->label('Kategori Angka')
                    ->options([
                        '2D' => '2D',
                        '3D' => '3D',
                        '4D' => '4D',
                    ])
                    ->default('2D')
                    ->required()
                    ->native(false),

                Textarea::make('description')
                    ->label('Keterangan')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder(
                        'PENYAIR - TAPIR - SEMPITAN - REMBULAN'
                    ),

                TextInput::make('numbers')
                    ->label('Angka')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('00 (97-48-64-98)')
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('No.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->wrap()
                    ->limit(120),

                TextColumn::make('category')
                    ->label('Kategori Angka')
                    ->badge()
                    ->sortable(),

                TextColumn::make('numbers')
                    ->label('Angka')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDreamBookEntries::route('/'),
            'create' => Pages\CreateDreamBookEntry::route('/create'),
            'edit' => Pages\EditDreamBookEntry::route('/{record}/edit'),
        ];
    }
}
