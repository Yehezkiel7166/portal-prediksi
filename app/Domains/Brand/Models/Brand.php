<?php

namespace App\Domains\Brand\Models;

use App\Domains\Domain\Models\BrandDomain;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'domain',
        'is_active',
        'is_primary',
        'sort_order',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
            'settings' => 'array',
        ];
    }

    /**
     * Registered frontend, admin, API, asset, and preview domains.
     *
     * @return HasMany<BrandDomain, $this>
     */
    public function domains(): HasMany
    {
        return $this->hasMany(BrandDomain::class);
    }

    /** @return HasOne<SiteConfiguration, $this> */
    public function siteConfiguration(): HasOne
    {
        return $this->hasOne(SiteConfiguration::class);
    }
    /** @return HasMany<HomepageBanner, $this> */
    public function homepageBanners(): HasMany
    {
        return $this->hasMany(HomepageBanner::class);
    }

    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }
}
