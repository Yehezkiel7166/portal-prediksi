<?php

declare(strict_types=1);

namespace Tests\Feature\LotteryTools;

use App\Domains\Converter\Support\SgpNumberConverter;
use InvalidArgumentException;
use Tests\TestCase;

final class SgpNumberConverterTest extends TestCase
{
    public function test_reference_formula_returns_7907(): void
    {
        $converter = new SgpNumberConverter;

        $this->assertSame(
            '7907',
            $converter->convert([
                5,
                7,
                30,
                33,
                36,
                46,
                44,
            ]),
        );
    }

    public function test_formula_components_match_reference(): void
    {
        [
            $b1,
            $b2,
            $b3,
            $b4,
            $b5,
            $b6,
            $b7,
        ] = [
            5,
            7,
            30,
            33,
            36,
            46,
            44,
        ];

        $asRaw = $b2 + $b3;
        $kopRaw = $b4 + $b5;

        $kepalaEkorRaw = (
            (
                (
                    $b1
                    + $b2
                    + $b3
                    + $b4
                    + $b5
                    + $b6
                ) * 2
            )
            - $b1
            - $b6
        ) + $b7;

        $this->assertSame(37, $asRaw);
        $this->assertSame(69, $kopRaw);
        $this->assertSame(307, $kepalaEkorRaw);
    }

    public function test_leading_zero_is_preserved(): void
    {
        $result = (new SgpNumberConverter)->convert([
            0,
            0,
            0,
            0,
            0,
            0,
            0,
        ]);

        $this->assertSame('0000', $result);
        $this->assertSame(4, strlen($result));
    }

    public function test_exactly_seven_numbers_are_required(): void
    {
        $this->expectException(
            InvalidArgumentException::class,
        );

        (new SgpNumberConverter)->convert([
            1,
            2,
            3,
        ]);
    }

    public function test_negative_numbers_are_rejected(): void
    {
        $this->expectException(
            InvalidArgumentException::class,
        );

        (new SgpNumberConverter)->convert([
            1,
            2,
            3,
            4,
            5,
            6,
            -7,
        ]);
    }

    public function test_create_route_remains_available(): void
    {
        $this->get(
            route('tools.sgp-converter.create'),
        )
            ->assertOk()
            ->assertSee('Konversi SGP TOTO')
            ->assertSee(
                'data-sgp-converter',
                false,
            )
            ->assertSee(
                'data-sgp-inputs',
                false,
            )
            ->assertSee(
                'data-sgp-result',
                false,
            );
    }

    public function test_store_route_returns_reference_result(): void
    {
        $this->post(
            route('tools.sgp-converter.store'),
            [
                'balls' => [
                    5,
                    7,
                    30,
                    33,
                    36,
                    46,
                    44,
                ],
            ],
        )
            ->assertOk()
            ->assertSee('7907');
    }

    public function test_store_requires_exactly_seven_numbers(): void
    {
        $this->from(
            route('tools.sgp-converter.create'),
        )
            ->post(
                route('tools.sgp-converter.store'),
                [
                    'balls' => [
                        1,
                        2,
                        3,
                    ],
                ],
            )
            ->assertRedirect(
                route('tools.sgp-converter.create'),
            )
            ->assertSessionHasErrors('balls');
    }

    public function test_view_has_exactly_seven_unlabelled_cells(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        $this->assertStringContainsString(
            '@foreach (range(0, 6) as $index)',
            $view,
        );

        $this->assertStringContainsString(
            'name="balls[]"',
            $view,
        );

        $this->assertStringNotContainsString(
            'BOLA 1',
            $view,
        );

        $this->assertStringNotContainsString(
            'BOLA 2',
            $view,
        );
    }

    public function test_desktop_inputs_are_compact_and_horizontal(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        foreach ([
            'grid-template-columns: repeat(7, 64px)',
            'width: 64px',
            'height: 44px',
            'width: min(100%, 660px)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_mobile_inputs_use_four_plus_three_layout(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        foreach ([
            '@media (max-width: 639px)',
            'grid-template-columns: repeat(4, 58px)',
            'width: 58px',
            'height: 42px',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_result_panel_is_compact(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        $this->assertStringContainsString(
            'width: min(100%, 360px)',
            $view,
        );

        $this->assertStringContainsString(
            'min-height: 66px',
            $view,
        );
    }

    public function test_view_is_ready_for_theme_engine(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        foreach ([
            '--sgp-page-bg',
            '--sgp-surface',
            '--sgp-primary',
            '--sgp-danger',
            '--sgp-text',
            '--sgp-muted',
            '--sgp-border',
            'data-theme-surface',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}
