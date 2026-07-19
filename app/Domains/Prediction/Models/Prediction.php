<?php

namespace App\Domains\Prediction\Models;

use App\Domains\Market\Models\Market;
use Database\Factories\PredictionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    /** @use HasFactory<PredictionFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'market_id',
        'prediction_date',
        'predicted_numbers',
        'status',
        'notes',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'prediction_date' => 'date',
            'published_at' => 'datetime',
        ];
    }

    protected static function newFactory(): PredictionFactory
    {
        return PredictionFactory::new();
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('prediction_date', $date);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Diterbitkan',
            self::STATUS_ARCHIVED => 'Diarsipkan',
        ];
    }
}
