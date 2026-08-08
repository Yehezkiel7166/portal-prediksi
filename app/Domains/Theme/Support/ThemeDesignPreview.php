<?php

declare(strict_types=1);

namespace App\Domains\Theme\Support;

use Illuminate\Support\HtmlString;

final class ThemeDesignPreview
{
    /**
     * Minimum component opacity required for normal readability.
     *
     * @var array<string, int>
     */
    public const MINIMUM_OPACITY = [
        'solid' => 85,
        'semi-transparent' => 45,
        'glass' => 35,
        'outline' => 20,
    ];

    /**
     * @param  callable(string): mixed  $get
     */
    public function render(callable $get): HtmlString
    {
        return $this->renderFromState([
            'theme_preset' => $get('theme_preset'),
            'theme_background_mode' => $get('theme_background_mode'),
            'theme_background_image' => $get('theme_background_image'),
            'theme_background_size' => $get('theme_background_size'),
            'theme_background_position' => $get('theme_background_position'),
            'theme_overlay_enabled' => $get('theme_overlay_enabled'),
            'theme_overlay_color' => $get('theme_overlay_color'),
            'theme_overlay_opacity' => $get('theme_overlay_opacity'),
            'theme_component_style' => $get('theme_component_style'),
            'theme_component_opacity' => $get('theme_component_opacity'),
            'theme_component_blur' => $get('theme_component_blur'),
        ]);
    }

