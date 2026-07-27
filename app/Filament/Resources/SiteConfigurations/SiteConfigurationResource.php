<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use App\Filament\Resources\SiteConfigurations\Pages\CreateSiteConfiguration;
use App\Filament\Resources\SiteConfigurations\Pages\EditSiteConfiguration;
use App\Filament\Resources\SiteConfigurations\Pages\ListSiteConfigurations;
use App\Filament\Resources\SiteConfigurations\Schemas\SiteConfigurationForm;
use App\Filament\Resources\SiteConfigurations\Tables\SiteConfigurationsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class SiteConfigurationResource extends Resource
{
    protected static ?string $model = SiteConfiguration::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Konfigurasi Situs';

    protected static ?string $modelLabel = 'konfigurasi situs';

    protected static ?string $pluralModelLabel = 'konfigurasi situs';

    protected static ?string $recordTitleAttribute = 'site_name';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'site-configuration';

    public static function getNavigationGroup(): ?string
    {
        return 'Pengaturan Brand';
    }

    public static function form(Schema $schema): Schema
    {
        return SiteConfigurationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteConfigurationsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $brand = app(BrandContext::class)->get();

        if ($brand === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where($query->qualifyColumn('brand_id'), $brand->getKey());
    }

    public static function canCreate(): bool
    {
        $brand = app(BrandContext::class)->get();

        return $brand !== null
            && ! SiteConfiguration::query()->where('brand_id', $brand->getKey())->exists();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteConfigurations::route('/'),
            'create' => CreateSiteConfiguration::route('/create'),
            'edit' => EditSiteConfiguration::route('/{record}/edit'),
        ];
    }
}
