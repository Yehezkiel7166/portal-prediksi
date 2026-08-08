<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Theme Engine
    |--------------------------------------------------------------------------
    |
    | Stage 15B defines the canonical token structure.
    |
    | Stage 15C will add the 100 selectable presets.
    | Stage 15D will migrate remaining frontend modules to these tokens.
    |
    */

    'allowed' => [

        'background_modes' => [
            'theme',
            'image',
        ],

        'background_sizes' => [
            'cover',
            'contain',
            'auto',
        ],

        'background_positions' => [
            'center',
            'top',
            'bottom',
            'left',
            'right',
        ],

        'component_styles' => [
            'solid',
            'semi-transparent',
            'glass',
            'outline',
        ],

    ],

    'defaults' => [

        'slug' => 'midnight-gold',

        'background' => [
            'mode' => 'theme',
            'image' => null,
            'size' => 'cover',
            'position' => 'center',
            'repeat' => false,
            'fixed' => false,

            'overlay' => [
                'enabled' => false,
                'color' => '#000000',
                'opacity' => 0.00,
            ],
        ],

        'appearance' => [
            'component_style' => 'solid',
            'component_opacity' => 1.00,
            'component_blur' => 0,
        ],

        'tokens' => [

            'page_bg' => '#020617',

            'surface' => '#0F172A',
            'surface_alt' => '#111827',
            'surface_soft' => 'rgba(15, 23, 42, 0.82)',

            'primary' => '#D4AF37',
            'secondary' => '#F5C542',
            'accent' => '#FACC15',

            'text' => '#FFFFFF',
            'text_muted' => '#94A3B8',
            'text_inverse' => '#020617',

            'border' => '#334155',
            'border_accent' => '#B8860B',

            'button_primary_bg' => '#D4AF37',
            'button_primary_text' => '#020617',

            'button_secondary_bg' => '#1E293B',
            'button_secondary_text' => '#FFFFFF',

            'input_bg' => '#020617',
            'input_text' => '#FFFFFF',
            'input_border' => '#475569',

            'table_header_bg' => '#111827',
            'table_header_text' => '#F5C542',

            'result_bg' => '#020617',
            'result_text' => '#FFFFFF',
            'result_border' => '#D4AF37',

            'success' => '#22C55E',
            'danger' => '#E11D48',
            'warning' => '#F59E0B',
            'info' => '#22D3EE',

            'header_bg' => '#020617',
            'footer_bg' => '#020617',

            'glow' => '#D4AF37',
            'shadow' => 'rgba(0, 0, 0, 0.30)',

        ],

    ],

];
