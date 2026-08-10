@php
    $brandTheme = app(
        \App\Domains\Theme\Support\BrandThemeResolver::class
    )->resolve();

    $themeTokens =
        $brandTheme['tokens'] ?? [];

    $themeBackground =
        $brandTheme['background'] ?? [];

    $themeAppearance =
        $brandTheme['appearance'] ?? [];

    $themeBackgroundMode =
        $themeBackground['mode'] ?? 'theme';

    $themeBackgroundImage =
        $themeBackground['image'] ?? null;

    $themeOverlay =
        $themeBackground['overlay'] ?? [];

    $themeComponentStyle =
        $themeAppearance['component_style']
        ?? 'solid';

    $themeComponentOpacity =
        max(
            0.1,
            min(
                1,
                (float) (
                    $themeAppearance['component_opacity']
                    ?? 1
                )
            )
        );

    $themeComponentOpacityPercent =
        (int) round(
            $themeComponentOpacity * 100
        );

    $themeComponentBlur =
        max(
            0,
            min(
                30,
                (int) (
                    $themeAppearance['component_blur']
                    ?? 0
                )
            )
        );
@endphp

<style id="brand-theme-tokens">
    :root {
        --theme-page-bg:
            {{ $themeTokens['page_bg'] ?? '#020617' }};

        --theme-surface:
            {{ $themeTokens['surface'] ?? '#0F172A' }};

        --theme-surface-alt:
            {{ $themeTokens['surface_alt'] ?? '#111827' }};

        --theme-surface-soft:
            {{ $themeTokens['surface_soft'] ?? 'rgba(15, 23, 42, 0.82)' }};

        --theme-primary:
            {{ $themeTokens['primary'] ?? '#D4AF37' }};

        --theme-secondary:
            {{ $themeTokens['secondary'] ?? '#F5C542' }};

        --theme-accent:
            {{ $themeTokens['accent'] ?? '#FACC15' }};

        --theme-text:
            {{ $themeTokens['text'] ?? '#FFFFFF' }};

        --theme-text-muted:
            {{ $themeTokens['text_muted'] ?? '#94A3B8' }};

        --theme-text-inverse:
            {{ $themeTokens['text_inverse'] ?? '#020617' }};

        --theme-border:
            {{ $themeTokens['border'] ?? '#334155' }};

        --theme-border-accent:
            {{ $themeTokens['border_accent'] ?? '#B8860B' }};

        --theme-button-primary-bg:
            {{ $themeTokens['button_primary_bg'] ?? '#D4AF37' }};

        --theme-button-primary-text:
            {{ $themeTokens['button_primary_text'] ?? '#020617' }};

        --theme-button-secondary-bg:
            {{ $themeTokens['button_secondary_bg'] ?? '#1E293B' }};

        --theme-button-secondary-text:
            {{ $themeTokens['button_secondary_text'] ?? '#FFFFFF' }};

        --theme-input-bg:
            {{ $themeTokens['input_bg'] ?? '#020617' }};

        --theme-input-text:
            {{ $themeTokens['input_text'] ?? '#FFFFFF' }};

        --theme-input-border:
            {{ $themeTokens['input_border'] ?? '#475569' }};

        --theme-table-header-bg:
            {{ $themeTokens['table_header_bg'] ?? '#111827' }};

        --theme-table-header-text:
            {{ $themeTokens['table_header_text'] ?? '#F5C542' }};

        --theme-result-bg:
            {{ $themeTokens['result_bg'] ?? '#020617' }};

        --theme-result-text:
            {{ $themeTokens['result_text'] ?? '#FFFFFF' }};

        --theme-result-border:
            {{ $themeTokens['result_border'] ?? '#D4AF37' }};

        --theme-success:
            {{ $themeTokens['success'] ?? '#22C55E' }};

        --theme-danger:
            {{ $themeTokens['danger'] ?? '#E11D48' }};

        --theme-warning:
            {{ $themeTokens['warning'] ?? '#F59E0B' }};

        --theme-info:
            {{ $themeTokens['info'] ?? '#22D3EE' }};

        --theme-header-bg:
            {{ $themeTokens['header_bg'] ?? '#020617' }};

        --theme-footer-bg:
            {{ $themeTokens['footer_bg'] ?? '#020617' }};

        --theme-glow:
            {{ $themeTokens['glow'] ?? '#D4AF37' }};

        --theme-shadow:
            {{ $themeTokens['shadow'] ?? 'rgba(0,0,0,.30)' }};

        --theme-component-opacity:
            {{ $themeComponentOpacity }};

        --theme-component-opacity-percent:
            {{ $themeComponentOpacityPercent }}%;

        --theme-component-blur:
            {{ $themeComponentBlur }}px;
    }

    html {
        --theme-slug:
            "{{ $brandTheme['slug'] ?? 'midnight-gold' }}";
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL PAGE SHELL
    |--------------------------------------------------------------------------
    */

    html,
    body {
        min-height: 100%;
        background-color:
            var(--theme-page-bg);
        color:
            var(--theme-text);
    }

    body[data-theme-root] {
        position: relative;
        isolation: isolate;

        background-color:
            var(--theme-page-bg);

        color:
            var(--theme-text);
    }

    body[data-theme-root] > header,
    body[data-theme-root] > main,
    body[data-theme-root] > footer {
        position: relative;
        z-index: 1;
    }

    body[data-theme-root] > main {
        min-height: 55vh;
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM BACKGROUND
    |--------------------------------------------------------------------------
    */

    @if (
        $themeBackgroundMode === 'image'
        && filled($themeBackgroundImage)
    )
        body[data-theme-root] {
            background-image:
                url('{{ asset('storage/'.$themeBackgroundImage) }}');

            background-size:
                {{ $themeBackground['size'] ?? 'cover' }};

            background-position:
                {{ $themeBackground['position'] ?? 'center' }};

            background-repeat:
                {{ ! empty($themeBackground['repeat'])
                    ? 'repeat'
                    : 'no-repeat' }};

            background-attachment:
                {{ ! empty($themeBackground['fixed'])
                    ? 'fixed'
                    : 'scroll' }};
        }
    @endif

    @if (! empty($themeOverlay['enabled']))
        body[data-theme-root]::before {
            content: "";

            position: fixed;
            inset: 0;

            z-index: 0;

            pointer-events: none;

            background:
                {{ $themeOverlay['color'] ?? '#000000' }};

            opacity:
                {{ max(
                    0,
                    min(
                        1,
                        (float) (
                            $themeOverlay['opacity']
                            ?? 0
                        )
                    )
                ) }};
        }
    @endif

    /*
    |--------------------------------------------------------------------------
    | GLOBAL THEMED COMPONENT HOOKS
    |--------------------------------------------------------------------------
    */

    [data-theme-surface] {
        color:
            var(--theme-text);

        border-color:
            var(--theme-border);
    }

    @if ($themeComponentStyle === 'solid')
        [data-theme-surface] {
            background:
                var(--theme-surface);
        }
    @elseif ($themeComponentStyle === 'semi-transparent')
        [data-theme-surface] {
            background:
                color-mix(
                    in srgb,
                    var(--theme-surface)
                    var(--theme-component-opacity-percent),
                    transparent
                );
        }
    @elseif ($themeComponentStyle === 'glass')
        [data-theme-surface] {
            background:
                color-mix(
                    in srgb,
                    var(--theme-surface)
                    var(--theme-component-opacity-percent),
                    transparent
                );

            backdrop-filter:
                blur(var(--theme-component-blur));

            -webkit-backdrop-filter:
                blur(var(--theme-component-blur));
        }
    @elseif ($themeComponentStyle === 'outline')
        [data-theme-surface] {
            background:
                color-mix(
                    in srgb,
                    var(--theme-surface)
                    12%,
                    transparent
                );

            border-width: 1px;
            border-style: solid;
        }
    @endif

    [data-theme-primary-button] {
        background:
            var(--theme-button-primary-bg);

        color:
            var(--theme-button-primary-text);

        border-color:
            var(--theme-border-accent);
    }

    [data-theme-secondary-button] {
        background:
            var(--theme-button-secondary-bg);

        color:
            var(--theme-button-secondary-text);

        border-color:
            var(--theme-border);
    }

    [data-theme-input] {
        background:
            var(--theme-input-bg);

        color:
            var(--theme-input-text);

        border-color:
            var(--theme-input-border);
    }

    [data-theme-muted] {
        color:
            var(--theme-text-muted);
    }

    [data-theme-accent] {
        color:
            var(--theme-primary);
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    [data-theme-header] {
        background:
            color-mix(
                in srgb,
                var(--theme-header-bg)
                96%,
                transparent
            );

        color:
            var(--theme-text);

        border-color:
            var(--theme-border-accent);
    }

    [data-theme-header] a,
    [data-theme-header] summary {
        color:
            var(--theme-text-muted);
    }

    [data-theme-header] a:hover,
    [data-theme-header] summary:hover,
    [data-theme-header] [aria-current="page"] {
        color:
            var(--theme-primary);
    }

    [data-theme-header-menu] {
        background:
            var(--theme-surface);

        color:
            var(--theme-text);

        border-color:
            var(--theme-border);

        box-shadow:
            0 18px 42px
            var(--theme-shadow);
    }

    [data-theme-clock] {
        background:
            var(--theme-surface-alt);

        color:
            var(--theme-primary);

        border-color:
            var(--theme-border-accent);
    }

    /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

    [data-theme-footer] {
        background:
            color-mix(
                in srgb,
                var(--theme-footer-bg)
                96%,
                transparent
            );

        color:
            var(--theme-text-muted);

        border-color:
            var(--theme-border);
    }

    [data-theme-footer] a {
        color:
            var(--theme-text-muted);
    }

    [data-theme-footer] a:hover {
        color:
            var(--theme-primary);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSIBILITY / SELECTION
    |--------------------------------------------------------------------------
    */

    body[data-theme-root] ::selection {
        background:
            var(--theme-primary);

        color:
            var(--theme-text-inverse);
    }

    body[data-theme-root] :focus-visible {
        outline:
            2px solid
            var(--theme-primary);

        outline-offset:
            2px;
    }
</style>

<style id="brand-theme-homepage">
    [data-theme-home-section] {
        position: relative;
        color: var(--theme-text);
    }

    [data-theme-home-section] + [data-theme-home-section] {
        border-top:
            1px solid
            color-mix(
                in srgb,
                var(--theme-border) 70%,
                transparent
            );
    }

    [data-theme-home-section] [data-theme-surface] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 82%,
                transparent
            );

        box-shadow:
            0 12px 28px
            color-mix(
                in srgb,
                var(--theme-shadow) 75%,
                transparent
            );

        transition:
            transform 160ms ease,
            border-color 160ms ease,
            box-shadow 160ms ease;
    }

    [data-theme-home-section] a[data-theme-surface]:hover {
        transform: translateY(-2px);
        border-color: var(--theme-border-accent);

        box-shadow:
            0 16px 34px
            color-mix(
                in srgb,
                var(--theme-shadow) 88%,
                transparent
            );
    }

    [data-theme-home-section] [data-theme-accent] {
        color: var(--theme-primary);
    }

    [data-theme-home-section] [data-theme-muted] {
        color: var(--theme-text-muted);
    }

    [data-theme-status] {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 80%,
                transparent
            );

        border-color: var(--theme-border);
        color: var(--theme-text-muted);
    }

    [data-theme-status="live"] {
        background:
            color-mix(
                in srgb,
                var(--theme-danger) 14%,
                transparent
            );

        border-color:
            color-mix(
                in srgb,
                var(--theme-danger) 45%,
                transparent
            );

        color: var(--theme-danger);
    }

    [data-theme-status="scheduled"] {
        background:
            color-mix(
                in srgb,
                var(--theme-warning) 14%,
                transparent
            );

        border-color:
            color-mix(
                in srgb,
                var(--theme-warning) 45%,
                transparent
            );

        color: var(--theme-warning);
    }

    @media (max-width: 767px) {
        [data-theme-home-section] [data-theme-surface] {
            border-radius: 0.75rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-theme-home-section] [data-theme-surface] {
            transition: none;
        }

        [data-theme-home-section] a[data-theme-surface]:hover {
            transform: none;
        }
    }
</style>

<style id="brand-theme-results">
    /*
    |--------------------------------------------------------------------------
    | RESULT MODULE
    |--------------------------------------------------------------------------
    */

    [data-theme-result-hero],
    [data-theme-result-section] {
        position: relative;
        color: var(--theme-text);
    }

    [data-theme-result-hero] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 75%,
                transparent
            );
    }

    [data-theme-result-card],
    [data-theme-result-history-card],
    [data-theme-result-detail] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 85%,
                transparent
            );

        box-shadow:
            0 16px 36px
            color-mix(
                in srgb,
                var(--theme-shadow) 72%,
                transparent
            );
    }

    [data-theme-result-card] {
        transition:
            transform 160ms ease,
            border-color 160ms ease,
            box-shadow 160ms ease;
    }

    [data-theme-result-card]:hover {
        transform: translateY(-3px);

        border-color:
            var(--theme-border-accent);

        box-shadow:
            0 20px 42px
            color-mix(
                in srgb,
                var(--theme-shadow) 88%,
                transparent
            );
    }

    [data-theme-result-number-panel] {
        background:
            color-mix(
                in srgb,
                var(--theme-result-bg) 82%,
                transparent
            );

        border-color:
            color-mix(
                in srgb,
                var(--theme-result-border) 55%,
                transparent
            );
    }

    [data-theme-result-number] {
        color:
            var(--theme-result-text);

        text-shadow:
            0 0 22px
            color-mix(
                in srgb,
                var(--theme-glow) 30%,
                transparent
            );
    }

    [data-theme-result-detail-header],
    [data-theme-result-notes] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 78%,
                transparent
            );
    }

    [data-theme-result-meta],
    [data-theme-result-market-code] {
        border-color:
            var(--theme-border);

        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 72%,
                transparent
            );
    }

    [data-theme-market-status] {
        border-color:
            var(--theme-border);

        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 80%,
                transparent
            );

        color:
            var(--theme-text-muted);
    }

    [data-theme-market-status="open"] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-success) 48%,
                transparent
            );

        background:
            color-mix(
                in srgb,
                var(--theme-success) 12%,
                transparent
            );

        color:
            var(--theme-success);
    }

    [data-theme-market-status="closed"] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-danger) 48%,
                transparent
            );

        background:
            color-mix(
                in srgb,
                var(--theme-danger) 12%,
                transparent
            );

        color:
            var(--theme-danger);
    }

    [data-theme-market-status="holiday"] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-warning) 48%,
                transparent
            );

        background:
            color-mix(
                in srgb,
                var(--theme-warning) 12%,
                transparent
            );

        color:
            var(--theme-warning);
    }

    @media (max-width: 639px) {
        [data-theme-result-grid] {
            grid-template-columns:
                minmax(0, 1fr);
        }

        [data-theme-result-card],
        [data-theme-result-history-card],
        [data-theme-result-detail] {
            border-radius: 0.875rem;
        }

        [data-theme-result-number] {
            letter-spacing: 0.08em;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-theme-result-card] {
            transition: none;
        }

        [data-theme-result-card]:hover {
            transform: none;
        }
    }
</style>
