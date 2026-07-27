<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Artikel')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->helperText(
                                'Kosongkan untuk membuat slug otomatis dari judul.'
                            )
                            ->maxLength(255),

                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Konten')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Gambar Utama')
                    ->description(
                        'Admin memilih sumber dan focal point. Ukuran, crop, '
                        .'rasio, thumbnail, dan breakpoint dikelola sistem.'
                    )
                    ->schema([
                        Select::make('image_source')
                            ->label('Sumber gambar')
                            ->options([
                                'upload' => 'Upload file',
                                'url' => 'Direct image URL',
                            ])
                            ->default('upload')
                            ->live()
                            ->required(),

                        Select::make('focal_point')
                            ->label('Posisi fokus')
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

                        FileUpload::make('image_path')
                            ->label('Upload gambar')
                            ->image()
                            ->disk('public')
                            ->directory('blog')
                            ->visibility('public')
                            ->visible(
                                fn ($get): bool =>
                                    $get('image_source') === 'upload'
                            )
                            ->columnSpanFull(),

                        TextInput::make('image_url')
                            ->label('Direct image URL')
                            ->url()
                            ->maxLength(4096)
                            ->visible(
                                fn ($get): bool =>
                                    $get('image_source') === 'url'
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Publikasi')
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
                            ->label('Waktu publikasi')
                            ->seconds(false)
                            ->visible(
                                fn ($get): bool =>
                                    $get('status') === 'published'
                            ),

                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO title')
                            ->maxLength(255),

                        Textarea::make('seo_description')
                            ->label('SEO description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Catatan Internal')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
