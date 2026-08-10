@extends('frontend.layouts.app')

@section(
    'title',
    'Data Result Togel Terbaru | '.config('app.name')
)

@section(
    'description',
    'Result terbaru setiap pasaran aktif beserta history lengkap melalui '.config('app.name').'.'
)

@section('content')
<section
    data-theme-result-hero
    class="border-b"
>
    <div class="mx-auto max-w-7xl px-4 py-10 md:py-14">
        <p
            data-theme-accent
            class="text-sm font-semibold uppercase tracking-widest"
        >
            Data Result
        </p>

        <h1 class="mt-3 text-3xl font-bold md:text-5xl">
            Result Terbaru Setiap Pasaran
        </h1>

        <p
            data-theme-muted
            class="mt-4 max-w-3xl text-base leading-7 md:text-lg"
        >
            Satu card mewakili satu pasaran. Tekan Detail untuk
            melihat seluruh history result pasaran tersebut.
        </p>
    </div>
</section>

<section data-theme-result-section>
    <div class="mx-auto max-w-7xl px-4 py-7">
        <form
            data-theme-surface
            method="GET"
            action="{{ route('results.index') }}"
            class="
                flex flex-col gap-4 rounded-xl border
                p-5 md:flex-row md:items-end
            "
        >
            <div class="min-w-0 flex-1">
                <label
                    for="market"
                    class="block text-sm font-medium"
                >
                    Pilih Pasaran
                </label>

                <select
                    data-theme-input
                    id="market"
                    name="market"
                    class="
                        mt-2 block w-full rounded-lg border
                        px-4 py-3 text-sm outline-none transition
                    "
                >
                    <option value="">
                        Semua pasaran
                    </option>

                    @foreach ($marketOptions as $marketOption)
                        <option
                            value="{{ $marketOption->slug }}"
                            @selected(
                                $filters['market']
                                    === $marketOption->slug
                            )
                        >
                            {{ $marketOption->name }}
                            ({{ $marketOption->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    data-theme-primary-button
                    type="submit"
                    class="
                        inline-flex min-h-11 items-center
                        justify-center rounded-lg border
                        px-5 py-3 text-sm font-semibold
                        transition hover:opacity-90
                    "
                >
                    Terapkan
                </button>

                @if ($filters['market'] !== null)
                    <a
                        data-theme-secondary-button
                        href="{{ route('results.index') }}"
                        class="
                            inline-flex min-h-11 items-center
                            justify-center rounded-lg border
                            px-5 py-3 text-sm font-semibold
                            transition hover:opacity-90
                        "
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

<section data-theme-result-section>
    <div class="mx-auto max-w-7xl px-4 pb-12 pt-4">
        <div
            class="
                mb-6 flex flex-wrap
                items-center justify-between gap-3
            "
        >
            <p
                data-theme-muted
                class="text-sm"
            >
                Menampilkan

                <span class="font-semibold">
                    {{ $markets->total() }}
                </span>

                pasaran
            </p>

            @if ($filters['market'] !== null)
                <span
                    data-theme-accent
                    class="text-sm font-semibold"
                >
                    Filter aktif
                </span>
            @endif
        </div>

        @if ($markets->isNotEmpty())
            <div
                data-theme-result-grid
                class="
                    grid gap-5
                    sm:grid-cols-2
                    xl:grid-cols-3
                "
            >
                @foreach ($markets as $market)
                    @php
                        $latestResult = $market->getRelation(
                            'latestResult'
                        );

                        $status = $statuses->get(
                            $market->getKey(),
                            [
                                'key' => 'unknown',
                                'label' => 'Status tidak tersedia',
                            ],
                        );
                    @endphp

                    <article
                        data-theme-result-card
                        data-theme-surface
                        class="
                            group flex min-h-full flex-col
                            overflow-hidden rounded-2xl border
                        "
                    >
                        <div
                            class="
                                flex items-start justify-between
                                gap-4 p-5
                            "
                        >
                            <div class="min-w-0">
                                <p
                                    data-theme-muted
                                    class="
                                        text-xs font-semibold uppercase
                                        tracking-widest
                                    "
                                >
                                    {{ $market->code }}
                                </p>

                                <h2
                                    data-theme-accent
                                    class="
                                        mt-1 truncate text-xl
                                        font-bold
                                    "
                                >
                                    {{ $market->name }}
                                </h2>
                            </div>

                            <span
                                data-theme-market-status="{{ $status['key'] }}"
                                class="
                                    shrink-0 rounded-full border
                                    px-3 py-1 text-xs font-semibold
                                "
                            >
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <div
                            data-theme-result-number-panel
                            class="
                                mx-5 rounded-xl border
                                px-4 py-6 text-center
                            "
                        >
                            <p
                                data-theme-muted
                                class="
                                    text-xs font-semibold uppercase
                                    tracking-wider
                                "
                            >
                                Result Terbaru
                            </p>

                            @if ($latestResult)
                                <p
                                    data-theme-result-number
                                    class="
                                        mt-3 break-words font-mono
                                        text-3xl font-black
                                        tracking-[0.12em]
                                        md:text-4xl
                                    "
                                >
                                    {{ $latestResult->winning_numbers }}
                                </p>
                            @else
                                <p
                                    data-theme-muted
                                    class="mt-3 text-sm"
                                >
                                    Belum ada result.
                                </p>
                            @endif
                        </div>

                        <div
                            class="
                                mt-auto grid grid-cols-2
                                gap-3 p-5
                            "
                        >
                            <div>
                                <p
                                    data-theme-muted
                                    class="text-xs"
                                >
                                    Tanggal
                                </p>

                                @if ($latestResult)
                                    <time
                                        datetime="{{ $latestResult->result_date->format('Y-m-d') }}"
                                        class="
                                            mt-1 block text-sm
                                            font-semibold
                                        "
                                    >
                                        {{ $latestResult->result_date->translatedFormat('d M Y') }}
                                    </time>
                                @else
                                    <span
                                        data-theme-muted
                                        class="mt-1 block text-sm"
                                    >
                                        —
                                    </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <p
                                    data-theme-muted
                                    class="text-xs"
                                >
                                    History
                                </p>

                                <p class="mt-1 text-sm font-semibold">
                                    {{ $market->results_count }}
                                    result
                                </p>
                            </div>
                        </div>

                        <div class="px-5 pb-5">
                            <a
                                data-theme-primary-button
                                href="{{ route('results.history', [
                                    'marketSlug' => $market->slug,
                                ]) }}"
                                class="
                                    inline-flex min-h-11 w-full
                                    items-center justify-center
                                    rounded-lg border px-4 py-2.5
                                    text-sm font-bold transition
                                    hover:opacity-90
                                "
                            >
                                Detail {{ $market->name }}
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
                    Belum ada pasaran aktif
                </h2>

                <p
                    data-theme-muted
                    class="
                        mx-auto mt-3 max-w-xl
                        text-sm leading-6
                    "
                >
                    Tidak ada pasaran yang sesuai dengan filter.
                </p>
            </div>
        @endif

        @if ($markets->hasPages())
            <nav
                class="mt-10"
                aria-label="Navigasi halaman pasaran result"
            >
                {{ $markets->links() }}
            </nav>
        @endif
    </div>
</section>
@endsection