    /**
     * @param  array<string, mixed>  $state
     */
    public function renderFromState(array $state): HtmlString
    {
        $catalog = app(ThemePresetCatalog::class);

        $slug = (string) (
            $state['theme_preset']
            ?? 'gold-black-classic'
        );

        $preset = $catalog->find($slug);

        if ($preset === null) {
            $preset = $catalog->find('gold-black-classic');
        }

        if ($preset === null) {
            return new HtmlString(
                '<div>Preview theme tidak tersedia.</div>',
            );
        }

        $tokens = $preset['tokens'];
        $palette = $preset['palette'];

        $backgroundMode = (string) (
            $state['theme_background_mode']
            ?? 'theme'
        );

        $backgroundImage =
            $state['theme_background_image']
            ?? null;

        $backgroundSize = (string) (
            $state['theme_background_size']
            ?? 'cover'
        );

        $backgroundPosition = (string) (
            $state['theme_background_position']
            ?? 'center'
        );

        $overlayEnabled = (bool) (
            $state['theme_overlay_enabled']
            ?? false
        );

        $overlayColor = $this->safeColor(
            (string) (
                $state['theme_overlay_color']
                ?? '#000000'
            ),
            '#000000',
        );

        $overlayOpacity = $this->clampInteger(
            $state['theme_overlay_opacity']
            ?? 0,
            0,
            100,
        );

        $componentStyle = (string) (
            $state['theme_component_style']
            ?? $preset['appearance']['component_style']
            ?? 'solid'
        );

        if (
            ! array_key_exists(
                $componentStyle,
                self::MINIMUM_OPACITY,
            )
        ) {
            $componentStyle = 'solid';
        }

        $componentOpacity = $this->clampInteger(
            $state['theme_component_opacity']
            ?? 100,
            10,
            100,
        );

        $componentBlur = $this->clampInteger(
            $state['theme_component_blur']
            ?? 0,
            0,
            30,
        );

        $pageBackground = $this->safeColor(
            (string) (
                $tokens['page_bg']
                ?? $palette[0]
                ?? '#020617'
            ),
            '#020617',
        );

        $surface = $this->safeColor(
            (string) (
                $tokens['surface']
                ?? '#0F172A'
            ),
            '#0F172A',
        );

        $primary = $this->safeColor(
            (string) (
                $tokens['primary']
                ?? $palette[1]
                ?? '#D4AF37'
            ),
            '#D4AF37',
        );

        $accent = $this->safeColor(
            (string) (
                $tokens['accent']
                ?? $palette[2]
                ?? '#F5C542'
            ),
            '#F5C542',
        );

        $text = $this->safeColor(
            (string) (
                $tokens['text']
                ?? '#FFFFFF'
            ),
            '#FFFFFF',
        );

        $muted = $this->safeColor(
            (string) (
                $tokens['text_muted']
                ?? '#94A3B8'
            ),
            '#94A3B8',
        );

        $buttonText = $this->safeColor(
            (string) (
                $tokens['button_primary_text']
                ?? '#020617'
            ),
            '#020617',
        );

        /*
        |--------------------------------------------------------------------------
        | Theme background
        |--------------------------------------------------------------------------
        */

        $backgroundCss = $this->themeBackground(
            $preset,
            $pageBackground,
            $primary,
            $accent,
        );

        $backgroundStatus = 'Background template aktif.';

        if ($backgroundMode === 'image') {
            if (
                is_string($backgroundImage)
                && filled($backgroundImage)
            ) {
                $safePath = htmlspecialchars(
                    asset(
                        'storage/'.
                        ltrim($backgroundImage, '/'),
                    ),
                    ENT_QUOTES,
                );

                $safeSize = in_array(
                    $backgroundSize,
                    ['cover', 'contain', 'auto'],
                    true,
                )
                    ? $backgroundSize
                    : 'cover';

                $safePosition = in_array(
                    $backgroundPosition,
                    [
                        'center',
                        'top',
                        'bottom',
                        'left',
                        'right',
                    ],
                    true,
                )
                    ? $backgroundPosition
                    : 'center';

                $backgroundCss =
                    "url('{$safePath}') ".
                    "{$safePosition} / ".
                    "{$safeSize} no-repeat";

                $backgroundStatus =
                    'Background custom aktif.';
            } else {
                $backgroundStatus =
                    'Preview gambar tampil setelah upload selesai.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Overlay
        |--------------------------------------------------------------------------
        */

        $overlayCss = $overlayEnabled
            ? $this->hexToRgba(
                $overlayColor,
                $overlayOpacity / 100,
            )
            : 'rgba(0,0,0,0)';

        /*
        |--------------------------------------------------------------------------
        | Component appearance
        |--------------------------------------------------------------------------
        */

        $componentAlpha =
            $componentOpacity / 100;

        $componentBackground = match ($componentStyle) {
            'outline' => 'rgba(0,0,0,0.08)',

            default => $this->hexToRgba(
                $surface,
                $componentAlpha,
            ),
        };

        $borderWidth =
            $componentStyle === 'outline'
                ? '2px'
                : '1px';

        $backdrop =
            $componentStyle === 'glass'
                ? "backdrop-filter:blur({$componentBlur}px);".
                    "-webkit-backdrop-filter:blur({$componentBlur}px);"
                : '';

        /*
        |--------------------------------------------------------------------------
        | Readability
        |--------------------------------------------------------------------------
        */

        $warnings = $this->readabilityWarnings(
            backgroundMode: $backgroundMode,
            overlayEnabled: $overlayEnabled,
            componentStyle: $componentStyle,
            componentOpacity: $componentOpacity,
        );

        $statusHtml = $warnings === []
            ? <<<'HTML'
                <div style="
                    margin-top:12px;
                    padding:10px 12px;
                    border-radius:8px;
                    background:rgba(34,197,94,.12);
                    border:1px solid rgba(34,197,94,.35);
                    color:#86efac;
                    font-size:12px;
                    font-weight:700;
                ">
                    Keterbacaan preview: AMAN
                </div>
                HTML
            : '<div style="
                    margin-top:12px;
                    padding:10px 12px;
                    border-radius:8px;
                    background:rgba(245,158,11,.12);
                    border:1px solid rgba(245,158,11,.40);
                    color:#fcd34d;
                    font-size:12px;
                    line-height:1.55;
                ">'.
                    implode(
                        '<br>',
                        array_map(
                            static fn (
                                string $warning,
                            ): string => '• '.
                                htmlspecialchars(
                                    $warning,
                                    ENT_QUOTES,
                                ),
                            $warnings,
                        ),
                    ).
                '</div>';

        $presetName = htmlspecialchars(
            (string) $preset['name'],
            ENT_QUOTES,
        );

        $styleName = htmlspecialchars(
            ucwords(
                str_replace(
                    '-',
                    ' ',
                    $componentStyle,
                ),
            ),
            ENT_QUOTES,
        );

        $backgroundStatus = htmlspecialchars(
            $backgroundStatus,
            ENT_QUOTES,
        );

        return new HtmlString(
            <<<HTML
            <div
                data-theme-live-preview
                style="
                    position:relative;
                    overflow:hidden;
                    min-height:330px;
                    border-radius:14px;
                    border:1px solid {$primary};
                    background:{$backgroundCss};
                    box-shadow:0 18px 45px rgba(0,0,0,.22);
                "
            >
                <div style="
                    position:absolute;
                    inset:0;
                    background:{$overlayCss};
                    pointer-events:none;
                "></div>

                <div style="
                    position:relative;
                    z-index:1;
                    min-height:330px;
                    padding:20px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    gap:18px;
                ">
                    <div>
                        <div style="
                            display:flex;
                            flex-wrap:wrap;
                            gap:7px;
                            margin-bottom:14px;
                        ">
                            <span style="
                                width:28px;
                                height:28px;
                                border-radius:7px;
                                background:{$pageBackground};
                                border:1px solid rgba(255,255,255,.25);
                            "></span>

                            <span style="
                                width:28px;
                                height:28px;
                                border-radius:7px;
                                background:{$primary};
                                border:1px solid rgba(255,255,255,.25);
                            "></span>

                            <span style="
                                width:28px;
                                height:28px;
                                border-radius:7px;
                                background:{$accent};
                                border:1px solid rgba(255,255,255,.25);
                            "></span>
                        </div>

                        <div style="
                            max-width:520px;
                            padding:18px;
                            border:{$borderWidth} solid {$primary};
                            border-radius:12px;
                            background:{$componentBackground};
                            color:{$text};
                            {$backdrop}
                            box-shadow:0 14px 32px rgba(0,0,0,.16);
                        ">
                            <div style="
                                color:{$primary};
                                font-size:11px;
                                font-weight:800;
                                letter-spacing:.12em;
                                text-transform:uppercase;
                            ">
                                {$presetName}
                            </div>

                            <div style="
                                margin-top:7px;
                                font-size:21px;
                                line-height:1.2;
                                font-weight:900;
                            ">
                                Contoh Tampilan Website
                            </div>

                            <div style="
                                margin-top:7px;
                                color:{$muted};
                                font-size:13px;
                                line-height:1.55;
                            ">
                                Card, teks, border dan tombol mengikuti
                                theme aktif tanpa menghilangkan keterbacaan.
                            </div>

                            <div style="
                                margin-top:14px;
                                display:flex;
                                flex-wrap:wrap;
                                gap:8px;
                            ">
                                <span style="
                                    display:inline-flex;
                                    align-items:center;
                                    min-height:34px;
                                    padding:7px 14px;
                                    border-radius:8px;
                                    background:{$primary};
                                    color:{$buttonText};
                                    font-size:12px;
                                    font-weight:800;
                                ">
                                    Tombol Utama
                                </span>

                                <span style="
                                    display:inline-flex;
                                    align-items:center;
                                    min-height:34px;
                                    padding:7px 14px;
                                    border:1px solid {$accent};
                                    border-radius:8px;
                                    color:{$text};
                                    font-size:12px;
                                    font-weight:800;
                                ">
                                    Tombol Kedua
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="
                        display:flex;
                        flex-wrap:wrap;
                        gap:7px;
                    ">
                        <span style="
                            padding:6px 9px;
                            border-radius:7px;
                            background:rgba(0,0,0,.52);
                            color:#fff;
                            font-size:11px;
                        ">
                            {$styleName}
                        </span>

                        <span style="
                            padding:6px 9px;
                            border-radius:7px;
                            background:rgba(0,0,0,.52);
                            color:#fff;
                            font-size:11px;
                        ">
                            Opacity {$componentOpacity}%
                        </span>

                        <span style="
                            padding:6px 9px;
                            border-radius:7px;
                            background:rgba(0,0,0,.52);
                            color:#fff;
                            font-size:11px;
                        ">
                            Blur {$componentBlur}px
                        </span>

                        <span style="
                            padding:6px 9px;
                            border-radius:7px;
                            background:rgba(0,0,0,.52);
                            color:#fff;
                            font-size:11px;
                        ">
                            {$backgroundStatus}
                        </span>
                    </div>
                </div>
            </div>

            {$statusHtml}
            HTML,
        );
    }

    /**
     * @return array<int, string>
     */
    public function readabilityWarnings(
        string $backgroundMode,
        bool $overlayEnabled,
        string $componentStyle,
        int $componentOpacity,
    ): array {
        $warnings = [];

        $minimum =
            self::MINIMUM_OPACITY[$componentStyle]
            ?? 85;

        if ($componentOpacity < $minimum) {
            $warnings[] =
                "Opacity {$componentStyle} minimal {$minimum}% ".
                'agar isi tetap mudah dibaca.';
        }

        if (
            $backgroundMode === 'image'
            && ! $overlayEnabled
            && $componentOpacity < 55
        ) {
            $warnings[] =
                'Background gambar tanpa overlay membutuhkan '.
                'opacity komponen minimal 55%, atau aktifkan overlay.';
        }

        if (
            $backgroundMode === 'image'
            && ! $overlayEnabled
        ) {
            $warnings[] =
                'Untuk gambar yang ramai, Overlay disarankan agar '.
                'teks tetap konsisten pada semua halaman.';
        }

        return $warnings;
    }

    /**
     * @param  array<string, mixed>  $preset
     */
    private function themeBackground(
        array $preset,
        string $background,
        string $primary,
        string $accent,
    ): string {
        $gradient =
            $preset['preview']['gradient']
            ?? false;

        if ($gradient) {
            return 'linear-gradient(135deg, '.
                "{$background} 0%, ".
                "{$primary} 58%, ".
                "{$accent} 100%)";
        }

        return 'linear-gradient(135deg, '.
            "{$background} 0%, ".
            "{$background} 68%, ".
            "{$primary} 180%)";
    }

    private function safeColor(
        string $value,
        string $fallback,
    ): string {
        if (
            preg_match(
                '/^#[0-9A-Fa-f]{6}$/',
                $value,
            ) === 1
        ) {
            return strtoupper($value);
        }

        return $fallback;
    }

    private function hexToRgba(
        string $hex,
        float $alpha,
    ): string {
        $hex = ltrim($hex, '#');

        if (
            strlen($hex) !== 6
            || ! ctype_xdigit($hex)
        ) {
            $hex = '0F172A';
        }

        $red = hexdec(
            substr($hex, 0, 2),
        );

        $green = hexdec(
            substr($hex, 2, 2),
        );

        $blue = hexdec(
            substr($hex, 4, 2),
        );

        $alpha = max(
            0,
            min(1, $alpha),
        );

        return sprintf(
            'rgba(%d,%d,%d,%.2f)',
            $red,
            $green,
            $blue,
            $alpha,
        );
    }

    private function clampInteger(
        mixed $value,
        int $minimum,
        int $maximum,
    ): int {
        $value = is_numeric($value)
            ? (int) $value
            : $minimum;

        return max(
            $minimum,
            min(
                $maximum,
                $value,
            ),
        );
    }
}
