<?php

namespace App\Domains\Promotion\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;

use Database\Factories\PromotionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<PromotionFactory> */
    use HasFactory;

    public const MEDIA_SOURCE_UPLOAD = 'upload';

    public const MEDIA_SOURCE_URL = 'url';

    public const MEDIA_SOURCE_EMBED = 'embed';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'media_source',
        'media_path',
        'media_url',
        'embed_url',
        'focal_point',
        'status',
        'published_at',
        'sort_order',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'immutable_datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): PromotionFactory
    {
        return PromotionFactory::new();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }
}
