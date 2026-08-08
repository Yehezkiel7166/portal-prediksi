<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Schemas;

use App\Domains\Theme\Support\ThemePresetCatalog;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

final class SiteConfigurationForm
{
    public static function configure(
        Schema $schema,
    ): Schema {
        return $schema->components([

            Section::make('Identitas Situs')
                ->schema([
                    TextInput::make('site_name')
                        ->label('Nama situs')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('tagline')
                        ->label('Tagline')
                        ->maxLength(255),

                    TextInput::make('logo_url')
                        ->label('URL logo')
                        ->url()
                        ->rules([
                            'url:http,https',
                        ])
                        ->maxLength(2048),

                    TextInput::make('favicon_url')
                        ->label('URL favicon')
                        ->url()
                        ->rules([
                            'url:http,https',
                        ])
                        ->maxLength(2048),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('Design')
                ->description(
                    'Atur template, background dan tampilan komponen '
                    .'untuk brand aktif.'
                )
                ->schema([

                    /*
                    |--------------------------------------------------------------------------
                    | PRESET
                    |--------------------------------------------------------------------------
                    */

                    Select::make('theme_preset')
                        ->label('Template Design')
                        ->options(
                            app(
                                ThemePresetCatalog::class,
                            )->options(),
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->dehydrated(),

                    Placeholder::make('theme_preview')
                        ->label('Preview Template')
                        ->content(
                            static function (
                                callable $get,
                            ): HtmlString {
                                $slug = $get(
                                    'theme_preset',
                                );

                                $preset = filled($slug)
                                    ? app(
                                        ThemePresetCatalog::class,
                                    )->find(
                                        (string) $slug,
                                    )
                                    : null;

                                if ($preset === null) {
                                    return new HtmlString(
                                        '<div style="
                                            padding:16px;
                                            border:1px solid #334155;
                                            border-radius:10px;
                                        ">
                                            Pilih template untuk melihat preview.
                                        </div>',
                                    );
                                }

                                $palette =
                                    $preset['palette'];

                                $background =
                                    htmlspecialchars(
                                        (string) $palette[0],
                                        ENT_QUOTES,
                                    );

                                $primary =
                                    htmlspecialchars(
                                        (string) $palette[1],
                                        ENT_QUOTES,
                                    );

                                $accent =
                                    htmlspecialchars(
                                        (string) $palette[2],
                                        ENT_QUOTES,
                                    );

                                $name =
                                    htmlspecialchars(
                                        (string) $preset['name'],
                                        ENT_QUOTES,
                                    );

                                return new HtmlString(
                                    <<<HTML
                                    <div style="
                                        overflow:hidden;
                                        border:1px solid #475569;
                                        border-radius:12px;
                                    ">
                                        <div style="
                                            min-height:120px;
                                            padding:18px;
                                            background:
                                                linear-gradient(
                                                    135deg,
                                                    {$background},
                                                    {$primary},
                                                    {$accent}
                                                );
                                            display:flex;
                                            align-items:flex-end;
                                        ">
                                            <div style="
                                                background:rgba(0,0,0,.60);
                                                color:#fff;
                                                padding:8px 12px;
                                                border-radius:8px;
                                                font-weight:700;
                                            ">
                                                {$name}
                                            </div>
                                        </div>

                                        <div style="
                                            display:flex;
                                            gap:8px;
                                            padding:12px;
                                            background:#0f172a;
                                        ">
                                            <span style="
                                                width:38px;
                                                height:38px;
                                                border-radius:8px;
                                                background:{$background};
                                                border:1px solid #64748b;
                                            "></span>

                                            <span style="
                                                width:38px;
                                                height:38px;
                                                border-radius:8px;
                                                background:{$primary};
                                                border:1px solid #64748b;
                                            "></span>

                                            <span style="
                                                width:38px;
                                                height:38px;
                                                border-radius:8px;
                                                background:{$accent};
                                                border:1px solid #64748b;
                                            "></span>
                                        </div>
                                    </div>
                                    HTML,
                                );
                            },
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | BACKGROUND
                    |--------------------------------------------------------------------------
                    */

                    Select::make('theme_background_mode')
                        ->label('Background')
                        ->options([
                            'theme' => 'Gunakan Background Template',

                            'image' => 'Upload Background Sendiri',
                        ])
                        ->default('theme')
                        ->required()
                        ->live()
                        ->dehydrated(),

                    FileUpload::make(
                        'theme_background_image',
                    )
                        ->label(
                            'Upload Background',
                        )
                        ->disk('public')
                        ->directory(
                            'brand-design/backgrounds',
                        )
                        ->image()
                        ->imagePreviewHeight('220')
                        ->maxSize(8192)
                        ->helperText(
                            'Gunakan JPG, PNG atau WebP. '
                            .'Disarankan gambar landscape resolusi tinggi.'
                        )
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image',
                        )
                        ->dehydrated(),

                    Select::make(
                        'theme_background_size',
                    )
                        ->label(
                            'Ukuran Background',
                        )
                        ->options([
                            'cover' => 'Cover',
                            'contain' => 'Contain',
                            'auto' => 'Ukuran Asli',
                        ])
                        ->default('cover')
                        ->required()
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image',
                        )
                        ->dehydrated(),

                    Select::make(
                        'theme_background_position',
                    )
                        ->label(
                            'Posisi Background',
                        )
                        ->options([
                            'center' => 'Tengah',
                            'top' => 'Atas',
                            'bottom' => 'Bawah',
                            'left' => 'Kiri',
                            'right' => 'Kanan',
                        ])
                        ->default('center')
                        ->required()
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image',
                        )
                        ->dehydrated(),

                    Toggle::make(
                        'theme_background_repeat',
                    )
                        ->label(
                            'Ulangi Background',
                        )
                        ->default(false)
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image',
                        )
                        ->dehydrated(),

                    Toggle::make(
                        'theme_background_fixed',
                    )
                        ->label(
                            'Background Tetap Saat Scroll',
                        )
                        ->default(false)
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image',
                        )
                        ->dehydrated(),

                    /*
                    |--------------------------------------------------------------------------
                    | OVERLAY
                    |--------------------------------------------------------------------------
                    */

                    Toggle::make(
                        'theme_overlay_enabled',
                    )
                        ->label(
                            'Gunakan Overlay',
                        )
                        ->helperText(
                            'Overlay membantu teks tetap terbaca '
                            .'di atas background gambar.'
                        )
                        ->default(false)
                        ->live()
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image',
                        )
                        ->dehydrated(),

                    ColorPicker::make(
                        'theme_overlay_color',
                    )
                        ->label(
                            'Warna Overlay',
                        )
                        ->default('#000000')
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image'
                                && (bool) $get(
                                    'theme_overlay_enabled',
                                ),
                        )
                        ->dehydrated(),

                    TextInput::make(
                        'theme_overlay_opacity',
                    )
                        ->label(
                            'Opacity Overlay',
                        )
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->default(25)
                        ->helperText(
                            '0% = transparan, 100% = menutupi penuh.'
                        )
                        ->visible(
                            static fn (
                                callable $get,
                            ): bool => $get(
                                'theme_background_mode',
                            ) === 'image'
                                && (bool) $get(
                                    'theme_overlay_enabled',
                                ),
                        )
                        ->dehydrated(),

                    /*
                    |--------------------------------------------------------------------------
                    | COMPONENT APPEARANCE
                    |--------------------------------------------------------------------------
                    */

                    Select::make(
                        'theme_component_style',
                    )
                        ->label(
                            'Tampilan Card / Komponen',
                        )
                        ->options([
                            'solid' => 'Solid',

                            'semi-transparent' => 'Semi Transparent',

                            'glass' => 'Glass',

                            'outline' => 'Outline',
                        ])
                        ->default('solid')
                        ->required()
                        ->live()
                        ->dehydrated(),

                    TextInput::make(
                        'theme_component_opacity',
                    )
                        ->label(
                            'Opacity Komponen',
                        )
                        ->numeric()
                        ->minValue(10)
                        ->maxValue(100)
                        ->suffix('%')
                        ->default(100)
                        ->helperText(
                            'Disarankan 65–100% agar isi tetap mudah dibaca.'
                        )
                        ->dehydrated(),

                    TextInput::make(
                        'theme_component_blur',
                    )
                        ->label(
                            'Background Blur',
                        )
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(30)
                        ->suffix('px')
                        ->default(0)
                        ->helperText(
                            'Blur terutama digunakan pada mode Glass.'
                        )
                        ->dehydrated(),

                    Placeholder::make(
                        'theme_design_note',
                    )
                        ->label('')
                        ->content(
                            'Theme berlaku untuk seluruh frontend setelah '
                            .'modul-modul terhubung penuh ke Theme Engine. '
                            .'Kontras teks dan tombol tetap mengikuti token '
                            .'template agar tampilan tidak menjadi aneh.',
                        ),
                ])
                ->columns(2),

