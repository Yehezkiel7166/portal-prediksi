@extends('frontend.layouts.app')

@section(
    'title',
    'History Result '.$market->name.' | '.config('app.name')
)

@section(
    'description',
    'History result pasaran '.$market->name.' melalui '.config('app.name').'.'
)

@section('content')
<div data-theme-result-workspace="history">
<section
    data-theme-result-context-panel
    data-theme-result-hero
    class="border-b"
>
    <div class="mx-auto max-w-7xl px-4 py-7 md:py-9">
        <a
            data-theme-accent
            href="{{ route('results.index') }}"
            class="text-sm font-semibold transition hover:opacity-80"
        >
            ← Semua Pasaran
        </a>

        <div
            class="
                mt-6 flex flex-wrap
                items-start justify-between gap-4
            "
        >
            <div>
                <p
                    data-theme-accent
                    class="
                        text-sm font-semibold uppercase
                        tracking-widest
                    "
                >
                    History Result
                </p>

                <h1 class="mt-2 text-3xl font-bold md:text-4xl">
                    {{ $market->name }}
                </h1>

                <p
                    data-theme-muted
                    class="mt-3"
                >
                    Kode pasaran:
                    {{ $market->code }}
                </p>
            </div>

            <span
                data-theme-market-status="{{ $status['key'] }}"
                class="
                    inline-flex rounded-full
                    border px-4 py-2
                    text-sm font-semibold
                "
            >
                {{ $status['label'] }}
            </span>
        </div>
    </div>
</section>

<section data-theme-result-section data-theme-result-detail-panel>
    <div class="mx-auto max-w-7xl px-4 py-8">
        <p
            data-theme-muted
            class="mb-7 text-sm"
        >
            Total

            <span class="font-semibold">
                {{ $results->total() }}
            </span>

            history result
        </p>

        @if ($results->isNotEmpty())
            <div
                data-theme-result-history-list
                class="space-y-3"
            >
                @foreach ($results as $result)
                    <article
                        data-theme-result-history-card
                        data-theme-result-history-row
                        data-theme-surface
                        class="
                            rounded-xl border p-4
                            md:grid
                            md:grid-cols-[160px_minmax(0,1fr)_140px]
                            md:items-center md:gap-4
                        "
                    >
                        <time
                            data-theme-accent
                            datetime="{{ $result->result_date->format('Y-m-d') }}"
                            class="text-sm font-semibold"
                        >
                            {{ $result->result_date->translatedFormat('d F Y') }}
                        </time>

                        <div
                            data-theme-result-number-panel
                            class="
                                mt-4 rounded-lg border md:mt-0
                                px-4 py-4 text-center
                            "
                        >
                            <p
                                data-theme-result-number
                                class="
                                    whitespace-pre-line break-words
                                    font-mono text-2xl font-black
                                    tracking-[0.1em]
                                "
                            >
                                {{ $result->winning_numbers }}
                            </p>
                        </div>

                        @if (filled($result->notes))
                            <p
                                data-theme-muted
                                class="
                                    mt-4 line-clamp-3 md:col-span-3
                                    whitespace-pre-line
                                    text-sm leading-6
                                "
                            >
                                {{ $result->notes }}
                            </p>
                        @endif

                        <div class="mt-auto pt-5 md:mt-0 md:pt-0">
                            <a
                                data-theme-secondary-button
                                href="{{ route('results.show', [
                                    'marketSlug' => $market->slug,
                                    'resultDate' => $result->result_date->format('Y-m-d'),
                                ]) }}"
                                class="
                                    inline-flex min-h-10 w-full
                                    items-center justify-center
                                    rounded-lg border px-4 py-2
                                    text-sm font-semibold transition
                                    hover:opacity-90
                                "
                            >
                                Buka Detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div
                data-theme-surface
                class="
                    rounded-2xl border border-dashed
                    px-6 py-16 text-center
                "
            >
                <h2 class="text-xl font-semibold">
                    Belum ada history result
                </h2>
            </div>
        @endif

        @if ($results->hasPages())
            <nav
                class="mt-10"
                aria-label="Navigasi history result"
            >
                {{ $results->links() }}
            </nav>
        @endif
    </div>
</section>
</div>
@endsection
