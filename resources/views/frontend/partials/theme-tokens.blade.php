@php
    $brandTheme = app(
        \App\Domains\Theme\Support\BrandThemeResolver::class
    )->resolve();

    $themeTokens = $brandTheme['tokens'] ?? [];
    $themeBackground = $brandTheme['background'] ?? [];
    $themeAppearance = $brandTheme['appearance'] ?? [];

    $themeBackgroundMode =
        $themeBackground['mode'] ?? 'theme';

    $themeBackgroundImage =
        $themeBackground['image'] ?? null;

    $themeOverlay =
        $themeBackground['overlay'] ?? [];
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
            {{ $themeAppearance['component_opacity'] ?? 1 }};

        --theme-component-blur:
            {{ (int) ($themeAppearance['component_blur'] ?? 0) }}px;
    }

    html {
        --theme-slug:
            "{{ $brandTheme['slug'] ?? 'midnight-gold' }}";
    }

    @if (
        $themeBackgroundMode === 'image'
        && filled($themeBackgroundImage)
    )
        body {
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
        body::before {
            content: "";

            position: fixed;
            inset: 0;

            z-index: -1;

            pointer-events: none;

            background:
                {{ $themeOverlay['color'] ?? '#000000' }};

            opacity:
                {{ (float) ($themeOverlay['opacity'] ?? 0) }};
        }
    @endif
</style>
