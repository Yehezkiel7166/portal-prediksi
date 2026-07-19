<?php

namespace Tests\Feature\Promotion;

use App\Domains\Promotion\Actions\UpsertPromotionAction;
use Tests\TestCase;

class PromotionFilamentUpsertActionTest extends TestCase
{
    public function test_create_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path('Filament/Resources/Promotions/Pages/CreatePromotion.php')
        );

        $this->assertStringContainsString(
            UpsertPromotionAction::class,
            $content
        );
    }

    public function test_edit_page_uses_upsert_action(): void
    {
        $content = file_get_contents(
            app_path('Filament/Resources/Promotions/Pages/EditPromotion.php')
        );

        $this->assertStringContainsString(
            UpsertPromotionAction::class,
            $content
        );
    }
}
