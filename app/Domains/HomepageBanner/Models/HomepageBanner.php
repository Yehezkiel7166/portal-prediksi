<?php

declare(strict_types=1);

namespace App\Domains\HomepageBanner\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use Database\Factories\HomepageBannerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class HomepageBanner extends Model
{
    use BelongsToBrand;

    /** @use HasFactory<HomepageBannerFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    public const FOCAL_POINTS = [
        'top-left',
        'top',
        'top-right',
        'left',
        'center',
        'right',
        'bottom-left',
        'bottom',
        'bottom-right',
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'desktop_image_path',
        'mobile_image_path',
        'cta_label',
        'cta_url',
        'focal_point',
        'status',
        'published_at',
        'expires_at',
        'sort_order',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'immutable_datetime',
            'expires_at' => 'immutable_datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): HomepageBannerFactory
    {
        return HomepageBannerFactory::new();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }
}
