<?php

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Promotion')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->helperText(
                                'Kosongkan untuk membuat slug otomatis dari judul.'
                            )
                            ->maxLength(255),

                        Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->description(
                        'Admin memilih sumber dan focal point. Ukuran, crop, '
                        .'rasio, thumbnail, dan breakpoint dikelola sistem.'
                    )
                    ->schema([
                        Select::make('media_source')
                            ->options([
                                'upload' => 'Upload file',
                                'url' => 'Direct image URL',
                                'embed' => 'Whitelisted embed URL',
                            ])
                            ->default('upload')
                            ->live()
                            ->required(),

                        Select::make('focal_point')
                            ->options([
                                'top-left' => 'Kiri atas',
                                'top' => 'Tengah atas',
                                'top-right' => 'Kanan atas',
                                'left' => 'Kiri tengah',
                                'center' => 'Tengah',
                                'right' => 'Kanan tengah',
                                'bottom-left' => 'Kiri bawah',
                                'bottom' => 'Tengah bawah',
                                'bottom-right' => 'Kanan bawah',
                            ])
                            ->default('center')
                            ->required(),

                        FileUpload::make('media_path')
                            ->label('Upload gambar')
                            ->image()
                            ->disk('public')
                            ->directory('promotions')
                            ->visibility('public')
                            ->visible(
                                fn ($get): bool =>
                                    $get('media_source') === 'upload'
                            )
                            ->columnSpanFull(),

                        TextInput::make('media_url')
                            ->label('Direct image URL')
                            ->url()
                            ->maxLength(4096)
                            ->visible(
                                fn ($get): bool =>
                                    $get('media_source') === 'url'
                            )
                            ->columnSpanFull(),

                        TextInput::make('embed_url')
                            ->label('Embed URL')
                            ->helperText(
                                'Hanya URL HTTPS. Raw JavaScript dan arbitrary '
                                .'script tidak diterima.'
                            )
                            ->url()
                            ->maxLength(4096)
                            ->visible(
                                fn ($get): bool =>
                                    $get('media_source') === 'embed'
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Publication')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->default('draft')
                            ->live()
                            ->required(),

                        DateTimePicker::make('published_at')
                            ->seconds(false)
                            ->visible(
                                fn ($get): bool =>
                                    $get('status') === 'published'
                            ),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),

                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
