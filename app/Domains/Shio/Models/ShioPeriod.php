<?php

namespace App\Domains\Shio\Models;

use Database\Factories\ShioPeriodFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShioPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'start_date',
        'end_date',
        'banner_template',
        'generated_banner',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function newFactory(): ShioPeriodFactory
    {
        return ShioPeriodFactory::new();
    }

    public function shios(): HasMany
    {
        return $this->hasMany(ShioNumber::class);
    }
}
