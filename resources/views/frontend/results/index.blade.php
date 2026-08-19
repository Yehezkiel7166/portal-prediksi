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
<div data-theme-result-workspace="index">
    <section
        data-theme-result-hero
        class="border-b"
    >
        <div class="mx-auto max-w-7xl px-4 py-7 md:py-9">
            <p
                data-theme-accent
                class="text-sm font-semibold uppercase tracking-widest"
            >
                Data Result
            </p>

            <h1 class="mt-2 text-3xl font-bold md:text-4xl">
                Result Terbaru Setiap Pasaran
            </h1>

            <p
                data-theme-muted
                class="mt-4 max-w-3xl text-base leading-7 md:text-lg"
            >
                Pilih pasaran untuk melihat result terbaru dan membuka
                seluruh history result secara lengkap.
            </p>
        </div>
    </section>

    <section
        data-theme-result-section
        data-theme-result-filter-panel
    >
        <div class="mx-auto max-w-7xl px-4 pt-6">
            <form
                data-theme-surface
                method="GET"
                action="{{ route('results.index') }}"
                class="
                    flex flex-col gap-3 rounded-xl border p-4
                    sm:flex-row sm:items-end
                "
            >
                <div class="min-w-0 flex-1">
                    <label
                        for="market"
                        class="
                            block text-xs font-semibold
                            uppercase tracking-wider
                        "
                    >
                        Pilih Pasaran
                    </label>

                    <select
                        data-theme-input
                        id="market"
                        name="market"
                        class="
                            mt-2 block min-h-11 w-full
                            rounded-lg border px-3 py-2
                            text-sm outline-none transition
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

                <div class="flex shrink-0 flex-wrap gap-2">
                    <button
                        data-theme-primary-button
                        type="submit"
                        class="
                            inline-flex min-h-11 items-center
                            justify-center rounded-lg border
                            px-5 py-2 text-sm font-semibold
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
                                px-5 py-2 text-sm font-semibold
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

    <section
        data-theme-result-section
        data-theme-result-list-panel
    >
        <div class="mx-auto max-w-7xl px-4 pb-12 pt-5">
            <div
                class="
                    mb-4 flex flex-wrap
                    items-center justify-between gap-3
                "
            >
                <p data-theme-muted class="text-sm">
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
                @php
                    $primaryMarket = $markets->first();

                    $primaryResult = $primaryMarket
                        ? $primaryMarket->getRelation('latestResult')
                        : null;

                    $primaryStatus = $primaryMarket
                        ? $statuses->get(
                            $primaryMarket->getKey(),
                            [
                                'key' => 'unknown',
                                'label' => 'Status tidak tersedia',
                            ],
                        )
                        : [
                            'key' => 'unknown',
                            'label' => 'Status tidak tersedia',
                        ];
                @endphp

                <div data-theme-result-master-detail>
                    <aside
                        data-theme-result-master-panel
                        data-theme-surface
                        class="
                            min-w-0 overflow-hidden
                            rounded-xl border
                        "
                    >
                        <header class="border-b px-4 py-4">
                            <p
                                data-theme-accent
                                class="
                                    text-xs font-semibold uppercase
                                    tracking-[0.16em]
                                "
                            >
                                Pasaran Terbaru
                            </p>

                            <h2 class="mt-1 text-lg font-bold">
                                Data Result
                            </h2>
                        </header>

                        <div data-theme-result-master-list>
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

                                    $isPrimary = $primaryMarket
                                        && $primaryMarket->getKey()
                                            === $market->getKey();
                                @endphp

                                <a
                                    data-theme-result-master-row
                                    @if ($isPrimary)
                                        data-theme-result-master-active
                                    @endif
                                    href="{{ route('results.history', [
                                        'marketSlug' => $market->slug,
                                    ]) }}"
                                    class="
                                        block border-b px-4 py-4
                                        transition last:border-b-0
                                    "
                                >
                                    <div
                                        class="
                                            flex items-start
                                            justify-between gap-3
                                        "
                                    >
                                        <div class="min-w-0">
                                            <div
                                                class="
                                                    flex min-w-0
                                                    items-center gap-2
                                                "
                                            >
                                                <span
                                                    data-theme-accent
                                                    class="
                                                        shrink-0 text-xs
                                                        font-bold
                                                    "
                                                >
                                                    {{ $market->code }}
                                                </span>

                                                <h3
                                                    class="
                                                        truncate text-sm
                                                        font-bold
                                                    "
                                                >
                                                    {{ $market->name }}
                                                </h3>
                                            </div>

                                            @if ($latestResult)
                                                <p
                                                    data-theme-result-number
                                                    class="
                                                        mt-2 truncate
                                                        font-mono text-xl
                                                        font-black
                                                        tracking-[0.08em]
                                                    "
                                                >
                                                    {{ $latestResult->winning_numbers }}
                                                </p>

                                                <time
                                                    data-theme-muted
                                                    datetime="{{ $latestResult->result_date->format('Y-m-d') }}"
                                                    class="mt-1 block text-xs"
                                                >
                                                    {{ $latestResult->result_date->translatedFormat('d M Y') }}
                                                </time>
                                            @else
                                                <p
                                                    data-theme-muted
                                                    class="mt-2 text-xs"
                                                >
                                                    Belum ada result.
                                                </p>
                                            @endif
                                        </div>

                                        <span
                                            data-theme-market-status="{{ $status['key'] }}"
                                            class="
                                                shrink-0 rounded-full
                                                border px-2 py-1
                                                text-[11px] font-semibold
                                            "
                                        >
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </aside>

                    <div
                        data-theme-result-primary-panel
                        class="min-w-0"
                    >
                        <article
                            data-theme-result-primary-card
                            data-theme-surface
                            class="overflow-hidden rounded-xl border"
                        >
                            <header
                                class="
                                    border-b px-5 py-5
                                    md:px-6 md:py-6
                                "
                            >
                                <div
                                    class="
                                        flex flex-col gap-4
                                        sm:flex-row
                                        sm:items-start
                                        sm:justify-between
                                    "
                                >
                                    <div class="min-w-0">
                                        <p
                                            data-theme-accent
                                            class="
                                                text-xs font-semibold
                                                uppercase
                                                tracking-[0.18em]
                                            "
                                        >
                                            Detail Result Terbaru
                                        </p>

                                        <h2
                                            class="
                                                mt-2 text-2xl font-black
                                                md:text-3xl
                                            "
                                        >
                                            {{ $primaryMarket->name }}
                                        </h2>

                                        <p
                                            data-theme-muted
                                            class="mt-2 text-sm"
                                        >
                                            Kode pasaran:
                                            {{ $primaryMarket->code }}
                                        </p>
                                    </div>

                                    <span
                                        data-theme-market-status="{{ $primaryStatus['key'] }}"
                                        class="
                                            self-start rounded-full
                                            border px-3 py-1.5
                                            text-xs font-semibold
                                        "
                                    >
                                        {{ $primaryStatus['label'] }}
                                    </span>
                                </div>
                            </header>

                            <div class="p-5 md:p-6">
                                <div
                                    data-theme-result-number-panel
                                    class="
                                        rounded-xl border
                                        px-5 py-8 text-center
                                        md:px-8 md:py-10
                                    "
                                >
                                    <p
                                        data-theme-muted
                                        class="
                                            text-xs font-semibold
                                            uppercase tracking-[0.18em]
                                        "
                                    >
                                        Result Terbaru
                                    </p>

                                    @if ($primaryResult)
                                        <p
                                            data-theme-result-number
                                            class="
                                                mt-4 whitespace-pre-line
                                                break-words font-mono
                                                text-4xl font-black
                                                leading-tight
                                                tracking-[0.12em]
                                                sm:text-5xl lg:text-6xl
                                            "
                                        >
                                            {{ $primaryResult->winning_numbers }}
                                        </p>
                                    @else
                                        <p
                                            data-theme-muted
                                            class="
                                                mt-5 text-base
                                                font-semibold
                                            "
                                        >
                                            Belum ada result.
                                        </p>
                                    @endif
                                </div>

                                <dl
                                    class="
                                        mt-4 grid gap-3
                                        sm:grid-cols-3
                                    "
                                >
                                    <div
                                        data-theme-result-meta
                                        class="rounded-lg border p-4"
                                    >
                                        <dt data-theme-muted class="text-xs">
                                            Pasaran
                                        </dt>

                                        <dd class="mt-1 font-semibold">
                                            {{ $primaryMarket->name }}
                                        </dd>
                                    </div>

                                    <div
                                        data-theme-result-meta
                                        class="rounded-lg border p-4"
                                    >
                                        <dt data-theme-muted class="text-xs">
                                            Tanggal
                                        </dt>

                                        <dd class="mt-1 font-semibold">
                                            @if ($primaryResult)
                                                {{ $primaryResult->result_date->translatedFormat('d M Y') }}
                                            @else
                                                —
                                            @endif
                                        </dd>
                                    </div>

                                    <div
                                        data-theme-result-meta
                                        class="rounded-lg border p-4"
                                    >
                                        <dt data-theme-muted class="text-xs">
                                            History
                                        </dt>

                                        <dd class="mt-1 font-semibold">
                                            {{ $primaryMarket->results_count }}
                                            result
                                        </dd>
                                    </div>
                                </dl>

                                <a
                                    data-theme-primary-button
                                    href="{{ route('results.history', [
                                        'marketSlug' => $primaryMarket->slug,
                                    ]) }}"
                                    class="
                                        mt-5 inline-flex min-h-11
                                        w-full items-center
                                        justify-center rounded-lg
                                        border px-5 py-3
                                        text-center text-sm
                                        font-bold transition
                                        hover:opacity-90
                                    "
                                >
                                    Lihat History
                                    {{ $primaryMarket->name }}
                                </a>
                            </div>
                        </article>
                    </div>
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
                    class="mt-8"
                    aria-label="Navigasi halaman pasaran result"
                >
                    {{ $markets->links() }}
                </nav>
            @endif
        </div>
    </section>
</div>
@endsection
