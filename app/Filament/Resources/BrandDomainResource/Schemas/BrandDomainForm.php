<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Schemas;

use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource\Actions\DomainTypeOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

final class BrandDomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Domain')
                    ->description(
                        'Konfigurasi host dan fungsi domain untuk brand aktif.',
                    )
                    ->schema([
                        TextInput::make('host')
                            ->label('Host')
                            ->placeholder('example.com')
                            ->helperText(
                                'Masukkan host tanpa path. Scheme, path, dan kapitalisasi akan dinormalisasi.',
                            )
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: BrandDomain::class,
                                column: 'host',
                                ignoreRecord: true,
                            )
                            ->columnSpanFull(),

                        Select::make('type')
                            ->label('Tipe domain')
                            ->options(
                                fn (): array => app(
                                    DomainTypeOptions::class,
                                )->execute(),
                            )
                            ->default(DomainType::Frontend->value)
                            ->required()
                            ->native(false),

                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->live()
                            ->helperText(
                                'Domain nonaktif tidak digunakan untuk resolusi request.',
                            ),

                        Toggle::make('is_primary')
                            ->label('Domain utama')
                            ->default(false)
                            ->disabled(
                                fn (Get $get): bool => ! (bool) $get(
                                    'is_active',
                                ),
                            )
                            ->dehydrated()
                            ->helperText(
                                'Hanya satu domain utama untuk setiap brand dan tipe domain.',
                            ),
                    ])
                    ->columns(2),

                Section::make('HTTPS dan keamanan')
                    ->description(
                        'Kebijakan redirect HTTPS dan header keamanan domain.',
                    )
                    ->schema([
                        Toggle::make('force_https')
                            ->label('Paksa HTTPS')
                            ->default(true)
                            ->helperText(
                                'Request HTTP dialihkan ke HTTPS.',
                            ),

                        Toggle::make('settings.send_hsts')
                            ->label('Kirim header HSTS')
                            ->default(true),

                        TextInput::make('settings.hsts_max_age')
                            ->label('HSTS max age')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(63072000)
                            ->default(31536000)
                            ->suffix('detik')
                            ->required(),

                        Select::make(
                            'settings.https_redirect_status',
                        )
                            ->label('Status redirect HTTPS')
                            ->options([
                                301 => '301 — Permanent Redirect',
                                302 => '302 — Temporary Redirect',
                                307 => '307 — Temporary Redirect',
                                308 => '308 — Permanent Redirect',
                            ])
                            ->default(308)
                            ->required()
                            ->native(false),

                        Toggle::make(
                            'settings.hsts_include_subdomains',
                        )
                            ->label('Sertakan subdomain')
                            ->default(false)
                            ->live(),

                        Toggle::make('settings.hsts_preload')
                            ->label('HSTS preload')
                            ->default(false)
                            ->disabled(
                                fn (Get $get): bool => ! (bool) $get(
                                    'settings.hsts_include_subdomains',
                                ),
                            )
                            ->dehydrated(),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
