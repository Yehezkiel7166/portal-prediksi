<?php

namespace App\Domains\Blog\Actions;

use App\Domains\Blog\Models\BlogPost;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpsertBlogPostAction
{
    /**
     * @param array<string, mixed> $data
     */
    public function execute(
        array $data,
        ?BlogPost $blogPost = null,
    ): BlogPost {
        $blogPost ??= new BlogPost();

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
                Rule::unique('blog_posts', 'slug')
                    ->ignore($blogPost->getKey()),
            ],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image_source' => [
                'required',
                Rule::in([
                    BlogPost::IMAGE_SOURCE_UPLOAD,
                    BlogPost::IMAGE_SOURCE_URL,
                ]),
            ],
            'image_path' => [
                Rule::requiredIf(
                    fn (): bool => ($data['image_source'] ?? null)
                        === BlogPost::IMAGE_SOURCE_UPLOAD
                ),
                'nullable',
                'string',
                'max:2048',
            ],
            'image_url' => [
                Rule::requiredIf(
                    fn (): bool => ($data['image_source'] ?? null)
                        === BlogPost::IMAGE_SOURCE_URL
                ),
                'nullable',
                'url:http,https',
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
                    BlogPost::STATUS_DRAFT,
                    BlogPost::STATUS_PUBLISHED,
                    BlogPost::STATUS_ARCHIVED,
                ]),
            ],
            'published_at' => [
                Rule::requiredIf(
                    fn (): bool => ($data['status'] ?? null)
                        === BlogPost::STATUS_PUBLISHED
                ),
                'nullable',
                'date',
            ],
            'sort_order' => ['required', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        if ($validated['image_source'] !== BlogPost::IMAGE_SOURCE_UPLOAD) {
            $validated['image_path'] = null;
        }

        if ($validated['image_source'] !== BlogPost::IMAGE_SOURCE_URL) {
            $validated['image_url'] = null;
        }

        $blogPost->fill(
            Arr::only($validated, $blogPost->getFillable())
        );

        $blogPost->save();

        return $blogPost->refresh();
    }
}
