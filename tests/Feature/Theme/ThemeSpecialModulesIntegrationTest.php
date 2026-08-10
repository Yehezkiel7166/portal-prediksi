<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeSpecialModulesIntegrationTest extends TestCase
{
    public function test_special_modules_are_theme_scoped(): void
    {
        $contracts = [
            'slot-gacor/index.blade.php' => 'data-theme-special="slot-gacor"',

            'jackpot-proofs/index.blade.php' => 'data-theme-special="jackpot-index"',

            'jackpot-proofs/show.blade.php' => 'data-theme-special="jackpot-detail"',

            'complaints/create.blade.php' => 'data-theme-special="complaints"',
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

    public function test_slot_gacor_contract_is_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/slot-gacor/index.blade.php',
            ),
        );

        foreach ([
            '$slots',
            '$slot->game_name',
            '$slot->provider_name',
            '$slot->image_url',
            '$slot->latestSnapshot',
            '$slot->latestSnapshot->rtp_value',
            '$slot->latestSnapshot->captured_at',
            'data-theme-rtp-value',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_jackpot_proof_contract_is_preserved(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/jackpot-proofs/index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/jackpot-proofs/show.blade.php',
            ),
        );

        foreach ([
            '$proofs',
            '$proof->thumbnail_path',
            '$proof->image_path',
            '$proof->title',
            '$proof->description',
            '$proof->published_at',
            "'jackpot-proofs.show'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );
        }

        foreach ([
            '$proof->image_path',
            '$proof->seo_title',
            '$proof->seo_description',
            '$proof->description',
            '$proof->published_at',
            'data-theme-rich-content',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $show,
            );
        }
    }

    public function test_complaint_form_contract_is_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/complaints/create.blade.php',
            ),
        );

        foreach ([
            "route('complaints.store')",
            '@csrf',
            'name="website"',
            'name="name"',
            'name="contact"',
            'name="subject"',
            'name="message"',
            "session('complaint_submitted')",
            "session('complaint_reference')",
            '$errors->any()',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_complaint_semantic_states_are_themed(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/complaints/create.blade.php',
            ),
        );

        $this->assertStringContainsString(
            'data-theme-complaint-status="success"',
            $view,
        );

        $this->assertStringContainsString(
            'data-theme-complaint-status="error"',
            $view,
        );
    }

    public function test_media_is_preserved(): void
    {
        $slot = file_get_contents(
            resource_path(
                'views/frontend/slot-gacor/index.blade.php',
            ),
        );

        $index = file_get_contents(
            resource_path(
                'views/frontend/jackpot-proofs/index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/jackpot-proofs/show.blade.php',
            ),
        );

        $this->assertStringContainsString(
            '<img',
            $slot,
        );

        $this->assertStringContainsString(
            '<img',
            $index,
        );

        $this->assertStringContainsString(
            '<img',
            $show,
        );
    }

    public function test_special_module_theme_css_exists(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-special-modules">',
            '[data-theme-special="slot-gacor"]',
            '[data-theme-special="jackpot-index"]',
            '[data-theme-special="jackpot-detail"]',
            '[data-theme-special="complaints"]',
            '[data-theme-complaint-status="success"]',
            '[data-theme-complaint-status="error"]',
            'var(--theme-success)',
            'var(--theme-danger)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }
}
