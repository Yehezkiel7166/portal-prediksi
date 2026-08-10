@extends('frontend.layouts.app')

@section(
    'title',
    filled($blogPost->seo_title)
        ? $blogPost->seo_title
        : $blogPost->title.' | '.config('app.name')
)

@section(
    'description',
    filled($blogPost->seo_description)
        ? $blogPost->seo_description
        : (
            filled($blogPost->excerpt)
                ? $blogPost->excerpt
                : 'Artikel terbaru dari '.config('app.name').'.'
        )
)

@section('metadata')
    <link
        rel="canonical"
        href="{{ route('blog.show', $blogPost->slug) }}"
    >

    <meta
        property="og:title"
        content="{{ filled($blogPost->seo_title) ? $blogPost->seo_title : $blogPost->title }}"
    >

    <meta
        property="og:description"
        content="{{ filled($blogPost->seo_description) ? $blogPost->seo_description : (filled($blogPost->excerpt) ? $blogPost->excerpt : 'Artikel terbaru dari '.config('app.name').'.') }}"
    >

    <meta property="og:type" content="article">

    <meta
        property="og:url"
        content="{{ route('blog.show', $blogPost->slug) }}"
    >

    <meta
        property="article:published_time"
        content="{{ $blogPost->published_at->toIso8601String() }}"
    >

    @if ($blogPost->image_source === 'upload' && filled($blogPost->image_path))
        <meta
            property="og:image"
            content="{{ asset('storage/'.$blogPost->image_path) }}"
        >
    @elseif ($blogPost->image_source === 'url' && filled($blogPost->image_url))
        <meta
            property="og:image"
            content="{{ $blogPost->image_url }}"
        >
    @endif
@endsection

@section('content')
<section data-theme-content="blog-detail" class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-10 md:py-14">
        <a
            href="{{ route('blog.index') }}"
            class="inline-flex items-center text-sm font-semibold text-amber-400 transition hover:text-amber-300"
        >
            ← Kembali ke Blog
        </a>

        <p class="mt-7 text-sm font-semibold uppercase tracking-widest text-amber-400">
            Artikel
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            {{ $blogPost->title }}
        </h1>

        @if (filled($blogPost->excerpt))
            <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
                {{ $blogPost->excerpt }}
            </p>
        @endif

        <p class="mt-5 text-sm text-slate-500">
            Diterbitkan
            <time datetime="{{ $blogPost->published_at->toIso8601String() }}">
                {{ $blogPost->published_at->translatedFormat('d F Y H:i') }}
            </time>
        </p>
    </div>
</section>

<section data-theme-content="blog-detail" class="bg-slate-950">
    <div class="mx-auto max-w-5xl px-4 py-12">
        <article data-theme-surface class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
            @if ($blogPost->image_source === 'upload' && filled($blogPost->image_path))
                <div class="aspect-[16/9] overflow-hidden bg-slate-950">
                    <img
                        src="{{ asset('storage/'.$blogPost->image_path) }}"
                        alt="{{ $blogPost->title }}"
                        class="h-full w-full object-cover"
                        style="object-position: {{ $blogPost->focal_point }}"
                    >
                </div>
            @elseif ($blogPost->image_source === 'url' && filled($blogPost->image_url))
                <div class="aspect-[16/9] overflow-hidden bg-slate-950">
                    <img
                        src="{{ $blogPost->image_url }}"
                        alt="{{ $blogPost->title }}"
                        class="h-full w-full object-cover"
                        style="object-position: {{ $blogPost->focal_point }}"
                    >
                </div>
            @endif

            <div class="p-6 md:p-8">
                @if (filled($blogPost->content))
                    <div data-theme-rich-content class="prose prose-invert max-w-none break-words leading-8">
                        {!! $blogPost->content !!}
                    </div>
                @elseif (filled($blogPost->excerpt))
                    <p class="leading-8 text-slate-300">
                        {{ $blogPost->excerpt }}
                    </p>
                @else
                    <p class="text-slate-400">
                        Konten artikel belum tersedia.
                    </p>
                @endif
            </div>
        </article>
    </div>
</section>
@endsection
