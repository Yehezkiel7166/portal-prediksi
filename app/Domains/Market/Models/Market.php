<?php

namespace App\Domains\Market\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Domains\Prediction\Models\Prediction;
use App\Domains\Result\Models\Result;
use Database\Factories\MarketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use BelongsToBrand;

    /** @use HasFactory<MarketFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'timezone',
        'is_active',
        'sort_order',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): MarketFactory
    {
        return MarketFactory::new();
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
