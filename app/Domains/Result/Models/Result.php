<?php

namespace App\Domains\Result\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Domains\Brand\Support\BrandContext;

use App\Domains\Market\Models\Market;
use Database\Factories\ResultFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use BelongsToBrand;
    use HasFactory;

    protected $fillable = [
        'market_id',
        'result_date',
        'winning_numbers',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'result_date' => 'date',
        ];
    }

    protected static function newFactory(): ResultFactory
    {
        return ResultFactory::new();
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
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
