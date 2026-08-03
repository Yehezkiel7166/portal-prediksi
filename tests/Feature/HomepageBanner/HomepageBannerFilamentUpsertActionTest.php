<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\HomepageBanner\Actions\UpsertHomepageBannerAction;
use Tests\TestCase;

final class HomepageBannerFilamentUpsertActionTest extends TestCase
{
    public function test_create_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path(
                'Filament/Resources/HomepageBanners/Pages/'
                .'CreateHomepageBanner.php'
            )
        );

        $this->assertIsString($content);

        $this->assertStringContainsString(
            UpsertHomepageBannerAction::class,
            $content
        );
    }

    public function test_edit_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path(
                'Filament/Resources/HomepageBanners/Pages/'
                .'EditHomepageBanner.php'
            )
        );

        $this->assertIsString($content);

        $this->assertStringContainsString(
            UpsertHomepageBannerAction::class,
            $content
        );
    }
}
