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
            Setiap pasaran hanya ditampilkan satu kali. Tekan Detail untuk
            membuka seluruh history result.
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
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
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

        @if ($markets->isNotEmpty())
            <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                <div class="hidden border-b border-slate-800 bg-slate-950/70 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 md:grid md:grid-cols-[minmax(180px,1.5fr)_minmax(180px,1.3fr)_140px_120px_100px] md:gap-5">
                    <div>Pasaran</div>
                    <div>Result terbaru</div>
                    <div>Tanggal</div>
                    <div>Status</div>
                    <div class="text-right">Aksi</div>
                </div>

                <div class="divide-y divide-slate-800">
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

                        <article class="px-5 py-5 transition hover:bg-slate-800/30 md:grid md:grid-cols-[minmax(180px,1.5fr)_minmax(180px,1.3fr)_140px_120px_100px] md:items-center md:gap-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="font-bold text-amber-400">
                                        {{ $market->name }}
                                    </h2>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $market->code }}
                                        ·
                                        {{ $market->results_count }}
                                        history
                                    </p>
                                </div>

                                <span class="rounded-full border border-slate-700 bg-slate-950 px-2.5 py-1 text-xs text-slate-300 md:hidden">
                                    {{ $market->code }}
                                </span>
                            </div>

                            <div class="mt-4 md:mt-0">
                                <p class="mb-1 text-xs text-slate-500 md:hidden">
                                    Result terbaru
                                </p>

                                @if ($latestResult)
                                    <div class="break-words font-bold text-white">
                                        {{ $latestResult->winning_numbers }}
                                    </div>
                                @else
                                    <span class="text-sm text-slate-500">
                                        Belum ada result.
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 md:mt-0">
                                <p class="mb-1 text-xs text-slate-500 md:hidden">
                                    Tanggal
                                </p>

                                @if ($latestResult)
                                    <time
                                        datetime="{{ $latestResult->result_date->format('Y-m-d') }}"
                                        class="text-sm font-medium text-slate-200"
                                    >
                                        {{ $latestResult->result_date->translatedFormat('d M Y') }}
                                    </time>
                                @else
                                    <span class="text-sm text-slate-600">
                                        —
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 md:mt-0">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $status['label'] }}
                                </span>
                            </div>

                            <div class="mt-5 md:mt-0 md:text-right">
                                <a
                                    href="{{ route('results.history', [
                                        'marketSlug' => $market->slug,
                                    ]) }}"
                                    class="inline-flex min-h-10 w-full items-center justify-center rounded-lg bg-amber-400 px-4 py-2 text-sm font-bold text-slate-950 transition hover:bg-amber-300 md:w-auto"
                                >
                                    Detail
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Belum ada pasaran aktif
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
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
