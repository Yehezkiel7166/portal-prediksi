<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageBanners\Schemas;

use App\Domains\HomepageBanner\Models\HomepageBanner;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class HomepageBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten Banner')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('subtitle')
                        ->label('Subjudul')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    TextInput::make('cta_label')
                        ->label('Label tombol')
                        ->maxLength(120)
                        ->helperText(
                            'Isi bersama URL tombol atau kosongkan keduanya.'
                        ),

                    TextInput::make('cta_url')
                        ->label('URL tombol')
                        ->url()
                        ->maxLength(4096)
                        ->helperText('Hanya URL HTTP atau HTTPS.'),
                ])
                ->columns(2),

            Section::make('Gambar')
                ->description(
                    'Gambar desktop wajib. Gambar mobile digunakan pada layar kecil.'
                )
                ->schema([
                    FileUpload::make('desktop_image_path')
                        ->label('Banner desktop')
                        ->image()
                        ->disk('public')
                        ->directory('homepage-banners/desktop')
                        ->visibility('public')
                        ->imageEditor()
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ])
                        ->maxSize(5120)
                        ->required()
                        ->columnSpanFull(),

                    FileUpload::make('mobile_image_path')
                        ->label('Banner mobile')
                        ->image()
                        ->disk('public')
                        ->directory('homepage-banners/mobile')
                        ->visibility('public')
                        ->imageEditor()
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ])
                        ->maxSize(5120)
                        ->columnSpanFull(),

                    Select::make('focal_point')
                        ->label('Fokus gambar')
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
                ]),

            Section::make('Publikasi')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            HomepageBanner::STATUS_DRAFT => 'Draft',
                            HomepageBanner::STATUS_PUBLISHED => 'Published',
                            HomepageBanner::STATUS_ARCHIVED => 'Archived',
                        ])
                        ->default(HomepageBanner::STATUS_DRAFT)
                        ->live()
                        ->required(),

                    DateTimePicker::make('published_at')
                        ->label('Mulai publikasi')
                        ->seconds(false)
                        ->visible(
                            fn ($get): bool =>
                                $get('status')
                                === HomepageBanner::STATUS_PUBLISHED
                        ),

                    DateTimePicker::make('expires_at')
                        ->label('Berakhir pada')
                        ->seconds(false)
                        ->helperText(
                            'Opsional. Banner tidak tampil setelah waktu ini.'
                        ),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    Textarea::make('notes')
                        ->label('Catatan internal')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(4),
        ]);
    }
}
