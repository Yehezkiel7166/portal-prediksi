<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Domains\Theme\Support\BrandThemeResolver;
use App\Domains\Theme\Support\ThemePresetCatalog;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

final class ThemeQaPreviewTest extends TestCase
{
    public function test_unsigned_preview_is_rejected(): void
    {
        $this->get(
            '/__theme-qa?preset=gold-black-classic',
        )->assertForbidden();
    }

    public function test_invalid_preset_is_rejected_even_with_valid_signature(): void
    {
        $url = URL::temporarySignedRoute(
            'theme-qa.home',
            now()->addMinutes(5),
            [
                'preset' => 'does-not-exist',
            ],
        );

        $this->get($url)
            ->assertNotFound();
    }

    public function test_signed_preview_does_not_write_to_database(): void
    {
        $writes = [];

        DB::listen(
            static function (
                QueryExecuted $query,
            ) use (&$writes): void {
                $sql = ltrim(
                    strtolower($query->sql),
                );

                if (
                    preg_match(
                        '/^(insert|update|delete|replace|alter|create|drop|truncate)\b/',
                        $sql,
                    ) === 1
                ) {
                    $writes[] = $query->sql;
                }
            },
        );

        $url = URL::temporarySignedRoute(
            'theme-qa.home',
            now()->addMinutes(5),
            [
                'preset' => 'white-gold-classic',
            ],
        );

        $response = $this->get($url);

        $response
            ->assertOk()
            ->assertHeader(
                'X-Robots-Tag',
                'noindex, nofollow, noarchive',
            );

        $cacheControl = (string) $response->headers->get(
            'Cache-Control',
        );

        foreach ([
            'private',
            'no-store',
            'no-cache',
            'must-revalidate',
            'max-age=0',
        ] as $directive) {
            $this->assertStringContainsString(
                $directive,
                $cacheControl,
            );
        }

        $this->assertSame(
            [],
            $writes,
            'Theme QA preview must remain database read-only.',
        );
    }

    public function test_signed_preview_renders_light_preset_tokens(): void
    {
        $url = URL::temporarySignedRoute(
            'theme-qa.home',
            now()->addMinutes(5),
            [
                'preset' => 'white-gold-classic',
            ],
        );

        $response = $this->get($url);

        $response->assertOk();

        $response->assertSee(
            '--theme-page-bg:',
            false,
        );

        $response->assertSee(
            '#FFFFFF',
            false,
        );
    }

    public function test_signed_preview_renders_gradient_background(): void
    {
        $url = URL::temporarySignedRoute(
            'theme-qa.home',
            now()->addMinutes(5),
            [
                'preset' => 'gold-black-gradient',
            ],
        );

        $response = $this->get($url);

        $response->assertOk();

        $response->assertSee(
            'linear-gradient(',
            false,
        );
    }

    public function test_request_override_is_not_read_from_plain_query_parameter(): void
    {
        $request = request();

        $request->query->set(
            'preset',
            'white-gold-classic',
        );

        $resolved = app(
            BrandThemeResolver::class,
        )->resolve();

        $this->assertNotSame(
            'white-gold-classic',
            $resolved['slug'] ?? null,
        );
    }

    public function test_representative_presets_exist(): void
    {
        $catalog = app(
            ThemePresetCatalog::class,
        );

        foreach ([
            'gold-black-classic',
            'white-gold-classic',
            'gold-black-gradient',
            'gold-black-glass',
            'gold-black-contrast',
        ] as $slug) {
            $this->assertNotNull(
                $catalog->find($slug),
                $slug,
            );
        }
    }

    public function test_all_four_qa_routes_exist(): void
    {
        foreach ([
            'theme-qa.home',
            'theme-qa.results',
            'theme-qa.predictions',
            'theme-qa.live-draw',
        ] as $routeName) {
            $this->assertTrue(
                app('router')
                    ->getRoutes()
                    ->hasNamedRoute($routeName),
                $routeName,
            );
        }
    }
}
