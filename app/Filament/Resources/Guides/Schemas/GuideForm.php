<?php

namespace App\Filament\Resources\Guides\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Panduan')->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255),
                TextInput::make('slug')->helperText('Kosongkan untuk membuat slug otomatis.')->maxLength(255),
                TextInput::make('category')->label('Kategori')->maxLength(100),
                Textarea::make('excerpt')->label('Ringkasan')->rows(3)->columnSpanFull(),
                RichEditor::make('content')->label('Konten')->required()->columnSpanFull(),
            ])->columns(2),
            Section::make('Publikasi')->schema([
                Select::make('status')->options(['draft'=>'Draft','published'=>'Published','archived'=>'Archived'])->default('draft')->live()->required(),
                DateTimePicker::make('published_at')->label('Waktu publikasi')->seconds(false)->visible(fn ($get): bool => $get('status') === 'published'),
                TextInput::make('sort_order')->label('Urutan')->numeric()->minValue(0)->default(0)->required(),
            ])->columns(3),
            Section::make('SEO')->schema([
                TextInput::make('seo_title')->label('SEO title')->maxLength(255),
                Textarea::make('seo_description')->label('SEO description')->rows(3)->columnSpanFull(),
            ])->columns(2),
            Section::make('Catatan Internal')->schema([Textarea::make('notes')->label('Catatan')->rows(3)->columnSpanFull()]),
        ]);
    }
}
