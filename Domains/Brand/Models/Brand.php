<?php

namespace App\Domains\Brand\Models;

use App\Domains\Domain\Models\BrandDomain;
use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }
}
