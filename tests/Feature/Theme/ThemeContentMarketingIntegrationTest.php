<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeContentMarketingIntegrationTest extends TestCase
{
    public function test_all_content_modules_are_theme_scoped(): void
    {
        $contracts = [
            'blog/index.blade.php' => 'data-theme-content="blog-index"',

            'blog/show.blade.php' => 'data-theme-content="blog-detail"',

            'guides/index.blade.php' => 'data-theme-content="guide-index"',

            'guides/show.blade.php' => 'data-theme-content="guide-detail"',

            'promotions/index.blade.php' => 'data-theme-content="promotion-index"',

            'promotions/show.blade.php' => 'data-theme-content="promotion-detail"',
        ];

        foreach ($contracts as $path => $contract) {
            $view = file_get_contents(
                resource_path(
                    'views/frontend/'.$path,
                ),
            );

            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_blog_contract_is_preserved(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/blog/index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/blog/show.blade.php',
            ),
        );

        foreach ([
            '$blogPosts',
            '$blogPost->title',
            '$blogPost->excerpt',
            '$blogPost->published_at',
            "'blog.show'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );
        }

        foreach ([
            '$blogPost->content',
            '$blogPost->image_source',
            '$blogPost->image_path',
            '$blogPost->image_url',
            '$blogPost->focal_point',
            'data-theme-rich-content',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $show,
            );
        }
    }

    public function test_guides_contract_is_preserved(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/guides/index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/guides/show.blade.php',
            ),
        );

        foreach ([
            '$guides',
            '$guide->category',
            '$guide->title',
            '$guide->excerpt',
            "'guides.show'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );
        }

        foreach ([
            '$guide->content',
            '$guide->seo_title',
            '$guide->seo_description',
            'data-theme-rich-content',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $show,
            );
        }
    }

    public function test_promotions_contract_is_preserved(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/promotions/index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/promotions/show.blade.php',
            ),
        );

        foreach ([
            '$promotions',
            '$promotion->title',
            '$promotion->excerpt',
            '$promotion->published_at',
            "'promotions.show'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );
        }

        foreach ([
            '$promotion->content',
            '$promotion->media_source',
            '$promotion->media_path',
            '$promotion->media_url',
            '$promotion->embed_url',
            '<iframe',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $show,
            );
        }
    }

    public function test_content_media_is_preserved(): void
    {
        $blog = file_get_contents(
            resource_path(
                'views/frontend/blog/show.blade.php',
            ),
        );

        $promotion = file_get_contents(
            resource_path(
                'views/frontend/promotions/show.blade.php',
            ),
        );

        $this->assertStringContainsString(
            '<img',
            $blog,
        );

        $this->assertStringContainsString(
            '<img',
            $promotion,
        );

        $this->assertStringContainsString(
            '<iframe',
            $promotion,
        );
    }

    public function test_content_theme_css_exists(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-content-marketing">',
            '[data-theme-content]',
            '[data-theme-rich-content]',
            'var(--theme-primary)',
            'var(--theme-text)',
            'var(--theme-text-muted)',
            'var(--theme-border)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }
}
