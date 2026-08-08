@extends('frontend.layouts.app')

@section('title', $siteConfiguration->defaultSeoTitle)

@section(
    'description',
    $siteConfiguration->defaultSeoDescription
        ?? 'Portal informasi prediksi, hasil pasaran, live draw, dan kalender shio.'
)

@push('head')
    <link rel="canonical" href="{{ route('home') }}">

    <meta
        property="og:title"
        content="{{ config('app.name') }} | Portal Prediksi dan Data Result"
    >

    <meta
        property="og:description"
        content="Portal informasi Live Draw, Prediksi Togel, Data Result, Promosi, dan alat togel dari {{ config('app.name') }}."
    >

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
@endpush

@section('content')
@include('frontend.partials.homepage-banner-slider')

<section data-theme-home-section>
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p data-theme-accent class="text-sm font-semibold uppercase tracking-wider">
                    Informasi Langsung
                </p>

                <h2 class="mt-2 text-2xl font-bold">
                    Live Draw
                </h2>
            </div>

            <a
                href="{{ route('live-draw.index') }}"
                data-theme-accent class="text-sm font-semibold transition hover:opacity-80"
            >
                Lihat semua
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($liveDraws as $liveDraw)
                <article data-theme-surface class="rounded-xl border p-5">
                    <div class="flex items-center justify-between gap-3">
                        <span data-theme-muted class="text-xs font-semibold uppercase tracking-wide">
                            {{ $liveDraw->market?->code ?? 'Market' }}
                        </span>

                        <span
                            data-theme-status="{{ $liveDraw->status }}"
                            class="rounded-full border px-2.5 py-1 text-xs font-semibold uppercase"
                        >
                            {{ $liveDraw->status }}
                        </span>
                    </div>

                    <h3 class="mt-4 font-semibold">
                        {{ $liveDraw->title }}
                    </h3>

                    <p data-theme-muted class="mt-2 text-sm">
                        {{ $liveDraw->market?->name ?? 'Pasaran belum tersedia' }}
                    </p>
                </article>
            @empty
                <div data-theme-surface class="rounded-xl border border-dashed p-6 md:col-span-2 lg:col-span-4">
                    <p class="font-medium">
                        Belum ada live draw aktif
                    </p>

                    <p data-theme-muted class="mt-2 text-sm">
                        Jadwal dan status Live Draw akan ditampilkan setelah tersedia.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section data-theme-home-section>
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-12 lg:grid-cols-2 lg:py-16">
        <div>
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p data-theme-accent class="text-sm font-semibold uppercase tracking-wider">
                        Hasil Terbaru
                    </p>

                    <h2 class="mt-2 text-2xl font-bold">
                        Data Result
                    </h2>
                </div>

                <a
                    href="{{ route('results.index') }}"
                    data-theme-accent class="text-sm font-semibold transition hover:opacity-80"
                >
                    Lihat semua
                </a>
            </div>

            <div class="mt-6 space-y-3">
                @forelse ($latestResults as $result)
                    <article data-theme-surface class="flex items-center justify-between gap-4 rounded-xl border p-4">
                        <div>
                            <p class="font-semibold">
                                {{ $result->market?->name ?? 'Pasaran' }}
                            </p>

                            <time
                                data-theme-muted class="mt-1 block text-xs"
                                datetime="{{ $result->result_date->format('Y-m-d') }}"
                            >
                                {{ $result->result_date->translatedFormat('d F Y') }}
                            </time>
                        </div>

                        <p data-theme-accent class="text-right font-mono text-sm font-bold">
                            {{ $result->winning_numbers }}
                        </p>
                    </article>
                @empty
                    <div data-theme-surface class="rounded-xl border border-dashed p-6">
                        <p class="font-medium">
                            Belum ada data result
                        </p>

                        <p data-theme-muted class="mt-2 text-sm">
                            Result terbaru akan muncul setelah dikonfirmasi.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p data-theme-accent class="text-sm font-semibold uppercase tracking-wider">
                        Publikasi Aktif
                    </p>

                    <h2 class="mt-2 text-2xl font-bold">
                        Prediksi Togel
                    </h2>
                </div>

                <a
                    href="{{ route('predictions.index') }}"
                    data-theme-accent class="text-sm font-semibold transition hover:opacity-80"
                >
                    Lihat semua
                </a>
            </div>

            <div class="mt-6 space-y-3">
                @forelse ($currentPredictions as $prediction)
                    <article data-theme-surface class="rounded-xl border p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold">
                                    {{ $prediction->market?->name ?? 'Pasaran' }}
                                </p>

                                <time
                                    data-theme-muted class="mt-1 block text-xs"
                                    datetime="{{ $prediction->prediction_date->format('Y-m-d') }}"
                                >
                                    {{ $prediction->prediction_date->translatedFormat('d F Y') }}
                                </time>
                            </div>

                            <p data-theme-accent class="text-right font-mono text-sm font-bold">
                                {{ $prediction->predicted_numbers }}
                            </p>
                        </div>
                    </article>
                @empty
                    <div data-theme-surface class="rounded-xl border border-dashed p-6">
                        <p class="font-medium">
                            Belum ada prediksi aktif
                        </p>

                        <p data-theme-muted class="mt-2 text-sm">
                            Prediksi yang telah diterbitkan akan ditampilkan di sini.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section data-theme-home-section>
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p data-theme-accent class="text-sm font-semibold uppercase tracking-wider">
                    Penawaran Aktif
                </p>

                <h2 class="mt-2 text-2xl font-bold">
                    Promosi
                </h2>
            </div>

            <a
                href="{{ route('promotions.index') }}"
                data-theme-accent class="text-sm font-semibold transition hover:opacity-80"
            >
                Lihat semua
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($activePromotions as $promotion)
                <article data-theme-surface class="rounded-xl border p-5">
                    <h3 class="font-semibold">
                        <a
                            href="{{ route('promotions.show', $promotion->slug) }}"
                            class="transition hover:opacity-80"
                        >
                            {{ $promotion->title }}
                        </a>
                    </h3>

                    @if (filled($promotion->excerpt))
                        <p data-theme-muted class="mt-3 text-sm leading-6">
                            {{ $promotion->excerpt }}
                        </p>
                    @endif
                </article>
            @empty
                <div data-theme-surface class="rounded-xl border border-dashed p-6 md:col-span-2 lg:col-span-4">
                    <p class="font-medium">
                        Belum ada promosi aktif
                    </p>

                    <p data-theme-muted class="mt-2 text-sm">
                        Promosi yang sudah diterbitkan akan tampil di sini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section data-theme-home-section>
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p data-theme-accent class="text-sm font-semibold uppercase tracking-wider">
            Seluruh Layanan
        </p>

        <h2 class="mt-2 text-2xl font-bold">
            Layanan {{ $siteConfiguration->siteName }}
        </h2>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
            <a
                href="{{ route('live-draw.index') }}"
                data-theme-surface class="rounded-xl border p-5"
            >
                <h3 data-theme-accent class="font-semibold">Live Draw</h3>
            </a>

            <a
                href="{{ route('results.index') }}"
                data-theme-surface class="rounded-xl border p-5"
            >
                <h3 data-theme-accent class="font-semibold">Data Result</h3>
            </a>

            <a
                href="{{ route('predictions.index') }}"
                data-theme-surface class="rounded-xl border p-5"
            >
                <h3 data-theme-accent class="font-semibold">Prediksi Togel</h3>
            </a>

            <article data-theme-surface class="rounded-xl border p-5">
                <h3 data-theme-accent class="font-semibold">Slot Gacor / RTP</h3>
                <p data-theme-muted class="mt-2 text-sm">
                    Layanan segera tersedia.
                </p>
            </article>

            <article data-theme-surface class="rounded-xl border p-5">
                <h3 data-theme-accent class="font-semibold">Bukti Jackpot</h3>
                <p data-theme-muted class="mt-2 text-sm">
                    Layanan segera tersedia.
                </p>
            </article>

            <a
                href="{{ route('promotions.index') }}"
                data-theme-surface class="rounded-xl border p-5"
            >
                <h3 data-theme-accent class="font-semibold">Promosi</h3>
            </a>

            <article data-theme-surface class="rounded-xl border p-5">
                <h3 data-theme-accent class="font-semibold">Keluhan</h3>
                <p data-theme-muted class="mt-2 text-sm">
                    Workflow publik sedang dipersiapkan.
                </p>
            </article>

            <article data-theme-surface class="rounded-xl border p-5">
                <h3 data-theme-accent class="font-semibold">Panduan</h3>
                <p data-theme-muted class="mt-2 text-sm">
                    Guide Engine sedang dipersiapkan.
                </p>
            </article>

            <article data-theme-surface class="rounded-xl border p-5">
                <h3 data-theme-accent class="font-semibold">Alat Togel</h3>
                <p data-theme-muted class="mt-2 text-sm">
                    Enam alat wajib sedang dipersiapkan.
                </p>
            </article>

            <a
                href="{{ route('blog.index') }}"
                data-theme-surface class="rounded-xl border p-5"
            >
                <h3 data-theme-accent class="font-semibold">Blog</h3>
            </a>
        </div>
    </div>
</section>

<section data-theme-home-section>
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p data-theme-accent class="text-sm font-semibold uppercase tracking-wider">
                    Informasi Terbaru
                </p>

                <h2 class="mt-2 text-2xl font-bold">
                    Artikel
                </h2>
            </div>

            <a
                href="{{ route('blog.index') }}"
                data-theme-accent class="text-sm font-semibold transition hover:opacity-80"
            >
                Lihat semua
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($latestArticles as $article)
                <article data-theme-surface class="rounded-xl border p-5">
                    <h3 class="font-semibold">
                        <a
                            href="{{ route('blog.show', $article->slug) }}"
                            class="transition hover:opacity-80"
                        >
                            {{ $article->title }}
                        </a>
                    </h3>

                    @if (filled($article->excerpt))
                        <p data-theme-muted class="mt-3 text-sm leading-6">
                            {{ $article->excerpt }}
                        </p>
                    @endif
                </article>
            @empty
                <div data-theme-surface class="rounded-xl border border-dashed p-6 md:col-span-2 lg:col-span-4">
                    <p class="font-medium">
                        Belum ada artikel terbaru
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
