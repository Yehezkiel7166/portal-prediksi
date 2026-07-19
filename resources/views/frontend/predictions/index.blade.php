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
            Temukan prediksi terbaru dari berbagai pasaran aktif. Seluruh data
            yang ditampilkan telah diterbitkan melalui sistem administrasi resmi.
        </p>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
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
                    Belum ada prediksi yang diterbitkan
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Prediksi terbaru akan tampil pada halaman ini setelah
                    diterbitkan melalui panel administrasi.
                </p>
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
