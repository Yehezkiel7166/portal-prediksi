<?php

namespace App\Domains\Rtp\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Rtp\Models\BrandSlot;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

final class UpsertBrandSlotAction
{
    public function __construct(private readonly BrandContext $brandContext) {}

    public function execute(array $data, ?BrandSlot $slot = null): BrandSlot
    {
        $slot ??= new BrandSlot();
        if (! $slot->exists) {
            $slot->brand_id = $this->brandContext->get()?->getKey();
        }

        $data['provider_name'] = trim((string) ($data['provider_name'] ?? ''));
        $data['game_name'] = trim((string) ($data['game_name'] ?? ''));
        $data['slug'] = Str::slug(trim((string) ($data['slug'] ?? '')) ?: $data['game_name']);

        $brandId = $slot->brand_id;
        $validated = Validator::make($data, [
            'provider_name' => ['required', 'string', 'max:120'],
            'game_name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:180', Rule::unique('brand_slots', 'slug')->where(fn ($q) => $q->where('brand_id', $brandId))->ignore($slot->getKey())],
            'image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'is_active' => ['required', 'boolean'],
            'is_published' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        $slot->fill(Arr::only($validated, $slot->getFillable()));
        $slot->save();
        return $slot->refresh();
    }
}
