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
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Data Result
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Result Terbaru Setiap Pasaran
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Setiap pasaran hanya ditampilkan satu kali.
            Tekan Detail untuk membuka seluruh history result.
        </p>
    </div>
</section>

<section class="border-b border-slate-800 bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <form
            method="GET"
            action="{{ route('results.index') }}"
            class="flex flex-col gap-4 rounded-xl border border-slate-800 bg-slate-900 p-6 md:flex-row md:items-end"
        >
            <div class="min-w-0 flex-1">
                <label
                    for="market"
                    class="block text-sm font-medium text-slate-200"
                >
                    Pasaran
                </label>

                <select
                    id="market"
                    name="market"
                    class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white outline-none transition focus:border-amber-400"
                >
                    <option value="">Semua pasaran</option>

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

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex min-h-11 items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300"
                >
                    Terapkan
                </button>

                @if ($filters['market'] !== null)
                    <a
                        href="{{ route('results.index') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:border-slate-500 hover:text-white"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-7 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-400">
                Menampilkan
                <span class="font-semibold text-white">
                    {{ $markets->total() }}
                </span>
                pasaran
            </p>

            @if ($filters['market'] !== null)
                <p class="text-sm text-amber-400">
                    Filter aktif
                </p>
            @endif
        </div>

        @forelse ($markets as $market)
            @if ($loop->first)
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @endif

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

                $statusClass = match ($status['key']) {
                    'open' =>
                        'border-emerald-500/40 bg-emerald-950/40 text-emerald-300',
                    'closed' =>
                        'border-red-500/40 bg-red-950/40 text-red-300',
                    'holiday' =>
                        'border-amber-500/40 bg-amber-950/40 text-amber-300',
                    default =>
                        'border-slate-700 bg-slate-950 text-slate-400',
                };
            @endphp

            <article class="flex h-full flex-col rounded-xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pasaran
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-amber-400">
                            {{ $market->name }}
                        </h2>
                    </div>

                    <span class="rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs font-medium text-slate-300">
                        {{ $market->code }}
                    </span>
                </div>

                <div class="mt-4">
                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                        {{ $status['label'] }}
                    </span>
                </div>

                <div class="mt-5 border-t border-slate-800 pt-5">
                    <p class="text-sm text-slate-400">
                        Result terbaru
                    </p>

                    @if ($latestResult)
                        <div class="mt-2 whitespace-pre-line break-words rounded-lg border border-amber-400/20 bg-slate-950 p-4 text-center text-xl font-bold leading-7 text-white">
                            {{ $latestResult->winning_numbers }}
                        </div>

                        <time
                            datetime="{{ $latestResult->result_date->format('Y-m-d') }}"
                            class="mt-3 block text-sm font-semibold text-slate-200"
                        >
                            {{ $latestResult->result_date->translatedFormat('d F Y') }}
                        </time>
                    @else
                        <div class="mt-2 rounded-lg border border-dashed border-slate-700 bg-slate-950 p-4 text-sm text-slate-400">
                            Belum ada result.
                        </div>
                    @endif
                </div>

                <div class="mt-auto pt-6">
                    <p class="mb-4 text-xs text-slate-500">
                        {{ $market->results_count }}
                        history result
                    </p>

                    <a
                        href="{{ route('results.history', [
                            'marketSlug' => $market->slug,
                        ]) }}"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300"
                    >
                        Detail
                    </a>
                </div>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Belum ada pasaran aktif
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Tidak ada pasaran yang sesuai dengan filter.
                </p>
            </div>
        @endforelse

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
