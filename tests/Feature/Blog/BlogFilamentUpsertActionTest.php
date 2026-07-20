<?php

namespace Tests\Feature\Blog;

use App\Domains\Blog\Actions\UpsertBlogPostAction;
use Tests\TestCase;

class BlogFilamentUpsertActionTest extends TestCase
{
    public function test_create_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path('Filament/Resources/BlogPosts/Pages/CreateBlogPost.php')
        );

        $this->assertStringContainsString(
            UpsertBlogPostAction::class,
            $content
        );
    }

    public function test_edit_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path('Filament/Resources/BlogPosts/Pages/EditBlogPost.php')
        );

        $this->assertStringContainsString(
            UpsertBlogPostAction::class,
            $content
        );
    }
}
