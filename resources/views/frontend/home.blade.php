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

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-amber-400">
                    Informasi Langsung
                </p>

                <h2 class="mt-2 text-2xl font-bold text-white">
                    Live Draw
                </h2>
            </div>

            <a
                href="{{ route('live-draw.index') }}"
                class="text-sm font-semibold text-amber-400 hover:text-amber-300"
            >
                Lihat semua
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($liveDraws as $liveDraw)
                <article class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            {{ $liveDraw->market?->code ?? 'Market' }}
                        </span>

                        <span
                            @class([
                                'rounded-full px-2.5 py-1 text-xs font-semibold uppercase',
                                'bg-red-500/15 text-red-300' => $liveDraw->status === 'live',
                                'bg-amber-500/15 text-amber-300' => $liveDraw->status === 'scheduled',
                                'bg-slate-700 text-slate-300' => ! in_array(
                                    $liveDraw->status,
                                    ['live', 'scheduled'],
                                    true
                                ),
                            ])
                        >
                            {{ $liveDraw->status }}
                        </span>
                    </div>

                    <h3 class="mt-4 font-semibold text-white">
                        {{ $liveDraw->title }}
                    </h3>

                    <p class="mt-2 text-sm text-slate-400">
                        {{ $liveDraw->market?->name ?? 'Pasaran belum tersedia' }}
                    </p>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 p-6 md:col-span-2 lg:col-span-4">
                    <p class="font-medium text-slate-200">
                        Belum ada live draw aktif
                    </p>

                    <p class="mt-2 text-sm text-slate-400">
                        Jadwal dan status Live Draw akan ditampilkan setelah tersedia.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="border-y border-slate-800 bg-slate-900">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-12 lg:grid-cols-2 lg:py-16">
        <div>
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-amber-400">
                        Hasil Terbaru
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-white">
                        Data Result
                    </h2>
                </div>

                <a
                    href="{{ route('results.index') }}"
                    class="text-sm font-semibold text-amber-400 hover:text-amber-300"
                >
                    Lihat semua
                </a>
            </div>

            <div class="mt-6 space-y-3">
                @forelse ($latestResults as $result)
                    <article class="flex items-center justify-between gap-4 rounded-xl border border-slate-800 bg-slate-950 p-4">
                        <div>
                            <p class="font-semibold text-white">
                                {{ $result->market?->name ?? 'Pasaran' }}
                            </p>

                            <time
                                class="mt-1 block text-xs text-slate-400"
                                datetime="{{ $result->result_date->format('Y-m-d') }}"
                            >
                                {{ $result->result_date->translatedFormat('d F Y') }}
                            </time>
                        </div>

                        <p class="text-right font-mono text-sm font-bold text-amber-400">
                            {{ $result->winning_numbers }}
                        </p>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-700 bg-slate-950 p-6">
                        <p class="font-medium text-slate-200">
                            Belum ada data result
                        </p>

                        <p class="mt-2 text-sm text-slate-400">
                            Result terbaru akan muncul setelah dikonfirmasi.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-amber-400">
                        Publikasi Aktif
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-white">
                        Prediksi Togel
                    </h2>
                </div>

                <a
                    href="{{ route('predictions.index') }}"
                    class="text-sm font-semibold text-amber-400 hover:text-amber-300"
                >
                    Lihat semua
                </a>
            </div>

            <div class="mt-6 space-y-3">
                @forelse ($currentPredictions as $prediction)
                    <article class="rounded-xl border border-slate-800 bg-slate-950 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-white">
                                    {{ $prediction->market?->name ?? 'Pasaran' }}
                                </p>

                                <time
                                    class="mt-1 block text-xs text-slate-400"
                                    datetime="{{ $prediction->prediction_date->format('Y-m-d') }}"
                                >
                                    {{ $prediction->prediction_date->translatedFormat('d F Y') }}
                                </time>
                            </div>

                            <p class="text-right font-mono text-sm font-bold text-amber-400">
                                {{ $prediction->predicted_numbers }}
                            </p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-700 bg-slate-950 p-6">
                        <p class="font-medium text-slate-200">
                            Belum ada prediksi aktif
                        </p>

                        <p class="mt-2 text-sm text-slate-400">
                            Prediksi yang telah diterbitkan akan ditampilkan di sini.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-amber-400">
                    Penawaran Aktif
                </p>

                <h2 class="mt-2 text-2xl font-bold text-white">
                    Promosi
                </h2>
            </div>

            <a
                href="{{ route('promotions.index') }}"
                class="text-sm font-semibold text-amber-400 hover:text-amber-300"
            >
                Lihat semua
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($activePromotions as $promotion)
                <article class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                    <h3 class="font-semibold text-white">
                        <a
                            href="{{ route('promotions.show', $promotion->slug) }}"
                            class="hover:text-amber-400"
                        >
                            {{ $promotion->title }}
                        </a>
                    </h3>

                    @if (filled($promotion->excerpt))
                        <p class="mt-3 text-sm leading-6 text-slate-400">
                            {{ $promotion->excerpt }}
                        </p>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 p-6 md:col-span-2 lg:col-span-4">
                    <p class="font-medium text-slate-200">
                        Belum ada promosi aktif
                    </p>

                    <p class="mt-2 text-sm text-slate-400">
                        Promosi yang sudah diterbitkan akan tampil di sini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="border-y border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-wider text-amber-400">
            Seluruh Layanan
        </p>

        <h2 class="mt-2 text-2xl font-bold text-white">
            Layanan {{ $siteConfiguration->siteName }}
        </h2>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
            <a
                href="{{ route('live-draw.index') }}"
                class="rounded-xl border border-slate-800 bg-slate-950 p-5"
            >
                <h3 class="font-semibold text-amber-400">Live Draw</h3>
            </a>

            <a
                href="{{ route('results.index') }}"
                class="rounded-xl border border-slate-800 bg-slate-950 p-5"
            >
                <h3 class="font-semibold text-amber-400">Data Result</h3>
            </a>

            <a
                href="{{ route('predictions.index') }}"
                class="rounded-xl border border-slate-800 bg-slate-950 p-5"
            >
                <h3 class="font-semibold text-amber-400">Prediksi Togel</h3>
            </a>

            <article class="rounded-xl border border-slate-800 bg-slate-950 p-5">
                <h3 class="font-semibold text-amber-400">Slot Gacor / RTP</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Layanan segera tersedia.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-950 p-5">
                <h3 class="font-semibold text-amber-400">Bukti Jackpot</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Layanan segera tersedia.
                </p>
            </article>

            <a
                href="{{ route('promotions.index') }}"
                class="rounded-xl border border-slate-800 bg-slate-950 p-5"
            >
                <h3 class="font-semibold text-amber-400">Promosi</h3>
            </a>

            <article class="rounded-xl border border-slate-800 bg-slate-950 p-5">
                <h3 class="font-semibold text-amber-400">Keluhan</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Workflow publik sedang dipersiapkan.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-950 p-5">
                <h3 class="font-semibold text-amber-400">Panduan</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Guide Engine sedang dipersiapkan.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-950 p-5">
                <h3 class="font-semibold text-amber-400">Alat Togel</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Enam alat wajib sedang dipersiapkan.
                </p>
            </article>

            <a
                href="{{ route('blog.index') }}"
                class="rounded-xl border border-slate-800 bg-slate-950 p-5"
            >
                <h3 class="font-semibold text-amber-400">Blog</h3>
            </a>
        </div>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-amber-400">
                    Informasi Terbaru
                </p>

                <h2 class="mt-2 text-2xl font-bold text-white">
                    Artikel
                </h2>
            </div>

            <a
                href="{{ route('blog.index') }}"
                class="text-sm font-semibold text-amber-400 hover:text-amber-300"
            >
                Lihat semua
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($latestArticles as $article)
                <article class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                    <h3 class="font-semibold text-white">
                        <a
                            href="{{ route('blog.show', $article->slug) }}"
                            class="hover:text-amber-400"
                        >
                            {{ $article->title }}
                        </a>
                    </h3>

                    @if (filled($article->excerpt))
                        <p class="mt-3 text-sm leading-6 text-slate-400">
                            {{ $article->excerpt }}
                        </p>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 p-6 md:col-span-2 lg:col-span-4">
                    <p class="font-medium text-slate-200">
                        Belum ada artikel terbaru
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
