<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Schemas;

use App\Domains\Theme\Support\ThemePresetCatalog;
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
                    'Pilih template tampilan untuk brand aktif. '
                    .'Warna akan digunakan oleh frontend yang '
                    .'sudah terhubung ke Theme Engine.'
                )
                ->schema([

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
                        ->label('Preview')
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
                                            min-height:110px;
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
                                                background:rgba(0,0,0,.55);
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

                    Placeholder::make('theme_notice')
                        ->label('')
                        ->content(
                            'Custom background, overlay, opacity, '
                            .'glass dan transparansi akan ditambahkan '
                            .'pada tahap berikutnya di section Design ini juga.',
                        ),
                ])
                ->columns(1),

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
