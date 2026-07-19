<?php

namespace App\Domains\Promotion\Actions;

use App\Domains\Promotion\Models\Promotion;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpsertPromotionAction
{
    /**
     * @param array<string, mixed> $data
     */
    public function execute(array $data, ?Promotion $promotion = null): Promotion
    {
        $promotion ??= new Promotion();

        $data['title'] = trim((string) ($data['title'] ?? ''));
        $data['slug'] = Str::slug(
            trim((string) ($data['slug'] ?? '')) ?: $data['title']
        );

        $validated = Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('promotions', 'slug')->ignore($promotion->getKey()),
            ],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'media_source' => [
                'required',
                Rule::in([
                    Promotion::MEDIA_SOURCE_UPLOAD,
                    Promotion::MEDIA_SOURCE_URL,
                    Promotion::MEDIA_SOURCE_EMBED,
                ]),
            ],
            'media_path' => [
                Rule::requiredIf(
                    fn (): bool => ($data['media_source'] ?? null)
                        === Promotion::MEDIA_SOURCE_UPLOAD
                ),
                'nullable',
                'string',
                'max:2048',
            ],
            'media_url' => [
                Rule::requiredIf(
                    fn (): bool => ($data['media_source'] ?? null)
                        === Promotion::MEDIA_SOURCE_URL
                ),
                'nullable',
                'url:http,https',
                'max:4096',
            ],
            'embed_url' => [
                Rule::requiredIf(
                    fn (): bool => ($data['media_source'] ?? null)
                        === Promotion::MEDIA_SOURCE_EMBED
                ),
                'nullable',
                'url:https',
                'max:4096',
            ],
            'focal_point' => [
                'required',
                Rule::in([
                    'top-left',
                    'top',
                    'top-right',
                    'left',
                    'center',
                    'right',
                    'bottom-left',
                    'bottom',
                    'bottom-right',
                ]),
            ],
            'status' => [
                'required',
                Rule::in([
                    Promotion::STATUS_DRAFT,
                    Promotion::STATUS_PUBLISHED,
                    Promotion::STATUS_ARCHIVED,
                ]),
            ],
            'published_at' => [
                Rule::requiredIf(
                    fn (): bool => ($data['status'] ?? null)
                        === Promotion::STATUS_PUBLISHED
                ),
                'nullable',
                'date',
            ],
            'sort_order' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        if ($validated['media_source'] !== Promotion::MEDIA_SOURCE_UPLOAD) {
            $validated['media_path'] = null;
        }

        if ($validated['media_source'] !== Promotion::MEDIA_SOURCE_URL) {
            $validated['media_url'] = null;
        }

        if ($validated['media_source'] !== Promotion::MEDIA_SOURCE_EMBED) {
            $validated['embed_url'] = null;
        }

        $promotion->fill(Arr::only($validated, $promotion->getFillable()));
        $promotion->save();

        return $promotion->refresh();
    }
}
