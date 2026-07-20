<?php

namespace App\Domains\LiveDraw\Models;

use App\Domains\Market\Models\Market;
use Database\Factories\LiveDrawFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveDraw extends Model
{
    /** @use HasFactory<LiveDrawFactory> */
    use HasFactory;

    public const PROVIDER_OFFICIAL = 'official';

    public const PROVIDER_YOUTUBE = 'youtube';

    public const PROVIDER_VIMEO = 'vimeo';

    public const PROVIDER_CUSTOM = 'custom';

    public const STREAM_TYPE_URL = 'url';

    public const STREAM_TYPE_IFRAME = 'iframe';

    public const STREAM_TYPE_HLS = 'hls';

    public const STATUS_OFFLINE = 'offline';

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_LIVE = 'live';

    public const STATUS_FINISHED = 'finished';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'market_id',
        'title',
        'slug',
        'provider',
        'stream_type',
        'source_url',
        'draw_days',
        'draw_time',
        'timezone',
        'status',
        'headline',
        'footer',
        'logo_path',
        'background_path',
        'background_focal_point',
        'priority',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'market_id' => 'integer',
            'draw_days' => 'array',
            'priority' => 'integer',
        ];
    }

    protected static function newFactory(): LiveDrawFactory
    {
        return LiveDrawFactory::new();
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            self::STATUS_CANCELLED,
        ]);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('priority')
            ->orderBy('title')
            ->orderBy('id');
    }
}
