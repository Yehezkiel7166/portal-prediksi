@extends('frontend.layouts.app')

@section(
    'title',
    $promotion->title.' | '.config('app.name')
)

@section(
    'description',
    filled($promotion->excerpt)
        ? $promotion->excerpt
        : 'Informasi promosi terbaru dari '.config('app.name').'.'
)

@section('metadata')
    <link
        rel="canonical"
        href="{{ route('promotions.show', $promotion->slug) }}"
    >

    <meta
        property="og:title"
        content="{{ $promotion->title }}"
    >

    <meta
        property="og:description"
        content="{{ filled($promotion->excerpt) ? $promotion->excerpt : 'Informasi promosi terbaru dari '.config('app.name').'.' }}"
    >

    <meta property="og:type" content="article">

    <meta
        property="og:url"
        content="{{ route('promotions.show', $promotion->slug) }}"
    >

    @if ($promotion->media_source === 'upload' && filled($promotion->media_path))
        <meta
            property="og:image"
            content="{{ asset('storage/'.$promotion->media_path) }}"
        >
    @elseif ($promotion->media_source === 'url' && filled($promotion->media_url))
        <meta
            property="og:image"
            content="{{ $promotion->media_url }}"
        >
    @endif
@endsection

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-10 md:py-14">
        <a
            href="{{ route('promotions.index') }}"
            class="inline-flex items-center text-sm font-semibold text-amber-400 transition hover:text-amber-300"
        >
            ← Kembali ke Promosi
        </a>

        <p class="mt-7 text-sm font-semibold uppercase tracking-widest text-amber-400">
            Promosi
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            {{ $promotion->title }}
        </h1>

        @if (filled($promotion->excerpt))
            <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
                {{ $promotion->excerpt }}
            </p>
        @endif
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-5xl px-4 py-12">
        <article class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
            @if ($promotion->media_source === 'upload' && filled($promotion->media_path))
                <div class="aspect-[16/9] overflow-hidden bg-slate-950">
                    <img
                        src="{{ asset('storage/'.$promotion->media_path) }}"
                        alt="{{ $promotion->title }}"
                        class="h-full w-full object-cover"
                        style="object-position: {{ $promotion->focal_point }}"
                    >
                </div>
            @elseif ($promotion->media_source === 'url' && filled($promotion->media_url))
                <div class="aspect-[16/9] overflow-hidden bg-slate-950">
                    <img
                        src="{{ $promotion->media_url }}"
                        alt="{{ $promotion->title }}"
                        class="h-full w-full object-cover"
                        style="object-position: {{ $promotion->focal_point }}"
                    >
                </div>
            @elseif ($promotion->media_source === 'embed' && filled($promotion->embed_url))
                <div class="aspect-video overflow-hidden bg-black">
                    <iframe
                        src="{{ $promotion->embed_url }}"
                        title="{{ $promotion->title }}"
                        class="h-full w-full"
                        loading="lazy"
                        allowfullscreen
                    ></iframe>
                </div>
            @endif

            <div class="p-6 md:p-8">
                @if (filled($promotion->content))
                    <div class="whitespace-pre-line break-words leading-8 text-slate-300">
                        {{ $promotion->content }}
                    </div>
                @elseif (filled($promotion->excerpt))
                    <p class="leading-8 text-slate-300">
                        {{ $promotion->excerpt }}
                    </p>
                @else
                    <p class="text-slate-400">
                        Informasi lengkap promosi belum tersedia.
                    </p>
                @endif
            </div>

            <footer class="border-t border-slate-800 bg-slate-950/60 p-6 md:px-8">
                <p class="text-sm text-slate-500">
                    Diterbitkan
                    <time datetime="{{ $promotion->published_at->toIso8601String() }}">
                        {{ $promotion->published_at->translatedFormat('d F Y H:i') }}
                    </time>
                </p>
            </footer>
        </article>
    </div>
</section>
@endsection
