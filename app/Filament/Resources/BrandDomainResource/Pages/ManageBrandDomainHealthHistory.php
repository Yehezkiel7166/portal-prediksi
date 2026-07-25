<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Pages;

use App\Domains\Domain\Enums\DomainVerificationStatus;
use App\Domains\Domain\Models\BrandDomainHealthHistory;
use App\Filament\Resources\BrandDomainResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class ManageBrandDomainHealthHistory extends ManageRelatedRecords
{
    protected static string $resource =
        BrandDomainResource::class;

    protected static string $relationship =
        'healthHistories';

    protected static ?string $navigationLabel =
        'Health Timeline';

    protected static ?string $title =
        'Domain Health Timeline';

    protected static ?string $breadcrumb =
        'Health Timeline';

    public function getTitle(): string
    {
        return sprintf(
            'Health Timeline: %s',
            $this->getRecord()->host,
        );
    }

    public function getSubheading(): ?string
    {
        return 'Riwayat hasil verifikasi DNS, HTTP, HTTPS, SSL, dan SEO.';
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('30s')
            ->defaultSort('verified_at', 'desc')
            ->recordTitleAttribute('host')
            ->columns([
                TextColumn::make('verified_at')
                    ->label('Waktu pemeriksaan')
                    ->dateTime('d M Y H:i:s')
                    ->since()
                    ->description(
                        fn (
                            BrandDomainHealthHistory $record,
                        ): string => $record->verified_at
                            ->format('Y-m-d H:i:s'),
                    )
                    ->sortable(),

                TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        static function (
                            DomainVerificationStatus|string|null $state,
                        ): string {
                            $status = self::statusFrom($state);

                            return match ($status) {
                                DomainVerificationStatus::Healthy => 'Healthy',

                                DomainVerificationStatus::Warning => 'Warning',

                                DomainVerificationStatus::Critical => 'Critical',

                                default => 'Unknown',
                            };
                        },
                    )
                    ->color(
                        static function (
                            DomainVerificationStatus|string|null $state,
                        ): string {
                            return match (self::statusFrom($state)) {
                                DomainVerificationStatus::Healthy => 'success',

                                DomainVerificationStatus::Warning => 'warning',

                                DomainVerificationStatus::Critical => 'danger',

                                default => 'gray',
                            };
                        },
                    )
                    ->icon(
                        static function (
                            DomainVerificationStatus|string|null $state,
                        ): string {
                            return match (self::statusFrom($state)) {
                                DomainVerificationStatus::Healthy => 'heroicon-o-check-circle',

                                DomainVerificationStatus::Warning => 'heroicon-o-exclamation-triangle',

                                DomainVerificationStatus::Critical => 'heroicon-o-x-circle',

                                default => 'heroicon-o-question-mark-circle',
                            };
                        },
                    )
                    ->sortable(),

                TextColumn::make('verification_score')
                    ->label('Skor')
                    ->suffix('/100')
                    ->badge()
                    ->color(
                        static fn (int|string|null $state): string => match (true) {
                            (int) $state >= 90 => 'success',
                            (int) $state >= 70 => 'warning',
                            default => 'danger',
                        },
                    )
                    ->sortable(),

                TextColumn::make('trend')
                    ->label('Perubahan')
                    ->state(
                        function (
                            BrandDomainHealthHistory $record,
                        ): string {
                            $previous = $this->previousHistory(
                                $record,
                            );

                            if ($previous === null) {
                                return 'Baseline';
                            }

                            $difference =
                                $record->verification_score
                                - $previous->verification_score;

                            return match (true) {
                                $difference > 0 => sprintf(
                                    'Naik +%d',
                                    $difference,
                                ),

                                $difference < 0 => sprintf(
                                    'Turun %d',
                                    $difference,
                                ),

                                default => 'Tetap',
                            };
                        },
                    )
                    ->badge()
                    ->color(
                        function (
                            BrandDomainHealthHistory $record,
                        ): string {
                            $previous = $this->previousHistory(
                                $record,
                            );

                            if ($previous === null) {
                                return 'gray';
                            }

                            return match (
                                $record->verification_score
                                <=>
                                $previous->verification_score
                            ) {
                                1 => 'success',
                                -1 => 'danger',
                                default => 'gray',
                            };
                        },
                    )
                    ->icon(
                        function (
                            BrandDomainHealthHistory $record,
                        ): string {
                            $previous = $this->previousHistory(
                                $record,
                            );

                            if ($previous === null) {
                                return 'heroicon-o-minus';
                            }

                            return match (
                                $record->verification_score
                                <=>
                                $previous->verification_score
                            ) {
                                1 => 'heroicon-o-arrow-trending-up',
                                -1 => 'heroicon-o-arrow-trending-down',
                                default => 'heroicon-o-minus',
                            };
                        },
                    ),

                TextColumn::make('issues')
                    ->label('Issue')
                    ->state(
                        static fn (
                            BrandDomainHealthHistory $record,
                        ): int => count($record->issues()),
                    )
                    ->suffix(' issue')
                    ->badge()
                    ->color(
                        static fn (
                            BrandDomainHealthHistory $record,
                        ): string => $record->hasCriticalIssue()
                            ? 'danger'
                            : (
                                count($record->issues()) > 0
                                    ? 'warning'
                                    : 'success'
                            ),
                    ),

                TextColumn::make('check_count')
                    ->label('Checks')
                    ->state(
                        static fn (
                            BrandDomainHealthHistory $record,
                        ): int => count(
                            $record->verification_checks ?? [],
                        ),
                    )
                    ->suffix(' checks')
                    ->toggleable(
                        isToggledHiddenByDefault: true,
                    ),

                TextColumn::make('host')
                    ->label('Snapshot host')
                    ->searchable()
                    ->copyable()
                    ->toggleable(
                        isToggledHiddenByDefault: true,
                    ),
            ])
            ->filters([
                SelectFilter::make('verification_status')
                    ->label('Status')
                    ->options([
                        DomainVerificationStatus::Healthy->value => 'Healthy',

                        DomainVerificationStatus::Warning->value => 'Warning',

                        DomainVerificationStatus::Critical->value => 'Critical',

                        DomainVerificationStatus::Unknown->value => 'Unknown',
                    ])
                    ->multiple(),

                Filter::make('verified_at')
                    ->label('Rentang tanggal')
                    ->schema([
                        DatePicker::make('verified_from')
                            ->label('Dari tanggal')
                            ->native(false),

                        DatePicker::make('verified_until')
                            ->label('Sampai tanggal')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->query(
                        static function (
                            Builder $query,
                            array $data,
                        ): Builder {
                            return $query
                                ->when(
                                    $data['verified_from']
                                        ?? null,

                                    static fn (
                                        Builder $query,
                                        string $date,
                                    ): Builder => $query
                                        ->whereDate(
                                            'verified_at',
                                            '>=',
                                            $date,
                                        ),
                                )
                                ->when(
                                    $data['verified_until']
                                        ?? null,

                                    static fn (
                                        Builder $query,
                                        string $date,
                                    ): Builder => $query
                                        ->whereDate(
                                            'verified_at',
                                            '<=',
                                            $date,
                                        ),
                                );
                        },
                    ),

                Filter::make('score')
                    ->label('Rentang skor')
                    ->schema([
                        TextInput::make(
                            'minimum_score',
                        )
                            ->label('Skor minimum')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),

                        TextInput::make(
                            'maximum_score',
                        )
                            ->label('Skor maksimum')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->columns(2)
                    ->query(
                        static function (
                            Builder $query,
                            array $data,
                        ): Builder {
                            return $query
                                ->when(
                                    filled(
                                        $data['minimum_score']
                                            ?? null,
                                    ),

                                    static fn (
                                        Builder $query,
                                        int|string $score,
                                    ): Builder => $query->where(
                                        'verification_score',
                                        '>=',
                                        (int) $score,
                                    ),
                                )
                                ->when(
                                    filled(
                                        $data['maximum_score']
                                            ?? null,
                                    ),

                                    static fn (
                                        Builder $query,
                                        int|string $score,
                                    ): Builder => $query->where(
                                        'verification_score',
                                        '<=',
                                        (int) $score,
                                    ),
                                );
                        },
                    ),

                Filter::make('has_issues')
                    ->label('Hanya yang memiliki issue')
                    ->query(
                        static fn (
                            Builder $query,
                        ): Builder => $query->whereIn(
                            'verification_status',
                            [
                                DomainVerificationStatus::Warning
                                    ->value,

                                DomainVerificationStatus::Critical
                                    ->value,

                                DomainVerificationStatus::Unknown
                                    ->value,
                            ],
                        ),
                    ),
            ])
            ->recordActions([
                ViewAction::make('viewTimelineEntry')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(
                        static fn (
                            BrandDomainHealthHistory $record,
                        ): string => sprintf(
                            'Health Check — %s',
                            $record->verified_at
                                ->format('d M Y H:i:s'),
                        ),
                    )
                    ->modalDescription(
                        static fn (
                            BrandDomainHealthHistory $record,
                        ): string => sprintf(
                            '%s — Score %d/100',
                            $record->host,
                            $record->verification_score,
                        ),
                    )
                    ->modalContent(
                        function (
                            BrandDomainHealthHistory $record,
                        ): View {
                            return view(
                                'filament.resources.brand-domain.health-timeline-detail',
                                [
                                    'history' => $record,
                                    'previous' => $this->previousHistory(
                                        $record,
                                    ),
                                ],
                            );
                        },
                    )
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->slideOver(),
            ])
            ->headerActions([
                Action::make('backToDomains')
                    ->label('Kembali ke Domains')
                    ->icon('heroicon-o-arrow-left')
                    ->url(
                        BrandDomainResource::getUrl('index'),
                    ),
            ])
            ->emptyStateHeading(
                'Belum ada histori verifikasi',
            )
            ->emptyStateDescription(
                'Jalankan Verify Now untuk membuat data health timeline.',
            )
            ->emptyStateIcon(
                'heroicon-o-clock',
            );
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Domain')
                ->schema([
                    TextEntry::make(
                        'host',
                    )
                        ->label('Host'),

                    TextEntry::make(
                        'verification_status',
                    )
                        ->label('Status')
                        ->badge(),

                    TextEntry::make(
                        'verification_score',
                    )
                        ->label('Skor')
                        ->suffix('/100'),

                    TextEntry::make(
                        'verified_at',
                    )
                        ->label('Diverifikasi')
                        ->dateTime('d M Y H:i:s'),
                ])
                ->columns(4),
        ]);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return parent::canAccess($parameters);
    }

    private static function statusFrom(
        DomainVerificationStatus|string|null $state,
    ): DomainVerificationStatus {
        if ($state instanceof DomainVerificationStatus) {
            return $state;
        }

        return DomainVerificationStatus::tryFrom(
            (string) $state,
        ) ?? DomainVerificationStatus::Unknown;
    }

    private function previousHistory(
        BrandDomainHealthHistory $record,
    ): ?BrandDomainHealthHistory {
        return BrandDomainHealthHistory::query()
            ->where(
                'brand_domain_id',
                $record->brand_domain_id,
            )
            ->where(
                'verified_at',
                '<',
                $record->verified_at,
            )
            ->latest('verified_at')
            ->first();
    }
}
