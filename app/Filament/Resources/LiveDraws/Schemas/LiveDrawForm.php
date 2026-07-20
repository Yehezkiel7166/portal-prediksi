<?php

namespace App\Filament\Resources\LiveDraws\Schemas;

use App\Core\Support\TimezoneCatalog;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LiveDrawForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Live Draw')
                    ->schema([
                        Select::make('market_id')
                            ->label('Pasaran')
                            ->relationship(
                                name: 'market',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query
                                    ->orderBy('sort_order')
                                    ->orderBy('name')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (?string $state, callable $set) =>
                                    $set(
                                        'slug',
                                        Str::slug((string) $state)
                                    )
                            ),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('priority')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Sumber Live')
                    ->schema([
                        Select::make('provider')
                            ->options([
                                'official' => 'Official',
                                'youtube' => 'YouTube',
                                'vimeo' => 'Vimeo',
                                'custom' => 'Custom',
                            ])
                            ->default('official')
                            ->required(),

                        Select::make('stream_type')
                            ->label('Jenis stream')
                            ->options([
                                'url' => 'URL',
                                'iframe' => 'Iframe URL',
                                'hls' => 'HLS',
                            ])
                            ->default('url')
                            ->required(),

                        TextInput::make('source_url')
                            ->label('Source URL')
                            ->url()
                            ->maxLength(4096)
                            ->helperText(
                                'Masukkan URL HTTP/HTTPS. Raw JavaScript '
                                .'tidak diterima.'
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Jadwal')
                    ->schema([
                        CheckboxList::make('draw_days')
                            ->label('Hari draw')
                            ->options([
                                1 => 'Senin',
                                2 => 'Selasa',
                                3 => 'Rabu',
                                4 => 'Kamis',
                                5 => 'Jumat',
                                6 => 'Sabtu',
                                7 => 'Minggu',
                            ])
                            ->columns(4)
                            ->columnSpanFull(),

                        TimePicker::make('draw_time')
                            ->label('Jam draw')
                            ->seconds(false),

                        Select::make('timezone')
                            ->label('Zona waktu')
                            ->options(
                                fn (): array => TimezoneCatalog::options()
                            )
                            ->default('Asia/Jakarta')
                            ->searchable()
                            ->required()
                            ->native(false),

                        Select::make('status')
                            ->options([
                                'offline' => 'Offline',
                                'scheduled' => 'Scheduled',
                                'live' => 'Live',
                                'finished' => 'Finished',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('offline')
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Tampilan')
                    ->description(
                        'Ukuran, crop, rasio, thumbnail, dan breakpoint '
                        .'dikelola otomatis oleh sistem.'
                    )
                    ->schema([
                        TextInput::make('headline')
                            ->label('Headline')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('live-draw/logos')
                            ->visibility('public'),

                        FileUpload::make('background_path')
                            ->label('Background')
                            ->image()
                            ->disk('public')
                            ->directory('live-draw/backgrounds')
                            ->visibility('public'),

                        Select::make('background_focal_point')
                            ->label('Posisi fokus background')
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

                        Textarea::make('footer')
                            ->label('Footer')
                            ->rows(3)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Catatan Internal')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(4)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
