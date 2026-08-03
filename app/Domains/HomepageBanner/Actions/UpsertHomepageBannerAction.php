<?php

declare(strict_types=1);

namespace App\Domains\HomepageBanner\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class UpsertHomepageBannerAction
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {
    }

    /** @param array<string, mixed> $data */
    public function execute(
        array $data,
        ?HomepageBanner $banner = null,
    ): HomepageBanner {
        $banner ??= new HomepageBanner();

        if (! $banner->exists) {
            $banner->brand_id = $this->brandContext->get()?->getKey();
        }

        $data['title'] = trim((string) ($data['title'] ?? ''));
        $data['subtitle'] = $this->nullableString($data['subtitle'] ?? null);
        $data['cta_label'] = $this->nullableString($data['cta_label'] ?? null);
        $data['cta_url'] = $this->nullableString($data['cta_url'] ?? null);
        $data['notes'] = $this->nullableString($data['notes'] ?? null);

        $status = (string) (
            $data['status']
            ?? HomepageBanner::STATUS_DRAFT
        );

        if (
            $status === HomepageBanner::STATUS_PUBLISHED
            && blank($data['published_at'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'published_at' =>
                    'Tanggal publikasi wajib diisi untuk banner published.',
            ]);
        }

        if (
            blank($data['cta_label'] ?? null)
            xor blank($data['cta_url'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'cta_label' => 'Label dan URL tombol harus diisi bersamaan.',
                'cta_url' => 'Label dan URL tombol harus diisi bersamaan.',
            ]);
        }

        $validated = Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
            'desktop_image_path' => ['required', 'string', 'max:2048'],
            'mobile_image_path' => ['nullable', 'string', 'max:2048'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'url:http,https', 'max:4096'],
            'focal_point' => [
                'required',
                Rule::in(HomepageBanner::FOCAL_POINTS),
            ],
            'status' => [
                'required',
                Rule::in([
                    HomepageBanner::STATUS_DRAFT,
                    HomepageBanner::STATUS_PUBLISHED,
                    HomepageBanner::STATUS_ARCHIVED,
                ]),
            ],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:published_at'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        $banner->fill(
            Arr::only($validated, $banner->getFillable())
        );

        $banner->save();

        return $banner->refresh();
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
