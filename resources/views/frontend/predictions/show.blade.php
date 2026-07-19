@extends('frontend.layouts.app')

@section(
    'title',
    'Prediksi '.$prediction->market->name.' '.
        $prediction->prediction_date->format('d-m-Y').
        ' | '.config('app.name')
)

@section(
    'description',
    'Prediksi togel '.$prediction->market->name.
        ' untuk tanggal '.
        $prediction->prediction_date->translatedFormat('d F Y').
        ' yang diterbitkan melalui '.config('app.name').'.'
)

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-10 md:py-14">
        <a
            href="{{ route('predictions.index', ['market' => $prediction->market->slug]) }}"
            class="inline-flex items-center text-sm font-semibold text-amber-400 transition hover:text-amber-300"
        >
            ← Kembali ke Prediksi Togel
        </a>

        <p class="mt-7 text-sm font-semibold uppercase tracking-widest text-amber-400">
            Prediksi Togel
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Prediksi {{ $prediction->market->name }}
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Prediksi untuk pasaran {{ $prediction->market->name }}
            pada tanggal
            {{ $prediction->prediction_date->translatedFormat('d F Y') }}.
        </p>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-5xl px-4 py-12">
        <article class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
            <header class="border-b border-slate-800 p-6 md:p-8">
                <div class="flex flex-wrap items-start justify-between gap-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pasaran
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-amber-400 md:text-3xl">
                            {{ $prediction->market->name }}
                        </h2>
                    </div>

                    <span class="rounded-full border border-slate-700 bg-slate-950 px-4 py-2 text-sm font-semibold text-slate-200">
                        {{ $prediction->market->code }}
                    </span>
                </div>
            </header>

            <div class="grid gap-8 p-6 md:grid-cols-2 md:p-8">
                <section>
                    <p class="text-sm text-slate-400">
                        Tanggal prediksi
                    </p>

                    <time
                        datetime="{{ $prediction->prediction_date->format('Y-m-d') }}"
                        class="mt-2 block text-xl font-bold text-white"
                    >
                        {{ $prediction->prediction_date->translatedFormat('d F Y') }}
                    </time>
                </section>

                <section>
                    <p class="text-sm text-slate-400">
                        Zona waktu pasaran
                    </p>

                    <p class="mt-2 text-xl font-bold text-white">
                        {{ $prediction->market->timezone }}
                    </p>
                </section>
            </div>

            <section class="border-t border-slate-800 p-6 md:p-8">
                <p class="text-sm text-slate-400">
                    Angka prediksi
                </p>

                <div class="mt-3 whitespace-pre-line break-words rounded-xl border border-amber-400/20 bg-slate-950 p-5 text-lg font-bold leading-8 text-white md:text-xl">
                    {{ $prediction->predicted_numbers }}
                </div>
            </section>

            @if (filled($prediction->notes))
                <section class="border-t border-slate-800 p-6 md:p-8">
                    <p class="text-sm text-slate-400">
                        Catatan
                    </p>

                    <p class="mt-3 whitespace-pre-line leading-7 text-slate-300">
                        {{ $prediction->notes }}
                    </p>
                </section>
            @endif

            <footer class="border-t border-slate-800 bg-slate-950/60 p-6 md:px-8">
                <p class="text-sm text-slate-500">
                    Diterbitkan
                    <time datetime="{{ $prediction->published_at->toIso8601String() }}">
                        {{ $prediction->published_at->translatedFormat('d F Y H:i') }}
                    </time>
                </p>
            </footer>
        </article>
    </div>
</section>
@endsection
