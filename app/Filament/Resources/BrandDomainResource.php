<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource\Pages\CreateBrandDomain;
use App\Filament\Resources\BrandDomainResource\Pages\EditBrandDomain;
use App\Filament\Resources\BrandDomainResource\Pages\ListBrandDomains;
use App\Filament\Resources\BrandDomainResource\Pages\ManageBrandDomainHealthHistory;
use App\Filament\Resources\BrandDomainResource\Schemas\BrandDomainForm;
use App\Filament\Resources\BrandDomainResource\Tables\BrandDomainsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class BrandDomainResource extends Resource
{
    protected static ?string $model = BrandDomain::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Domains';

    protected static ?string $modelLabel = 'domain';

    protected static ?string $pluralModelLabel = 'domains';

    protected static ?string $recordTitleAttribute = 'host';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'brand-domains';

    public static function getNavigationGroup(): ?string
    {
        return 'Domain Management';
    }

    public static function form(Schema $schema): Schema
    {
        return BrandDomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandDomainsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $brand = app(BrandContext::class)->get();

        if ($brand === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(
            $query->qualifyColumn('brand_id'),
            $brand->getKey(),
        );
    }

    public static function canCreate(): bool
    {
        return app(BrandContext::class)->get() !== null;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrandDomains::route('/'),
            'create' => CreateBrandDomain::route('/create'),
            'edit' => EditBrandDomain::route('/{record}/edit'),
            'health-history' => ManageBrandDomainHealthHistory::route('/{record}/health-history'),
        ];
    }
}
