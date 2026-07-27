<?php

namespace App\Domains\Brand\Concerns;

use App\Domains\Brand\Models\Brand;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBrand
{
    use UsesBrandScope;
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
