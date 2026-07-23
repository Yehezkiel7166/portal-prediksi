<?php

namespace App\Domains\Blog\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;

use Database\Factories\BlogPostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<BlogPostFactory> */
    use HasFactory;

    public const IMAGE_SOURCE_UPLOAD = 'upload';

    public const IMAGE_SOURCE_URL = 'url';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image_source',
        'image_path',
        'image_url',
        'focal_point',
        'status',
        'published_at',
        'sort_order',
        'seo_title',
        'seo_description',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'immutable_datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): BlogPostFactory
    {
        return BlogPostFactory::new();
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
