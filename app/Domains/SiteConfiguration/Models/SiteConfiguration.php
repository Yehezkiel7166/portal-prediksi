<?php

declare(strict_types=1);

namespace App\Domains\SiteConfiguration\Models;

use App\Domains\Brand\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SiteConfiguration extends Model
{
    protected $fillable = [
        'brand_id',
        'site_name',
        'tagline',
        'logo_url',
        'favicon_url',
        'default_seo_title',
        'default_seo_description',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'social_links',
        'footer_text',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Brand, $this> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
