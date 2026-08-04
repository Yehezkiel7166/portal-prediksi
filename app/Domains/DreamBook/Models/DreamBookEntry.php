<?php

namespace App\Domains\DreamBook\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DreamBookEntry extends Model
{
    use BelongsToBrand;

    protected $fillable = [
        'brand_id',
        'number',
        'slug',
        'title',
        'keywords',
        'interpretation',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('number');
    }
}
