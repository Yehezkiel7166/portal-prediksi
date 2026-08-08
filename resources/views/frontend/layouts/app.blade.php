<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>@yield('title', $siteConfiguration->defaultSeoTitle)</title>

    <meta
        name="description"
        content="@yield('description', $siteConfiguration->defaultSeoDescription ?? 'Portal informasi prediksi, hasil pasaran, live draw, dan kalender shio.')"
    >

    @if ($siteConfiguration->faviconUrl)
        <link
            rel="icon"
            href="{{ $siteConfiguration->faviconUrl }}"
        >
    @endif

    @hasSection('metadata')
        @yield('metadata')
    @endif

    @fonts

    @if (
        file_exists(public_path('build/manifest.json'))
        || file_exists(public_path('hot'))
    )
        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
        ])
    @endif

    @stack('head')

    @include('frontend.partials.theme-tokens')
</head>

<body
    data-theme-root
    class="min-h-screen antialiased"
>
    @include('frontend.partials.header')

    <main data-theme-main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')
</body>
</html>
