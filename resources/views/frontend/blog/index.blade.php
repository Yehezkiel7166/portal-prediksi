@extends('frontend.layouts.app')

@section('title', 'Blog Terbaru | '.config('app.name'))

@section(
    'description',
    'Baca artikel, panduan, informasi, dan pembaruan terbaru dari '.config('app.name').'.'
)

@section('metadata')
    <link rel="canonical" href="{{ route('blog.index') }}">

    <meta property="og:title" content="Blog Terbaru | {{ config('app.name') }}">

    <meta
        property="og:description"
        content="Baca artikel, panduan, informasi, dan pembaruan terbaru dari {{ config('app.name') }}."
    >

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('blog.index') }}">
@endsection

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Blog
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Artikel Terbaru
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Temukan artikel, panduan, informasi pasaran, dan pembaruan terbaru
            yang telah diterbitkan.
        </p>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-7">
            <p class="text-sm text-slate-400">
                Menampilkan
                <span class="font-semibold text-white">
                    {{ $blogPosts->total() }}
                </span>
                artikel
            </p>
        </div>

        @forelse ($blogPosts as $blogPost)
            @if ($loop->first)
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @endif

            <article class="flex h-full flex-col overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                <a
                    href="{{ route('blog.show', $blogPost->slug) }}"
                    class="block aspect-[16/9] overflow-hidden bg-slate-950"
                >
                    @if ($blogPost->image_source === 'upload' && filled($blogPost->image_path))
                        <img
                            src="{{ asset('storage/'.$blogPost->image_path) }}"
                            alt="{{ $blogPost->title }}"
                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                            style="object-position: {{ $blogPost->focal_point }}"
                            loading="lazy"
                        >
                    @elseif ($blogPost->image_source === 'url' && filled($blogPost->image_url))
                        <img
                            src="{{ $blogPost->image_url }}"
                            alt="{{ $blogPost->title }}"
                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                            style="object-position: {{ $blogPost->focal_point }}"
                            loading="lazy"
                        >
                    @else
                        <span class="flex h-full items-center justify-center text-sm font-semibold uppercase tracking-widest text-slate-600">
                            Blog
                        </span>
                    @endif
                </a>

                <div class="flex flex-1 flex-col p-6">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">
                        Artikel
                    </p>

                    <h2 class="mt-3 text-xl font-bold text-white">
                        <a
                            href="{{ route('blog.show', $blogPost->slug) }}"
                            class="transition hover:text-amber-400"
                        >
                            {{ $blogPost->title }}
                        </a>
                    </h2>

                    @if (filled($blogPost->excerpt))
                        <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-300">
                            {{ $blogPost->excerpt }}
                        </p>
                    @endif

                    <a
                        href="{{ route('blog.show', $blogPost->slug) }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 transition hover:bg-amber-400 hover:text-slate-950"
                    >
                        Baca Artikel
                    </a>

                    <p class="mt-auto pt-6 text-xs text-slate-500">
                        Diterbitkan
                        <time datetime="{{ $blogPost->published_at->toIso8601String() }}">
                            {{ $blogPost->published_at->translatedFormat('d F Y H:i') }}
                        </time>
                    </p>
                </div>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Belum ada artikel
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Artikel yang telah diterbitkan akan ditampilkan di halaman ini.
                </p>
            </div>
        @endforelse

        @if ($blogPosts->hasPages())
            <nav class="mt-10" aria-label="Navigasi halaman blog">
                {{ $blogPosts->links() }}
            </nav>
        @endif
    </div>
</section>
@endsection
