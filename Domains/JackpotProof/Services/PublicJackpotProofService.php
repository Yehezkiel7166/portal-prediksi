<?php

namespace App\Domains\JackpotProof\Services;

use App\Domains\JackpotProof\Models\JackpotProof;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PublicJackpotProofService
{
    public function paginate(int $perPage = 18): LengthAwarePaginator
    {
        return JackpotProof::query()
            ->select(['id', 'brand_id', 'title', 'slug', 'description', 'image_path', 'thumbnail_path', 'published_at', 'sort_order', 'seo_title', 'seo_description'])
            ->published()
            ->ordered()
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): JackpotProof
    {
        return JackpotProof::query()->published()->where('slug', $slug)->firstOrFail();
    }
}
