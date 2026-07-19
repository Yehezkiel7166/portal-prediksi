@extends('frontend.layouts.app')

@section('title', 'Data Result Togel Terbaru | '.config('app.name'))

@section(
    'description',
    'Daftar hasil togel terbaru dari berbagai pasaran aktif yang tersedia melalui '.config('app.name').'.'
)

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Data Result
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Data Result Togel Terbaru
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Lihat hasil terbaru dari berbagai pasaran aktif. Gunakan filter
            pasaran dan tanggal untuk mempersempit data yang ditampilkan.
        </p>
    </div>
</section>

<section class="border-b border-slate-800 bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <form
            method="GET"
            action="{{ route('results.index') }}"
            class="grid gap-5 rounded-xl border border-slate-800 bg-slate-900 p-6 md:grid-cols-2 lg:grid-cols-[1fr_1fr_auto]"
        >
            <div>
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

                    @foreach ($markets as $market)
                        <option
                            value="{{ $market->slug }}"
                            @selected($filters['market'] === $market->slug)
                        >
                            {{ $market->name }} ({{ $market->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label
                    for="date"
                    class="block text-sm font-medium text-slate-200"
                >
                    Tanggal result
                </label>

                <input
                    id="date"
                    name="date"
                    type="date"
                    value="{{ $filters['date'] }}"
                    class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white outline-none transition focus:border-amber-400"
                >
            </div>

            <div class="flex items-end gap-3">
                <button
                    type="submit"
                    class="inline-flex min-h-11 items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300"
                >
                    Terapkan Filter
                </button>

                @if ($filters['market'] !== null || $filters['date'] !== null)
                    <a
                        href="{{ route('results.index') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:border-slate-500 hover:text-white"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @if ($errors->any())
            <div
                role="alert"
                class="mt-5 rounded-lg border border-red-500/40 bg-red-950/30 px-5 py-4 text-sm text-red-200"
            >
                <p class="font-semibold">Filter tidak dapat diproses.</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-7 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-400">
                Menampilkan
                <span class="font-semibold text-white">
                    {{ $results->total() }}
                </span>
                result
            </p>

            @if ($filters['market'] !== null || $filters['date'] !== null)
                <p class="text-sm text-amber-400">
                    Filter aktif
                </p>
            @endif
        </div>

        @forelse ($results as $result)
            @if ($loop->first)
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @endif

            <article class="flex h-full flex-col rounded-xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pasaran
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-amber-400">
                            {{ $result->market->name }}
                        </h2>
                    </div>

                    <span class="rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs font-medium text-slate-300">
                        {{ $result->market->code }}
                    </span>
                </div>

                <div class="mt-5 border-t border-slate-800 pt-5">
                    <p class="text-sm text-slate-400">
                        Tanggal result
                    </p>

                    <time
                        datetime="{{ $result->result_date->format('Y-m-d') }}"
                        class="mt-1 block font-semibold text-slate-100"
                    >
                        {{ $result->result_date->translatedFormat('d F Y') }}
                    </time>
                </div>

                <div class="mt-5">
                    <p class="text-sm text-slate-400">
                        Hasil
                    </p>

                    <div class="mt-2 whitespace-pre-line break-words rounded-lg border border-amber-400/20 bg-slate-950 p-4 font-semibold leading-7 text-white">
                        {{ $result->winning_numbers }}
                    </div>
                </div>

                @if (filled($result->notes))
                    <div class="mt-5">
                        <p class="text-sm text-slate-400">
                            Catatan
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-300">
                            {{ $result->notes }}
                        </p>
                    </div>
                @endif
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Tidak ada data result yang ditemukan
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Belum ada data result yang sesuai dengan pilihan pasaran
                    dan tanggal saat ini.
                </p>

                @if ($filters['market'] !== null || $filters['date'] !== null)
                    <a
                        href="{{ route('results.index') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 transition hover:bg-amber-400 hover:text-slate-950"
                    >
                        Tampilkan Semua Result
                    </a>
                @endif
            </div>
        @endforelse

        @if ($results->hasPages())
            <nav class="mt-10" aria-label="Navigasi halaman data result">
                {{ $results->links() }}
            </nav>
        @endif
    </div>
</section>
@endsection