            Section::make('SEO Default')
                ->schema([
                    TextInput::make(
                        'default_seo_title',
                    )
                        ->label(
                            'Judul SEO default',
                        )
                        ->maxLength(255),

                    Textarea::make(
                        'default_seo_description',
                    )
                        ->label(
                            'Deskripsi SEO default',
                        )
                        ->rows(3)
                        ->maxLength(500),
                ]),

            Section::make('Kontak dan Sosial')
                ->schema([
                    TextInput::make(
                        'contact_email',
                    )
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    TextInput::make(
                        'contact_phone',
                    )
                        ->label('Telepon')
                        ->tel()
                        ->maxLength(50),

                    TextInput::make(
                        'whatsapp_number',
                    )
                        ->label(
                            'Nomor WhatsApp',
                        )
                        ->maxLength(50),

                    KeyValue::make(
                        'social_links',
                    )
                        ->label(
                            'Tautan sosial',
                        )
                        ->keyLabel(
                            'Platform',
                        )
                        ->valueLabel(
                            'URL',
                        )
                        ->reorderable(),
                ])
                ->columns(2),

            Section::make('Footer')
                ->schema([
                    Textarea::make(
                        'footer_text',
                    )
                        ->label(
                            'Teks footer',
                        )
                        ->rows(3)
                        ->maxLength(1000),
                ]),
        ]);
    }
}
