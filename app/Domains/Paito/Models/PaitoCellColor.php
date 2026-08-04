<?php

namespace App\Domains\Paito\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaitoCellColor extends Model
{
    use BelongsToBrand;

    public const POSITIONS = [
        'as',
        'kop',
        'kepala',
        'ekor',
        'jumlah',
    ];

    public const COLORS = [
        'red',
        'blue',
        'green',
        'yellow',
        'orange',
        'purple',
        'pink',
        'cyan',
        'gray',
    ];

    protected $fillable = [
        'brand_id',
        'market_id',
        'result_id',
        'position',
        'color',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}
