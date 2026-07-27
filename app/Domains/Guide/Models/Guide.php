<?php

namespace App\Domains\Guide\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use Database\Factories\GuideFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<GuideFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'category', 'status',
        'published_at', 'sort_order', 'seo_title', 'seo_description', 'notes',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'immutable_datetime', 'sort_order' => 'integer'];
    }

    protected static function newFactory(): GuideFactory
    {
        return GuideFactory::new();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }
}
