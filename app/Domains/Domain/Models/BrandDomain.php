<?php

declare(strict_types=1);

namespace App\Domains\Domain\Models;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use Database\Factories\BrandDomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandDomain extends Model
{
    /** @use HasFactory<BrandDomainFactory> */
    use HasFactory;

    protected $table = 'brand_domains';

    protected $fillable = [
        'brand_id',
        'host',
        'type',
        'is_primary',
        'is_active',
        'force_https',
        'sort_order',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'type' => DomainType::class,
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'force_https' => 'boolean',
            'sort_order' => 'integer',
            'settings' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    protected static function newFactory(): BrandDomainFactory
    {
        return BrandDomainFactory::new();
    }
}
