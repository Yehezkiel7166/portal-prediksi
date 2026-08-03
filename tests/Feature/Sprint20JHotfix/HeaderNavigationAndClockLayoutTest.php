<?php

declare(strict_types=1);

namespace Tests\Feature\Sprint20JHotfix;

use Tests\TestCase;

final class HeaderNavigationAndClockLayoutTest extends TestCase
{
    private string $source;

    protected function setUp(): void
    {
        parent::setUp();

        $source = file_get_contents(
            resource_path(
                'views/frontend/partials/header.blade.php'
            )
        );

        $this->assertIsString($source);

        $this->source = $source;
    }

    public function test_site_identity_remains_accessible_but_hidden(): void
    {
        $this->assertStringContainsString(
            'class="sr-only"',
            $this->source,
        );

        $this->assertStringContainsString(
            '{{ $siteConfiguration->siteName }}',
            $this->source,
        );

        $this->assertStringContainsString(
            '{{ $siteConfiguration->tagline }}',
            $this->source,
        );

        $this->assertStringNotContainsString(
            'class="block text-xs font-normal tracking-normal text-slate-400"',
            $this->source,
        );
    }

    public function test_navigation_has_required_labels_and_order(): void
    {
        $labels = [
            'Home',
            'LiveDraw',
            'Prediksi',
            'Slot Gacor',
            'Result',
            'Alat Togel',
            'Bukti Jackpot',
            'Panduan',
            'Keluhan',
        ];

        $positions = [];

        foreach ($labels as $label) {
            $matched = preg_match(
                '/>\s*' . preg_quote($label, '/') . '\s*</',
                $this->source,
                $matches,
                PREG_OFFSET_CAPTURE,
            );

            $this->assertSame(
                1,
                $matched,
                sprintf(
                    'Navigation label [%s] was not found.',
                    $label,
                ),
            );

            $positions[$label] = $matches[0][1];
        }

        $sorted = $positions;
        asort($sorted);

        $this->assertSame(
            $labels,
            array_keys($sorted),
        );
    }

    public function test_navigation_uses_existing_frontend_routes(): void
    {
        $routes = [
            "route('home')",
            "route('live-draw.index')",
            "route('predictions.index')",
            "route('slot-gacor.index')",
            "route('results.index')",
            "route('tools.lottery-schedule')",
            "route('jackpot-proofs.index')",
            "route('guides.index')",
            "route('complaints.create')",
        ];

        foreach ($routes as $route) {
            $this->assertStringContainsString(
                $route,
                $this->source,
            );
        }
    }

    public function test_clock_is_after_navigation_and_aligned_right(): void
    {
        $navigationPosition = strpos(
            $this->source,
            '<nav'
        );

        $clockPosition = strpos(
            $this->source,
            'data-live-clock'
        );

        $this->assertNotFalse($navigationPosition);
        $this->assertNotFalse($clockPosition);

        $this->assertGreaterThan(
            $navigationPosition,
            $clockPosition,
        );

        $this->assertStringContainsString(
            'lg:ml-auto',
            $this->source,
        );
    }

    public function test_clock_has_required_jakarta_configuration_and_format(): void
    {
        $this->assertStringContainsString(
            "new Intl.DateTimeFormat('id-ID'",
            $this->source,
        );

        $this->assertStringContainsString(
            "timeZone: 'Asia/Jakarta'",
            $this->source,
        );

        $this->assertStringContainsString(
            "weekday: 'long'",
            $this->source,
        );

        $this->assertStringContainsString(
            "month: 'short'",
            $this->source,
        );

        $this->assertStringContainsString(
            "second: '2-digit'",
            $this->source,
        );

        $this->assertStringContainsString(
            'timeFormatter.formatToParts(now)',
            $this->source,
        );

        $this->assertStringContainsString(
            "].join(':');",
            $this->source,
        );

        $this->assertStringContainsString(
            'clock.textContent = `${dateText} ( ${timeText} )`;',
            $this->source,
        );

        $this->assertStringContainsString(
            'window.setInterval(renderClock, 1000);',
            $this->source,
        );
    }

    public function test_obsolete_visible_labels_are_removed(): void
    {
        $this->assertStringNotContainsString(
            'Prediksi Togel',
            $this->source,
        );

        $this->assertStringNotContainsString(
            'Data Result',
            $this->source,
        );

        $normalized = preg_replace(
            '/\s+/',
            ' ',
            $this->source,
        );

        $this->assertIsString($normalized);

        $this->assertStringNotContainsString(
            '> Live Draw <',
            $normalized,
        );
    }
}
