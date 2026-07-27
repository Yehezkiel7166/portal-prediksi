<?php

namespace App\Domains\Rtp\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use Database\Factories\BrandSlotFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BrandSlot extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<BrandSlotFactory> */
    use HasFactory;

    protected $fillable = [
        'provider_name', 'game_name', 'slug', 'image_url',
        'is_active', 'is_published', 'sort_order', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): BrandSlotFactory
    {
        return BrandSlotFactory::new();
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(RtpSnapshot::class);
    }

    public function latestSnapshot(): HasOne
    {
        return $this->hasOne(RtpSnapshot::class)->latestOfMany('captured_at');
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('provider_name')->orderBy('game_name')->orderBy('id');
    }
}
