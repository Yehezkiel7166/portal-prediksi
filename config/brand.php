<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Canonical Default Brand
    |--------------------------------------------------------------------------
    |
    | During the single-brand phase, the application resolves this brand
    | whenever no explicit brand resolution strategy is active.
    |
    | Multi-brand resolvers introduced in later phases should continue to
    | use this value as the ultimate fallback.
    |
    */

    'default_code' => env('DEFAULT_BRAND_CODE', 'DEFAULT'),

];
