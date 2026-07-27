<?php

namespace App\Domains\Rtp\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use Database\Factories\RtpSnapshotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class RtpSnapshot extends Model
{
    use BelongsToBrand;
    /** @use HasFactory<RtpSnapshotFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['rtp_value', 'captured_at', 'source_label'];

    protected function casts(): array
    {
        return ['rtp_value' => 'decimal:2', 'captured_at' => 'immutable_datetime', 'created_at' => 'immutable_datetime'];
    }

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('RTP snapshots are immutable.'));
        static::deleting(fn () => throw new LogicException('RTP snapshots are immutable.'));
    }

    protected static function newFactory(): RtpSnapshotFactory
    {
        return RtpSnapshotFactory::new();
    }

    public function brandSlot(): BelongsTo
    {
        return $this->belongsTo(BrandSlot::class);
    }
}
