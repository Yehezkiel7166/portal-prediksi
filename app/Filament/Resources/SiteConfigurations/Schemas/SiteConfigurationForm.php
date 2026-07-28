<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class SiteConfigurationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas Situs')->schema([
                TextInput::make('site_name')->label('Nama situs')->required()->maxLength(150),
                TextInput::make('tagline')->label('Tagline')->maxLength(255),
                TextInput::make('logo_url')->label('URL logo')->url()->rules(['url:http,https'])->maxLength(2048),
                TextInput::make('favicon_url')->label('URL favicon')->url()->rules(['url:http,https'])->maxLength(2048),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ])->columns(2),
            Section::make('SEO Default')->schema([
                TextInput::make('default_seo_title')->label('Judul SEO default')->maxLength(255),
                Textarea::make('default_seo_description')->label('Deskripsi SEO default')->rows(3)->maxLength(500),
            ]),
            Section::make('Kontak dan Sosial')->schema([
                TextInput::make('contact_email')->label('Email')->email()->maxLength(255),
                TextInput::make('contact_phone')->label('Telepon')->tel()->maxLength(50),
                TextInput::make('whatsapp_number')->label('Nomor WhatsApp')->maxLength(50),
                KeyValue::make('social_links')->label('Tautan sosial')->keyLabel('Platform')->valueLabel('URL')->reorderable(),
            ])->columns(2),
            Section::make('Footer')->schema([
                Textarea::make('footer_text')->label('Teks footer')->rows(3)->maxLength(1000),
            ]),
        ]);
    }
}
