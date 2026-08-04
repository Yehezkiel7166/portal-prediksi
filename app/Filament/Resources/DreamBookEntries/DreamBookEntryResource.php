<?php

namespace App\Filament\Resources\DreamBookEntries;

use App\Domains\DreamBook\Models\DreamBookEntry;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TagsInput;
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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Tabel Mimpi';

    protected static ?string $modelLabel = 'tabel mimpi';

    protected static ?string $pluralModelLabel = 'tabel mimpi';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'dream-book';

    protected static ?int $navigationSort = 25;

    public static function getNavigationGroup(): ?string
    {
        return 'Konten Prediksi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('number')->label('Nomor')->required()->maxLength(10),
            TextInput::make('title')->label('Judul')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TagsInput::make('keywords')->label('Kata Kunci'),
            Textarea::make('interpretation')->label('Interpretasi')->required()->columnSpanFull(),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('Nomor')->sortable()->searchable(),
                TextColumn::make('title')->label('Judul')->sortable()->searchable(),
                TextColumn::make('keywords')->label('Kata Kunci')->badge(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('updated_at')->label('Diperbarui')->since(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
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
