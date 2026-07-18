<?php

namespace App\Domains\Shio\Models;

use Database\Factories\ShioNumberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShioNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'shio_period_id',
        'name',
        'numbers',
        'icon',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'numbers' => 'array',
        ];
    }

    protected static function newFactory(): ShioNumberFactory
    {
        return ShioNumberFactory::new();
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(ShioPeriod::class, 'shio_period_id');
    }
}
