@extends('frontend.layouts.app')

@section('title', 'Prediksi Togel Terbaru | '.config('app.name'))

@section(
    'description',
    'Daftar prediksi togel terbaru dari berbagai pasaran aktif yang diterbitkan melalui '.config('app.name').'.'
)

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Prediksi Togel
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Prediksi Togel Terbaru
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Temukan prediksi terbaru dari berbagai pasaran aktif. Gunakan filter
            pasaran dan tanggal untuk mempersempit hasil yang ditampilkan.
        </p>
    </div>
</section>

<section class="border-b border-slate-800 bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <form
            method="GET"
            action="{{ route('predictions.index') }}"
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
                    Tanggal prediksi
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
                        href="{{ route('predictions.index') }}"
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
                    {{ $predictions->total() }}
                </span>
                prediksi
            </p>

            @if ($filters['market'] !== null || $filters['date'] !== null)
                <p class="text-sm text-amber-400">
                    Filter aktif
                </p>
            @endif
        </div>

        @forelse ($predictions as $prediction)
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
                            {{ $prediction->market->name }}
                        </h2>
                    </div>

                    <span class="rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs font-medium text-slate-300">
                        {{ $prediction->market->code }}
                    </span>
                </div>

                <div class="mt-5 border-t border-slate-800 pt-5">
                    <p class="text-sm text-slate-400">
                        Tanggal prediksi
                    </p>

                    <time
                        datetime="{{ $prediction->prediction_date->format('Y-m-d') }}"
                        class="mt-1 block font-semibold text-slate-100"
                    >
                        {{ $prediction->prediction_date->translatedFormat('d F Y') }}
                    </time>
                </div>

                <div class="mt-5">
                    <p class="text-sm text-slate-400">
                        Angka prediksi
                    </p>

                    <div class="mt-2 whitespace-pre-line break-words rounded-lg border border-amber-400/20 bg-slate-950 p-4 font-semibold leading-7 text-white">
                        {{ $prediction->predicted_numbers }}
                    </div>
                </div>

                @if (filled($prediction->notes))
                    <div class="mt-5">
                        <p class="text-sm text-slate-400">
                            Catatan
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-300">
                            {{ $prediction->notes }}
                        </p>
                    </div>
                @endif

                <p class="mt-auto pt-6 text-xs text-slate-500">
                    Diterbitkan
                    <time datetime="{{ $prediction->published_at->toIso8601String() }}">
                        {{ $prediction->published_at->translatedFormat('d F Y H:i') }}
                    </time>
                </p>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Tidak ada prediksi yang ditemukan
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Belum ada prediksi publik yang sesuai dengan pilihan pasaran
                    dan tanggal saat ini.
                </p>

                @if ($filters['market'] !== null || $filters['date'] !== null)
                    <a
                        href="{{ route('predictions.index') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 transition hover:bg-amber-400 hover:text-slate-950"
                    >
                        Tampilkan Semua Prediksi
                    </a>
                @endif
            </div>
        @endforelse

        @if ($predictions->hasPages())
            <nav class="mt-10" aria-label="Navigasi halaman prediksi">
                {{ $predictions->links() }}
            </nav>
        @endif
    </div>
</section>
@endsection
