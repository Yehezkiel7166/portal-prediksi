<?php

declare(strict_types=1);

namespace App\Domains\Theme\Models;

use App\Domains\Brand\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BrandThemeSetting extends Model
{
    protected $fillable = [
        'brand_id',
        'theme_slug',

        'background_mode',
        'background_image',
        'background_size',
        'background_position',
        'background_repeat',
        'background_fixed',

        'overlay_enabled',
        'overlay_color',
        'overlay_opacity',

        'component_style',
        'component_opacity',
        'component_blur',

        'tokens',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'brand_id' => 'integer',

            'background_repeat' => 'boolean',
            'background_fixed' => 'boolean',

            'overlay_enabled' => 'boolean',
            'overlay_opacity' => 'float',

            'component_opacity' => 'float',
            'component_blur' => 'integer',

            'tokens' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(
            Brand::class,
        );
    }
}
