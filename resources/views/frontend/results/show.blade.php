@extends('frontend.layouts.app')

@section(
    'title',
    'Result '.$result->market->name.' '.$result->result_date->format('d-m-Y').' | '.config('app.name')
)

@section(
    'description',
    'Hasil '.$result->market->name.' tanggal '.$result->result_date->translatedFormat('d F Y').' beserta informasi result terbaru.'
)

@section('metadata')
    <link
        rel="canonical"
        href="{{ route('results.show', [
            'marketSlug' => $result->market->slug,
            'resultDate' => $result->result_date->format('Y-m-d'),
        ]) }}"
    >

    <meta
        property="og:title"
        content="Result {{ $result->market->name }} {{ $result->result_date->translatedFormat('d F Y') }}"
    >

    <meta
        property="og:description"
        content="Lihat hasil {{ $result->market->name }} tanggal {{ $result->result_date->translatedFormat('d F Y') }}."
    >

    <meta property="og:type" content="article">

    <meta
        property="og:url"
        content="{{ route('results.show', [
            'marketSlug' => $result->market->slug,
            'resultDate' => $result->result_date->format('Y-m-d'),
        ]) }}"
    >
@endsection

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-4xl px-4 py-12 md:py-16">
        <nav class="text-sm text-slate-400" aria-label="Breadcrumb">
            <a
                href="{{ route('results.index') }}"
                class="transition hover:text-amber-400"
            >
                Data Result
            </a>

            <span class="mx-2">/</span>

            <a
                href="{{ route('results.index', [
                    'market' => $result->market->slug,
                ]) }}"
                class="transition hover:text-amber-400"
            >
                {{ $result->market->name }}
            </a>

            <span class="mx-2">/</span>

            <span class="text-slate-200">
                {{ $result->result_date->format('d-m-Y') }}
            </span>
        </nav>

        <p class="mt-8 text-sm font-semibold uppercase tracking-widest text-amber-400">
            Data Result
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Result {{ $result->market->name }}
        </h1>

        <div class="mt-5 flex flex-wrap gap-3 text-sm">
            <span class="rounded-full border border-slate-700 px-3 py-1 text-slate-300">
                {{ $result->market->code }}
            </span>

            <time
                datetime="{{ $result->result_date->format('Y-m-d') }}"
                class="rounded-full border border-slate-700 px-3 py-1 text-slate-300"
            >
                {{ $result->result_date->translatedFormat('d F Y') }}
            </time>

            <span class="rounded-full border border-slate-700 px-3 py-1 text-slate-300">
                {{ $result->market->timezone }}
            </span>
        </div>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-4xl px-4 py-12">
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-6 md:p-8">
            <p class="text-sm font-medium text-slate-400">
                Nomor result
            </p>

            <div class="mt-4 whitespace-pre-line break-words rounded-xl border border-amber-400/20 bg-slate-950 p-6 text-center text-2xl font-bold leading-relaxed text-white md:text-3xl">
                {{ $result->winning_numbers }}
            </div>

            @if (filled($result->notes))
                <div class="mt-8 border-t border-slate-800 pt-8">
                    <h2 class="text-lg font-semibold text-white">
                        Catatan
                    </h2>

                    <p class="mt-3 whitespace-pre-line leading-7 text-slate-300">
                        {{ $result->notes }}
                    </p>
                </div>
            @endif
        </article>

        <div class="mt-8 flex flex-wrap gap-3">
            <a
                href="{{ route('results.index', [
                    'market' => $result->market->slug,
                ]) }}"
                class="inline-flex items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300"
            >
                Result {{ $result->market->name }} Lainnya
            </a>

            <a
                href="{{ route('results.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:border-slate-500 hover:text-white"
            >
                Semua Data Result
            </a>
        </div>
    </div>
</section>
@endsection
