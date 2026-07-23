<?php

namespace App\Domains\LiveDraw\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Domains\Brand\Support\BrandContext;

use App\Domains\Market\Models\Market;
use Database\Factories\LiveDrawFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveDraw extends Model
{
    use BelongsToBrand;
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


    public function scopeForCurrentBrand(Builder $query): Builder
    {
        $brand = app(BrandContext::class)->get();

        if ($brand === null) {
            return $query;
        }

        return $query->where(
            $this->qualifyColumn('brand_id'),
            $brand->id,
        );
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

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function publicEmbedUrl(): ?string
    {
        if (
            ! $this->isLive()
            || $this->stream_type !== self::STREAM_TYPE_IFRAME
            || blank($this->source_url)
        ) {
            return null;
        }

        return match ($this->provider) {
            self::PROVIDER_YOUTUBE => $this->youtubeEmbedUrl(),
            self::PROVIDER_VIMEO => $this->vimeoEmbedUrl(),
            default => null,
        };
    }

    private function youtubeEmbedUrl(): ?string
    {
        $url = (string) $this->source_url;
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $videoId = null;

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = explode('/', $path)[0] ?? null;
        }

        if (
            in_array(
                $host,
                [
                    'youtube.com',
                    'www.youtube.com',
                    'm.youtube.com',
                    'youtube-nocookie.com',
                    'www.youtube-nocookie.com',
                ],
                true,
            )
        ) {
            parse_str(
                (string) parse_url($url, PHP_URL_QUERY),
                $query,
            );

            $videoId = $query['v'] ?? null;

            if (str_starts_with($path, 'embed/')) {
                $videoId = explode('/', $path)[1] ?? null;
            }

            if (str_starts_with($path, 'shorts/')) {
                $videoId = explode('/', $path)[1] ?? null;
            }
        }

        if (
            ! is_string($videoId)
            || ! preg_match('/^[A-Za-z0-9_-]{6,32}$/', $videoId)
        ) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$videoId;
    }

    private function vimeoEmbedUrl(): ?string
    {
        $url = (string) $this->source_url;
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if (
            ! in_array(
                $host,
                [
                    'vimeo.com',
                    'www.vimeo.com',
                    'player.vimeo.com',
                ],
                true,
            )
        ) {
            return null;
        }

        $segments = array_values(
            array_filter(
                explode(
                    '/',
                    trim(
                        (string) parse_url($url, PHP_URL_PATH),
                        '/',
                    ),
                ),
            ),
        );

        $videoId = end($segments);

        if (
            ! is_string($videoId)
            || ! preg_match('/^\d{5,20}$/', $videoId)
        ) {
            return null;
        }

        return 'https://player.vimeo.com/video/'.$videoId;
    }
}
