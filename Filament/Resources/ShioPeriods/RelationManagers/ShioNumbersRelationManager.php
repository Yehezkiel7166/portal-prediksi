<?php

namespace App\Filament\Resources\ShioPeriods\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShioNumbersRelationManager extends RelationManager
{
    protected static string $relationship = 'shios';

    protected static ?string $title = 'Kelola Shio';

    protected static ?string $modelLabel = 'shio';

    protected static ?string $pluralModelLabel = 'daftar shio';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Shio')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('KUDA'),

                TagsInput::make('numbers')
                    ->label('Angka Shio')
                    ->required()
                    ->helperText(
                        'Masukkan setiap angka lalu tekan Enter. '
                        .'Data disimpan sebagai JSON.'
                    )
                    ->placeholder('01')
                    ->reorderable()
                    ->trim()
                    ->columnSpanFull(),

                TextInput::make('icon')
                    ->label('Ikon')
                    ->maxLength(255)
                    ->placeholder('Path atau nama ikon'),

                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->minValue(0),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Shio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('numbers')
                    ->label('Angka')
                    ->formatStateUsing(
                        fn (mixed $state): string => implode(
                            ', ',
                            is_array($state) ? $state : [],
                        ),
                    )
                    ->wrap(),

                TextColumn::make('icon')
                    ->label('Ikon')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Shio'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
