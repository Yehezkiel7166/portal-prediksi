<?php

namespace App\Domains\Complaint\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Models\User;
use Database\Factories\ComplaintFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<ComplaintFactory> */
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'reference_code', 'name', 'contact', 'subject', 'message', 'status',
        'reviewed_at', 'resolved_at', 'handled_by', 'admin_notes', 'source_ip', 'user_agent',
    ];

    protected $hidden = ['source_ip', 'user_agent'];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'immutable_datetime',
            'resolved_at' => 'immutable_datetime',
        ];
    }

    protected static function newFactory(): ComplaintFactory
    {
        return ComplaintFactory::new();
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeNewest(Builder $query): Builder
    {
        return $query->latest('id');
    }
}
