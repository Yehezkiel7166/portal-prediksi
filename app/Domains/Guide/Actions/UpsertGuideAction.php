<?php

namespace App\Domains\Guide\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Guide\Models\Guide;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpsertGuideAction
{
    public function __construct(private readonly BrandContext $brandContext) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, ?Guide $guide = null): Guide
    {
        $isCreating = $guide === null;
        $guide ??= new Guide();

        if ($isCreating) {
            $guide->brand_id = $this->brandContext->get()?->getKey();
        }

        $data['title'] = trim((string) ($data['title'] ?? ''));
        $data['slug'] = Str::slug(trim((string) ($data['slug'] ?? '')) ?: $data['title']);
        $data['category'] = trim((string) ($data['category'] ?? '')) ?: null;

        $validated = Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('guides', 'slug')->ignore($guide->getKey())],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in([Guide::STATUS_DRAFT, Guide::STATUS_PUBLISHED, Guide::STATUS_ARCHIVED])],
            'published_at' => [Rule::requiredIf(fn (): bool => ($data['status'] ?? null) === Guide::STATUS_PUBLISHED), 'nullable', 'date'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        $guide->fill(Arr::only($validated, $guide->getFillable()));
        $guide->save();

        return $guide->refresh();
    }
}
