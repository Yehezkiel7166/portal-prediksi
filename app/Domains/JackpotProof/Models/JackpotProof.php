<?php

namespace App\Domains\JackpotProof\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Models\User;
use Database\Factories\JackpotProofFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JackpotProof extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<JackpotProofFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'title', 'slug', 'description', 'image_path', 'thumbnail_path',
        'status', 'moderated_at', 'moderated_by', 'published_at',
        'sort_order', 'seo_title', 'seo_description', 'moderation_notes',
    ];

    protected function casts(): array
    {
        return [
            'moderated_at' => 'immutable_datetime',
            'published_at' => 'immutable_datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): JackpotProofFactory
    {
        return JackpotProofFactory::new();
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_APPROVED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }
}
