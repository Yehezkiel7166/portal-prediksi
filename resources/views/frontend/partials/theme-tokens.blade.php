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

    $themeGradient =
        $themeBackground['theme_gradient'] ?? [];

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

        /*
        | Page foreground is independent from surface foreground.
        | Used for content rendered directly over page backgrounds.
        */
        --theme-page-foreground:
            var(--theme-text);

        --theme-page-muted:
            color-mix(
                in srgb,
                var(--theme-text) 88%,
                var(--theme-page-bg)
            );

        --theme-page-accent:
            color-mix(
                in srgb,
                var(--theme-text) 92%,
                var(--theme-primary)
            );

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
    | THEME BACKGROUND
    |--------------------------------------------------------------------------
    */

    @if (
        $themeBackgroundMode === 'theme'
        && is_array($themeGradient)
        && count($themeGradient) >= 3
    )
        body[data-theme-root] {
            background-image:
                linear-gradient(
                    135deg,
                    {{ $themeGradient[0] }} 0%,
                    {{ $themeGradient[1] }} 55%,
                    {{ $themeGradient[2] }} 100%
                );

            background-attachment:
                fixed;
        }
    @endif

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
        color: var(--theme-page-foreground);
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
        color: var(--theme-page-accent);
    }

    [data-theme-home-section] [data-theme-muted] {
        color: var(--theme-page-muted);
    }

    [data-theme-home-section] [data-theme-surface] [data-theme-accent] {
        color: var(--theme-primary);
    }

    [data-theme-home-section] [data-theme-surface] [data-theme-muted] {
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

<style id="brand-theme-core-data">
    /*
    |--------------------------------------------------------------------------
    | CORE DATA MODULES
    |--------------------------------------------------------------------------
    | Predictions + Prediction Detail + Live Draw
    |--------------------------------------------------------------------------
    */

    [data-theme-module="predictions"],
    [data-theme-module="prediction-detail"],
    [data-theme-module="live-draw"] {
        position: relative;

        color:
            var(--theme-page-foreground);

        background:
            transparent !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 76%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC LEGACY PALETTE -> THEME TOKENS
    |--------------------------------------------------------------------------
    |
    | Existing Tailwind classes remain in markup for compatibility.
    | Within these scoped modules Theme Engine becomes authoritative.
    |
    */

    [data-theme-module="predictions"] .bg-slate-900,
    [data-theme-module="predictions"] .bg-slate-950,
    [data-theme-module="prediction-detail"] .bg-slate-900,
    [data-theme-module="prediction-detail"] .bg-slate-950,
    [data-theme-module="live-draw"] .bg-slate-900,
    [data-theme-module="live-draw"] .bg-slate-950 {
        background:
            var(--theme-surface) !important;
    }

    [data-theme-module="predictions"] .bg-slate-950\/60,
    [data-theme-module="prediction-detail"] .bg-slate-950\/60 {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 60%,
                transparent
            ) !important;
    }

    [data-theme-module="predictions"] .text-white,
    [data-theme-module="predictions"] .text-slate-100,
    [data-theme-module="predictions"] .text-slate-200,
    [data-theme-module="prediction-detail"] .text-white,
    [data-theme-module="prediction-detail"] .text-slate-100,
    [data-theme-module="prediction-detail"] .text-slate-200,
    [data-theme-module="live-draw"] .text-white,
    [data-theme-module="live-draw"] .text-slate-100,
    [data-theme-module="live-draw"] .text-slate-200 {
        color:
            var(--theme-text) !important;
    }

    [data-theme-module="predictions"] .text-slate-300,
    [data-theme-module="predictions"] .text-slate-400,
    [data-theme-module="predictions"] .text-slate-500,
    [data-theme-module="prediction-detail"] .text-slate-300,
    [data-theme-module="prediction-detail"] .text-slate-400,
    [data-theme-module="prediction-detail"] .text-slate-500,
    [data-theme-module="live-draw"] .text-slate-300,
    [data-theme-module="live-draw"] .text-slate-400,
    [data-theme-module="live-draw"] .text-slate-500 {
        color:
            var(--theme-text-muted) !important;
    }

    [data-theme-module="predictions"] .text-amber-300,
    [data-theme-module="predictions"] .text-amber-400,
    [data-theme-module="prediction-detail"] .text-amber-300,
    [data-theme-module="prediction-detail"] .text-amber-400,
    [data-theme-module="live-draw"] .text-amber-300,
    [data-theme-module="live-draw"] .text-amber-400 {
        color:
            var(--theme-primary) !important;
    }
    /*
    |--------------------------------------------------------------------------
    | Direct page foreground
    |--------------------------------------------------------------------------
    |
    | Legacy text-slate/text-amber mappings remain surface-oriented.
    | These hooks apply only to text rendered directly over page backgrounds.
    |
    */

    [data-theme-module="predictions"] [data-theme-direct-page-muted],
    [data-theme-module="prediction-detail"] [data-theme-direct-page-muted],
    [data-theme-module="live-draw"] [data-theme-direct-page-muted] {
        color: var(--theme-page-muted) !important;
    }

    [data-theme-module="predictions"] [data-theme-direct-page-accent],
    [data-theme-module="prediction-detail"] [data-theme-direct-page-accent],
    [data-theme-module="live-draw"] [data-theme-direct-page-accent] {
        color: var(--theme-page-accent) !important;
    }

    [data-theme-module="predictions"] .border-slate-600,
    [data-theme-module="predictions"] .border-slate-700,
    [data-theme-module="predictions"] .border-slate-800,
    [data-theme-module="prediction-detail"] .border-slate-600,
    [data-theme-module="prediction-detail"] .border-slate-700,
    [data-theme-module="prediction-detail"] .border-slate-800,
    [data-theme-module="live-draw"] .border-slate-600,
    [data-theme-module="live-draw"] .border-slate-700,
    [data-theme-module="live-draw"] .border-slate-800 {
        border-color:
            var(--theme-border) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | PREDICTION SURFACE
    |--------------------------------------------------------------------------
    */

    [data-theme-module="predictions"] [data-theme-surface],
    [data-theme-module="prediction-detail"] [data-theme-surface] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 84%,
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

    /*
    |--------------------------------------------------------------------------
    | INPUTS
    |--------------------------------------------------------------------------
    */

    [data-theme-module="predictions"] select,
    [data-theme-module="predictions"] input,
    [data-theme-module="predictions"] [data-datepicker-trigger] {
        background:
            var(--theme-input-bg) !important;

        color:
            var(--theme-input-text) !important;

        border-color:
            var(--theme-input-border) !important;
    }

    [data-theme-module="predictions"] select:focus,
    [data-theme-module="predictions"] input:focus,
    [data-theme-module="predictions"] [data-datepicker-trigger]:focus {
        border-color:
            var(--theme-primary) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | DATE PICKER
    |--------------------------------------------------------------------------
    */

    [data-theme-datepicker] [data-datepicker-panel] {
        background:
            var(--theme-surface) !important;

        color:
            var(--theme-text) !important;

        border-color:
            var(--theme-border) !important;

        box-shadow:
            0 24px 60px
            var(--theme-shadow) !important;
    }

    [data-theme-datepicker] [data-datepicker-prev],
    [data-theme-datepicker] [data-datepicker-next] {
        color:
            var(--theme-text);

        border-color:
            var(--theme-border) !important;
    }

    [data-theme-datepicker] [data-datepicker-prev]:hover,
    [data-theme-datepicker] [data-datepicker-next]:hover {
        color:
            var(--theme-primary) !important;

        border-color:
            var(--theme-primary) !important;
    }

    [data-theme-datepicker] [data-datepicker-clear],
    [data-theme-datepicker] [data-datepicker-today] {
        color:
            var(--theme-primary) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | PREDICTION NUMBER PANELS
    |--------------------------------------------------------------------------
    */

    [data-theme-module="predictions"] .border-amber-400\/20,
    [data-theme-module="prediction-detail"] .border-amber-400\/20 {
        border-color:
            color-mix(
                in srgb,
                var(--theme-result-border) 45%,
                transparent
            ) !important;
    }

    [data-theme-module="predictions"] .border-b,
    [data-theme-module="prediction-detail"] .border-b,
    [data-theme-module="prediction-detail"] .border-t {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 78%,
                transparent
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PRIMARY / SECONDARY ACTIONS
    |--------------------------------------------------------------------------
    */

    [data-theme-module="predictions"] button[type="submit"] {
        background:
            var(--theme-button-primary-bg) !important;

        color:
            var(--theme-button-primary-text) !important;
    }

    [data-theme-module="predictions"] a.border-amber-400 {
        background:
            var(--theme-button-secondary-bg);

        color:
            var(--theme-button-secondary-text) !important;

        border-color:
            var(--theme-border-accent) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | ERROR / SEMANTIC STATES
    |--------------------------------------------------------------------------
    */

    [data-theme-module="predictions"] [role="alert"] {
        background:
            color-mix(
                in srgb,
                var(--theme-danger) 12%,
                transparent
            ) !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-danger) 45%,
                transparent
            ) !important;

        color:
            var(--theme-danger) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | LIVE DRAW CARDS
    |--------------------------------------------------------------------------
    */

    [data-theme-live-card] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 84%,
                transparent
            ) !important;

        box-shadow:
            0 16px 36px
            color-mix(
                in srgb,
                var(--theme-shadow) 72%,
                transparent
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Do NOT recolor Live Draw supplied media.
    |--------------------------------------------------------------------------
    */

    [data-theme-module="live-draw"] iframe,
    [data-theme-module="live-draw"] video,
    [data-theme-module="live-draw"] img {
        color-scheme:
            normal;
    }

    /*
    |--------------------------------------------------------------------------
    | LIVE STATUS
    |--------------------------------------------------------------------------
    */

    [data-theme-live-status] {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 80%,
                transparent
            );

        border-color:
            var(--theme-border);

        color:
            var(--theme-text-muted);
    }

    [data-theme-live-status="live"] {
        background:
            color-mix(
                in srgb,
                var(--theme-danger) 14%,
                transparent
            );

        border-color:
            color-mix(
                in srgb,
                var(--theme-danger) 48%,
                transparent
            );

        color:
            var(--theme-danger);
    }

    [data-theme-live-status="scheduled"] {
        background:
            color-mix(
                in srgb,
                var(--theme-warning) 14%,
                transparent
            );

        border-color:
            color-mix(
                in srgb,
                var(--theme-warning) 48%,
                transparent
            );

        color:
            var(--theme-warning);
    }

    [data-theme-live-status="finished"] {
        background:
            color-mix(
                in srgb,
                var(--theme-success) 14%,
                transparent
            );

        border-color:
            color-mix(
                in srgb,
                var(--theme-success) 48%,
                transparent
            );

        color:
            var(--theme-success);
    }

    /*
    |--------------------------------------------------------------------------
    | LIVE DRAW RESULT
    |--------------------------------------------------------------------------
    */

    [data-theme-module="live-draw"] .border-sky-400\/30,
    [data-theme-module="live-draw"] .border-sky-400\/20 {
        border-color:
            color-mix(
                in srgb,
                var(--theme-info) 45%,
                transparent
            ) !important;
    }

    [data-theme-module="live-draw"] .bg-sky-950\/20 {
        background:
            color-mix(
                in srgb,
                var(--theme-info) 10%,
                transparent
            ) !important;
    }

    [data-theme-module="live-draw"] .text-sky-300,
    [data-theme-module="live-draw"] .text-sky-200 {
        color:
            var(--theme-info) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | LIVE DRAW ALERTS
    |--------------------------------------------------------------------------
    */

    [data-theme-module="live-draw"] .bg-amber-950\/20,
    [data-theme-module="live-draw"] .bg-amber-950\/30 {
        background:
            color-mix(
                in srgb,
                var(--theme-warning) 10%,
                transparent
            ) !important;
    }

    [data-theme-module="live-draw"] .text-amber-200 {
        color:
            var(--theme-warning) !important;
    }

    [data-theme-module="live-draw"] .bg-emerald-950\/20 {
        background:
            color-mix(
                in srgb,
                var(--theme-success) 10%,
                transparent
            ) !important;
    }

    [data-theme-module="live-draw"] .text-emerald-200,
    [data-theme-module="live-draw"] .text-emerald-300 {
        color:
            var(--theme-success) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {
        [data-theme-module="predictions"] [data-theme-surface],
        [data-theme-module="prediction-detail"] [data-theme-surface],
        [data-theme-live-card] {
            border-radius:
                0.875rem;
        }

        [data-theme-datepicker] [data-datepicker-panel] {
            min-width:
                min(320px, calc(100vw - 2rem)) !important;
        }
    }
</style>

<style id="brand-theme-lottery-tools">
    /*
    |--------------------------------------------------------------------------
    | ALL LOTTERY TOOLS
    |--------------------------------------------------------------------------
    */

    [data-theme-tool] {
        position:
            relative;

        color:
            var(--theme-text);

        background:
            transparent !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 76%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC LEGACY PALETTE
    |--------------------------------------------------------------------------
    */

    [data-theme-tool] .bg-slate-900,
    [data-theme-tool] .bg-slate-950,
    [data-theme-tool] .bg-slate-800 {
        background:
            var(--theme-surface) !important;
    }

    [data-theme-tool] .bg-slate-900\/80,
    [data-theme-tool] .bg-slate-950\/60,
    [data-theme-tool] .bg-slate-950\/40 {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 65%,
                transparent
            ) !important;
    }

    [data-theme-tool] .text-white,
    [data-theme-tool] .text-slate-100,
    [data-theme-tool] .text-slate-200 {
        color:
            var(--theme-text) !important;
    }

    [data-theme-tool] .text-slate-300,
    [data-theme-tool] .text-slate-400,
    [data-theme-tool] .text-slate-500,
    [data-theme-tool] .text-slate-600,
    [data-theme-tool] .text-slate-700 {
        color:
            var(--theme-text-muted) !important;
    }

    [data-theme-tool] .text-amber-300,
    [data-theme-tool] .text-amber-400 {
        color:
            var(--theme-primary) !important;
    }

    [data-theme-tool] .border-slate-500,
    [data-theme-tool] .border-slate-600,
    [data-theme-tool] .border-slate-700,
    [data-theme-tool] .border-slate-800 {
        border-color:
            var(--theme-border) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | SURFACES
    |--------------------------------------------------------------------------
    */

    [data-theme-tool] [data-theme-surface] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 84%,
                transparent
            ) !important;

        box-shadow:
            0 14px 32px
            color-mix(
                in srgb,
                var(--theme-shadow) 70%,
                transparent
            );
    }

    /*
    |--------------------------------------------------------------------------
    | INPUTS / SELECTS
    |--------------------------------------------------------------------------
    */

    [data-theme-tool] input,
    [data-theme-tool] select,
    [data-theme-tool] textarea {
        background:
            var(--theme-input-bg) !important;

        color:
            var(--theme-input-text) !important;

        border-color:
            var(--theme-input-border) !important;
    }

    [data-theme-tool] input:focus,
    [data-theme-tool] select:focus,
    [data-theme-tool] textarea:focus {
        border-color:
            var(--theme-primary) !important;

        outline:
            none;
    }

    /*
    |--------------------------------------------------------------------------
    | STANDARD PRIMARY BUTTONS
    |--------------------------------------------------------------------------
    */

    [data-theme-tool] button[type="submit"] {
        background:
            var(--theme-button-primary-bg) !important;

        color:
            var(--theme-button-primary-text) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | DREAM BOOK
    |--------------------------------------------------------------------------
    */

    [data-theme-tool="dream-book-index"] .divide-slate-800 > :not([hidden]) ~ :not([hidden]) {
        border-color:
            var(--theme-border) !important;
    }

    [data-theme-tool="dream-book-index"] a.text-amber-400,
    [data-theme-tool="dream-book-detail"] a.text-amber-400 {
        color:
            var(--theme-primary) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | LOTTERY SCHEDULE
    |--------------------------------------------------------------------------
    */

    [data-theme-tool="lottery-schedule"] table {
        color:
            var(--theme-text);
    }

    [data-theme-tool="lottery-schedule"] thead {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 82%,
                transparent
            ) !important;
    }

    [data-theme-tool="lottery-schedule"] tbody tr {
        border-color:
            var(--theme-border);
    }

    [data-theme-schedule-status] {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 75%,
                transparent
            ) !important;

        border-color:
            var(--theme-border) !important;

        color:
            var(--theme-text-muted) !important;
    }

    [data-theme-schedule-status="open"] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-success) 48%,
                transparent
            ) !important;

        background:
            color-mix(
                in srgb,
                var(--theme-success) 12%,
                transparent
            ) !important;

        color:
            var(--theme-success) !important;
    }

    [data-theme-schedule-status="live"],
    [data-theme-schedule-status="result_available"],
    [data-theme-schedule-status="upcoming"] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-warning) 48%,
                transparent
            ) !important;

        background:
            color-mix(
                in srgb,
                var(--theme-warning) 12%,
                transparent
            ) !important;

        color:
            var(--theme-warning) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | PAITO
    |--------------------------------------------------------------------------
    |
    | Important:
    | User-selected paint colors remain authoritative.
    | Theme only controls shell/table chrome.
    |--------------------------------------------------------------------------
    */

    [data-theme-tool="paito"] [data-paito-weekly-grid] {
        border-color:
            var(--theme-border) !important;

        background:
            var(--theme-surface) !important;
    }

    [data-theme-tool="paito"] [data-paito-responsive-table] {
        background:
            var(--theme-surface) !important;
    }

    [data-theme-tool="paito"] [data-paito-weekday-header] {
        background:
            var(--theme-surface-alt) !important;

        color:
            var(--theme-primary) !important;
    }

    [data-theme-tool="paito"] [data-paito-weekday-group],
    [data-theme-tool="paito"] [data-paito-sum-header],
    [data-theme-tool="paito"] .paito-cell {
        border-color:
            var(--theme-border) !important;
    }

    /*
    | Do not override inline background-color of painted cells.
    */

    [data-theme-tool="paito"] .paito-cell[style*="background-color"] {
        background-color:
            revert-layer;
    }

    [data-theme-tool="paito"] [data-paito-market-required] {
        background:
            color-mix(
                in srgb,
                var(--theme-warning) 10%,
                transparent
            ) !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-warning) 45%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | PAITO SEMANTIC ACTIONS
    |--------------------------------------------------------------------------
    */

    [data-theme-tool="paito"] #auto-paint {
        background:
            var(--theme-success) !important;

        color:
            var(--theme-on-success, #ffffff) !important;
    }

    [data-theme-tool="paito"] #clear-all {
        border-color:
            var(--theme-danger) !important;

        color:
            var(--theme-danger) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | SHIO
    |--------------------------------------------------------------------------
    */

    [data-theme-tool="shio"] img {
        color-scheme:
            normal;
    }

    [data-theme-tool="shio"] article span {
        background:
            var(--theme-surface-alt) !important;

        color:
            var(--theme-text) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | BBFS RESULT
    |--------------------------------------------------------------------------
    */

    [data-theme-tool="bbfs"] .font-mono {
        background:
            color-mix(
                in srgb,
                var(--theme-result-bg) 82%,
                transparent
            ) !important;

        color:
            var(--theme-result-text) !important;

        border-color:
            var(--theme-result-border) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {
        [data-theme-tool] [data-theme-surface] {
            border-radius:
                0.875rem;
        }

        [data-theme-tool="lottery-schedule"] table {
            min-width:
                760px;
        }

        [data-theme-tool="paito"] [data-paito-responsive-scroll] {
            max-width:
                100%;
        }
    }
</style>

<style id="brand-theme-content-marketing">
    /*
    |--------------------------------------------------------------------------
    | CONTENT + MARKETING MODULES
    |--------------------------------------------------------------------------
    */

    [data-theme-content] {
        position:
            relative;

        color:
            var(--theme-text);

        background:
            transparent !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 76%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | LEGACY PALETTE -> THEME TOKENS
    |--------------------------------------------------------------------------
    */

    [data-theme-content] .bg-slate-900,
    [data-theme-content] .bg-slate-950 {
        background:
            var(--theme-surface) !important;
    }

    [data-theme-content] .bg-slate-950\/60 {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 60%,
                transparent
            ) !important;
    }

    [data-theme-content] .text-white,
    [data-theme-content] .text-slate-100,
    [data-theme-content] .text-slate-200 {
        color:
            var(--theme-text) !important;
    }

    [data-theme-content] .text-slate-300,
    [data-theme-content] .text-slate-400,
    [data-theme-content] .text-slate-500,
    [data-theme-content] .text-slate-600 {
        color:
            var(--theme-text-muted) !important;
    }

    [data-theme-content] .text-amber-300,
    [data-theme-content] .text-amber-400 {
        color:
            var(--theme-primary) !important;
    }

    [data-theme-content] .border-slate-700,
    [data-theme-content] .border-slate-800 {
        border-color:
            var(--theme-border) !important;
    }

    [data-theme-content] .border-amber-400 {
        border-color:
            var(--theme-border-accent) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | CARDS
    |--------------------------------------------------------------------------
    */

    [data-theme-content] [data-theme-surface] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 84%,
                transparent
            ) !important;

        box-shadow:
            0 14px 34px
            color-mix(
                in srgb,
                var(--theme-shadow) 70%,
                transparent
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENT LINKS / CTA
    |--------------------------------------------------------------------------
    */

    [data-theme-content] a.text-amber-400,
    [data-theme-content] a.border-amber-400 {
        color:
            var(--theme-primary) !important;
    }

    [data-theme-content] a.border-amber-400 {
        background:
            var(--theme-button-secondary-bg);

        border-color:
            var(--theme-border-accent) !important;
    }

    [data-theme-content] a.border-amber-400:hover {
        background:
            var(--theme-button-primary-bg) !important;

        color:
            var(--theme-button-primary-text) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | RICH CONTENT
    |--------------------------------------------------------------------------
    */

    [data-theme-rich-content] {
        color:
            var(--theme-text);
    }

    [data-theme-rich-content] h1,
    [data-theme-rich-content] h2,
    [data-theme-rich-content] h3,
    [data-theme-rich-content] h4,
    [data-theme-rich-content] strong {
        color:
            var(--theme-text);
    }

    [data-theme-rich-content] a {
        color:
            var(--theme-primary);
    }

    [data-theme-rich-content] blockquote {
        color:
            var(--theme-text-muted);

        border-color:
            var(--theme-border-accent);
    }

    [data-theme-rich-content] code {
        background:
            var(--theme-surface-alt);

        color:
            var(--theme-primary);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA SAFETY
    |--------------------------------------------------------------------------
    */

    [data-theme-content] img,
    [data-theme-content] iframe {
        color-scheme:
            normal;
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA CONTAINERS
    |--------------------------------------------------------------------------
    */

    [data-theme-content] .aspect-\[16\/9\],
    [data-theme-content] .aspect-video {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 90%,
                #000000 10%
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY STATES
    |--------------------------------------------------------------------------
    */

    [data-theme-content] .border-dashed {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 75%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL FOOTERS
    |--------------------------------------------------------------------------
    */

    [data-theme-content] footer {
        border-color:
            var(--theme-border) !important;

        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 60%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {
        [data-theme-content] [data-theme-surface] {
            border-radius:
                0.875rem;
        }

        [data-theme-rich-content] {
            overflow-wrap:
                anywhere;
        }
    }
</style>

<style id="brand-theme-special-modules">
    /*
    |--------------------------------------------------------------------------
    | SPECIAL MODULES
    |--------------------------------------------------------------------------
    | Slot Gacor + Jackpot Proof + Complaints
    |--------------------------------------------------------------------------
    */

    [data-theme-special] {
        position:
            relative;

        color:
            var(--theme-text);

        background:
            transparent !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 76%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | LEGACY PALETTE
    |--------------------------------------------------------------------------
    */

    [data-theme-special] .bg-slate-900,
    [data-theme-special] .bg-slate-950 {
        background:
            var(--theme-surface) !important;
    }

    [data-theme-special] .text-white,
    [data-theme-special] .text-slate-100,
    [data-theme-special] .text-slate-200 {
        color:
            var(--theme-text) !important;
    }

    [data-theme-special] .text-slate-300,
    [data-theme-special] .text-slate-400,
    [data-theme-special] .text-slate-500,
    [data-theme-special] .text-slate-600 {
        color:
            var(--theme-text-muted) !important;
    }

    [data-theme-special] .text-amber-300,
    [data-theme-special] .text-amber-400 {
        color:
            var(--theme-primary) !important;
    }

    [data-theme-special] .border-slate-700,
    [data-theme-special] .border-slate-800 {
        border-color:
            var(--theme-border) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | SURFACES
    |--------------------------------------------------------------------------
    */

    [data-theme-special] [data-theme-surface] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 84%,
                transparent
            ) !important;

        box-shadow:
            0 14px 34px
            color-mix(
                in srgb,
                var(--theme-shadow) 70%,
                transparent
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SLOT GACOR
    |--------------------------------------------------------------------------
    */

    [data-theme-special="slot-gacor"] [data-theme-rtp-value] {
        color:
            var(--theme-success) !important;
    }

    [data-theme-special="slot-gacor"] img {
        color-scheme:
            normal;
    }

    /*
    |--------------------------------------------------------------------------
    | JACKPOT PROOF
    |--------------------------------------------------------------------------
    */

    [data-theme-special="jackpot-index"] img,
    [data-theme-special="jackpot-detail"] img {
        color-scheme:
            normal;
    }

    [data-theme-special="jackpot-index"] a:hover {
        color:
            var(--theme-primary);
    }

    [data-theme-special="jackpot-detail"] [data-theme-rich-content] {
        color:
            var(--theme-text-muted);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLAINT FORM
    |--------------------------------------------------------------------------
    */

    [data-theme-special="complaints"] input,
    [data-theme-special="complaints"] textarea {
        background:
            var(--theme-input-bg) !important;

        color:
            var(--theme-input-text) !important;

        border-color:
            var(--theme-input-border) !important;
    }

    [data-theme-special="complaints"] input:focus,
    [data-theme-special="complaints"] textarea:focus {
        border-color:
            var(--theme-primary) !important;

        outline:
            none;
    }

    [data-theme-special="complaints"] button[type="submit"] {
        background:
            var(--theme-button-primary-bg) !important;

        color:
            var(--theme-button-primary-text) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLAINT SEMANTIC STATES
    |--------------------------------------------------------------------------
    */

    [data-theme-complaint-status="success"] {
        background:
            color-mix(
                in srgb,
                var(--theme-success) 12%,
                transparent
            ) !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-success) 48%,
                transparent
            ) !important;

        color:
            var(--theme-success) !important;
    }

    [data-theme-complaint-status="error"] {
        background:
            color-mix(
                in srgb,
                var(--theme-danger) 12%,
                transparent
            ) !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-danger) 48%,
                transparent
            ) !important;

        color:
            var(--theme-danger) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA CONTAINERS
    |--------------------------------------------------------------------------
    */

    [data-theme-special] .aspect-\[4\/3\] {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 90%,
                #000000 10%
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {
        [data-theme-special] [data-theme-surface] {
            border-radius:
                0.875rem;
        }

        [data-theme-special="complaints"] form {
            padding:
                1.25rem;
        }
    }
</style>

<style id="brand-theme-homepage-banner">
    /*
    |--------------------------------------------------------------------------
    | HOMEPAGE BANNER
    |--------------------------------------------------------------------------
    |
    | Theme applies only to carousel chrome.
    | Uploaded desktop/mobile banner media remains authoritative.
    |
    */

    [data-theme-homepage-banner] {
        position:
            relative;

        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 78%,
                transparent
            ) !important;

        background:
            color-mix(
                in srgb,
                var(--theme-surface) 92%,
                transparent
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Never recolor banner media
    |--------------------------------------------------------------------------
    */

    [data-theme-homepage-banner] picture,
    [data-theme-homepage-banner] img {
        color-scheme:
            normal;
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation controls
    |--------------------------------------------------------------------------
    */

    [data-theme-banner-control] {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 82%,
                transparent
            ) !important;

        background:
            color-mix(
                in srgb,
                var(--theme-surface) 72%,
                transparent
            ) !important;

        color:
            var(--theme-text) !important;

        backdrop-filter:
            blur(
                max(
                    6px,
                    var(--theme-component-blur, 0px)
                )
            );
    }

    [data-theme-banner-control]:hover {
        background:
            color-mix(
                in srgb,
                var(--theme-surface-alt) 88%,
                transparent
            ) !important;

        border-color:
            var(--theme-border-accent) !important;
    }

    [data-theme-banner-control]:focus-visible {
        outline:
            2px solid var(--theme-primary);

        outline-offset:
            3px;

        box-shadow:
            0 0 0 3px
            color-mix(
                in srgb,
                var(--theme-primary) 22%,
                transparent
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Indicators
    |--------------------------------------------------------------------------
    */

    [data-theme-banner-indicator] {
        background:
            color-mix(
                in srgb,
                var(--theme-text) 42%,
                transparent
            ) !important;
    }

    [data-theme-banner-indicator][aria-current="true"] {
        background:
            var(--theme-primary) !important;
    }

    [data-theme-banner-indicator]:hover {
        background:
            color-mix(
                in srgb,
                var(--theme-primary) 72%,
                var(--theme-text) 28%
            ) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty-banner fallback
    |--------------------------------------------------------------------------
    */

    [data-theme-homepage-banner-fallback] {
        position:
            relative;

        color:
            var(--theme-text);

        background:
            transparent !important;

        border-color:
            color-mix(
                in srgb,
                var(--theme-border) 78%,
                transparent
            ) !important;
    }

    [data-theme-homepage-banner-fallback] .text-white {
        color:
            var(--theme-text) !important;
    }

    [data-theme-homepage-banner-fallback] .text-slate-300 {
        color:
            var(--theme-text-muted) !important;
    }

    [data-theme-homepage-banner-fallback] .text-amber-400 {
        color:
            var(--theme-primary) !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive control sizing
    |--------------------------------------------------------------------------
    */

    @media (max-width: 639px) {
        [data-theme-banner-control] {
            padding:
                0.625rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-theme-homepage-banner]
        [data-slider-slide] {
            transition-duration:
                0.01ms !important;
        }

        [data-theme-banner-indicator] {
            transition:
                none !important;
        }
    }
</style>

<style id="brand-theme-result-workspace">
    [data-theme-result-workspace] {
        width: 100%;
        max-width: 80rem;
        margin-inline: auto;
    }

    [data-theme-result-workspace]
    > [data-theme-result-hero],
    [data-theme-result-workspace]
    > [data-theme-result-section] {
        min-width: 0;
    }

    [data-theme-result-workspace]
    > [data-theme-result-hero]
    > div,
    [data-theme-result-workspace]
    > [data-theme-result-section]
    > div {
        width: 100%;
        max-width: none;
    }

    [data-theme-result-filter-panel],
    [data-theme-result-context-panel],
    [data-theme-result-detail-panel],
    [data-theme-result-master-panel],
    [data-theme-result-primary-panel] {
        min-width: 0;
    }

    [data-theme-result-master-detail] {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1rem;
        align-items: start;
    }

    [data-theme-result-master-list] {
        min-width: 0;
    }

    [data-theme-result-master-row] {
        min-width: 0;

        transition:
            background-color 160ms ease,
            border-color 160ms ease,
            box-shadow 160ms ease;
    }

    [data-theme-result-master-row]:hover,
    [data-theme-result-master-active] {
        background:
            color-mix(
                in srgb,
                var(--theme-primary) 8%,
                transparent
            );
    }

    [data-theme-result-master-active] {
        box-shadow:
            inset 3px 0 0
            var(--theme-primary);
    }

    [data-theme-result-primary-card] {
        min-width: 0;
    }

    [data-theme-result-history-row] {
        transition:
            border-color 160ms ease,
            box-shadow 160ms ease;
    }

    [data-theme-result-history-row]:hover {
        border-color:
            color-mix(
                in srgb,
                var(--theme-border-accent) 75%,
                var(--theme-border)
            );

        box-shadow:
            0 10px 28px
            color-mix(
                in srgb,
                var(--theme-shadow) 55%,
                transparent
            );
    }

    @media (min-width: 768px) {
        [data-theme-result-master-detail] {
            grid-template-columns:
                minmax(15rem, 0.82fr)
                minmax(0, 1.78fr);

            gap: 1rem;
        }
    }

    @media (min-width: 1024px) {
        [data-theme-result-master-detail] {
            grid-template-columns:
                minmax(17rem, 0.72fr)
                minmax(0, 2.28fr);

            gap: 1.25rem;
        }

        [data-theme-result-master-panel] {
            position: sticky;
            top: 5.5rem;
        }

        [data-theme-result-master-list] {
            max-height: calc(100vh - 12rem);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        [data-theme-result-workspace="history"],
        [data-theme-result-workspace="detail"] {
            display: grid;

            grid-template-columns:
                minmax(16rem, 0.72fr)
                minmax(0, 2.28fr);

            align-items: start;
        }

        [data-theme-result-workspace="history"]
        > [data-theme-result-context-panel],
        [data-theme-result-workspace="detail"]
        > [data-theme-result-context-panel] {
            grid-column: 1;
            min-height: 100%;

            border-right:
                1px solid
                color-mix(
                    in srgb,
                    var(--theme-border) 72%,
                    transparent
                );
        }

        [data-theme-result-workspace="history"]
        > [data-theme-result-detail-panel],
        [data-theme-result-workspace="detail"]
        > [data-theme-result-detail-panel] {
            grid-column: 2;
        }

        [data-theme-result-workspace="history"]
        > [data-theme-result-context-panel]
        > div,
        [data-theme-result-workspace="detail"]
        > [data-theme-result-context-panel]
        > div {
            position: sticky;
            top: 5.5rem;
        }

        [data-theme-result-workspace="history"]
        > [data-theme-result-detail-panel]
        > div,
        [data-theme-result-workspace="detail"]
        > [data-theme-result-detail-panel]
        > div {
            padding-left: 1.5rem;
        }
    }

    @media (max-width: 767px) {
        /*
         * D14 index mobile overflow containment
         *
         * Keep containment local to Result INDEX.
         * Do not hide overflow globally on html/body.
         */
        [data-theme-result-workspace="index"] {
            max-width: 100%;
            overflow-x: clip;
        }

        [data-theme-result-workspace="index"]
        [data-theme-result-section],
        [data-theme-result-workspace="index"]
        [data-theme-result-filter-panel],
        [data-theme-result-workspace="index"]
        [data-theme-result-list-panel],
        [data-theme-result-workspace="index"]
        [data-theme-result-master-detail],
        [data-theme-result-workspace="index"]
        [data-theme-result-master-panel],
        [data-theme-result-workspace="index"]
        [data-theme-result-master-list],
        [data-theme-result-workspace="index"]
        [data-theme-result-master-row],
        [data-theme-result-workspace="index"]
        [data-theme-result-primary-panel],
        [data-theme-result-workspace="index"]
        [data-theme-result-primary-card],
        [data-theme-result-workspace="index"]
        [data-theme-result-number-panel] {
            min-width: 0;
            max-width: 100%;
        }

        [data-theme-result-workspace="index"]
        [data-theme-result-master-row] > * {
            min-width: 0;
            max-width: 100%;
        }

        [data-theme-result-workspace="index"]
        [data-theme-result-master-row]
        > *
        > * {
            min-width: 0;
            max-width: 100%;
        }

        [data-theme-result-workspace="index"]
        [data-theme-result-number] {
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        [data-theme-result-master-detail] {
            display: block;
        }

        [data-theme-result-primary-panel] {
            margin-top: 1rem;
        }
    }

    @media (max-width: 1023px) {
        [data-theme-result-workspace] {
            display: block;
        }

        [data-theme-result-context-panel] {
            border-right: 0;
        }
    }

    @media (max-width: 639px) {
        [data-theme-result-history-list] {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
        }

        [data-theme-result-history-row] {
            border-radius: 0.875rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-theme-result-master-row],
        [data-theme-result-history-row] {
            transition: none;
        }
    }
</style>
