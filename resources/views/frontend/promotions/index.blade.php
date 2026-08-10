@extends('frontend.layouts.app')

@section('title', 'Promosi Terbaru | '.config('app.name'))

@section(
    'description',
    'Temukan berbagai promosi terbaru yang tersedia melalui '.config('app.name').'.'
)

@section('content')
<section data-theme-content="promotion-index" class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Promosi
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Promosi Terbaru
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Temukan informasi promosi terbaru yang telah diterbitkan dan masih
            tersedia untuk publik.
        </p>
    </div>
</section>

<section data-theme-content="promotion-index" class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-7">
            <p class="text-sm text-slate-400">
                Menampilkan
                <span class="font-semibold text-white">
                    {{ $promotions->total() }}
                </span>
                promosi
            </p>
        </div>

        @forelse ($promotions as $promotion)
            @if ($loop->first)
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @endif

            <article data-theme-surface class="flex h-full flex-col overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                @if ($promotion->media_source === 'upload' && filled($promotion->media_path))
                    <a
                        href="{{ route('promotions.show', $promotion->slug) }}"
                        class="block aspect-[16/9] overflow-hidden bg-slate-950"
                    >
                        <img
                            src="{{ asset('storage/'.$promotion->media_path) }}"
                            alt="{{ $promotion->title }}"
                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                            style="object-position: {{ $promotion->focal_point }}"
                            loading="lazy"
                        >
                    </a>
                @elseif ($promotion->media_source === 'url' && filled($promotion->media_url))
                    <a
                        href="{{ route('promotions.show', $promotion->slug) }}"
                        class="block aspect-[16/9] overflow-hidden bg-slate-950"
                    >
                        <img
                            src="{{ $promotion->media_url }}"
                            alt="{{ $promotion->title }}"
                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                            style="object-position: {{ $promotion->focal_point }}"
                            loading="lazy"
                        >
                    </a>
                @else
                    <a
                        href="{{ route('promotions.show', $promotion->slug) }}"
                        class="flex aspect-[16/9] items-center justify-center bg-slate-950"
                    >
                        <span class="text-sm font-semibold uppercase tracking-widest text-slate-600">
                            Promosi
                        </span>
                    </a>
                @endif

                <div class="flex flex-1 flex-col p-6">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">
                        Promosi
                    </p>

                    <h2 class="mt-3 text-xl font-bold text-white">
                        <a
                            href="{{ route('promotions.show', $promotion->slug) }}"
                            class="transition hover:text-amber-400"
                        >
                            {{ $promotion->title }}
                        </a>
                    </h2>

                    @if (filled($promotion->excerpt))
                        <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-300">
                            {{ $promotion->excerpt }}
                        </p>
                    @endif

                    <a
                        href="{{ route('promotions.show', $promotion->slug) }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 transition hover:bg-amber-400 hover:text-slate-950"
                    >
                        Lihat Promosi
                    </a>

                    <p class="mt-auto pt-6 text-xs text-slate-500">
                        Diterbitkan
                        <time datetime="{{ $promotion->published_at->toIso8601String() }}">
                            {{ $promotion->published_at->translatedFormat('d F Y H:i') }}
                        </time>
                    </p>
                </div>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div data-theme-surface class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Belum ada promosi
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Promosi yang sudah diterbitkan akan ditampilkan pada halaman ini.
                </p>
            </div>
        @endforelse

        @if ($promotions->hasPages())
            <nav class="mt-10" aria-label="Navigasi halaman promosi">
                {{ $promotions->links() }}
            </nav>
        @endif
    </div>
</section>
@endsection
