@extends('frontend.layouts.app')

@section('title', 'Live Draw Togel Hari Ini | '.config('app.name'))

@section(
    'description',
    'Saksikan live draw togel dari berbagai pasaran aktif dan lihat jadwal draw terbaru melalui '.config('app.name').'.'
)

@section('metadata')
    <link rel="canonical" href="{{ route('live-draw.index') }}">

    <meta
        property="og:title"
        content="Live Draw Togel Hari Ini | {{ config('app.name') }}"
    >

    <meta
        property="og:description"
        content="Saksikan live draw togel dari berbagai pasaran aktif dan lihat jadwal draw terbaru."
    >

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('live-draw.index') }}">
@endsection

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Live Draw
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Live Draw Togel Hari Ini
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Saksikan siaran live draw dari pasaran yang sedang aktif dan
            periksa jadwal draw berikutnya dalam zona waktu masing-masing.
        </p>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-7">
            <p class="text-sm text-slate-400">
                Menampilkan
                <span class="font-semibold text-white">
                    {{ $liveDraws->count() }}
                </span>
                pasaran live draw
            </p>
        </div>

        @forelse ($liveDraws as $liveDraw)
            @if ($loop->first)
                <div class="grid gap-8 lg:grid-cols-2">
            @endif

            @php
                $embedUrl = $liveDraw->publicEmbedUrl();

                $statusLabel = match ($liveDraw->status) {
                    'live' => 'Sedang Live',
                    'scheduled' => 'Terjadwal',
                    'finished' => 'Selesai',
                    default => 'Offline',
                };

                $statusClasses = match ($liveDraw->status) {
                    'live' => 'border-red-400/40 bg-red-950/50 text-red-300',
                    'scheduled' => 'border-amber-400/40 bg-amber-950/40 text-amber-300',
                    'finished' => 'border-emerald-400/40 bg-emerald-950/40 text-emerald-300',
                    default => 'border-slate-600 bg-slate-950 text-slate-300',
                };

                $dayLabels = collect($liveDraw->draw_days ?? [])
                    ->map(fn ($day) => match ((int) $day) {
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        7 => 'Minggu',
                        default => null,
                    })
                    ->filter()
                    ->implode(', ');
            @endphp

            <article class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900">
                <div
                    class="relative flex min-h-40 items-end overflow-hidden border-b border-slate-800 bg-slate-950 p-6"
                    @if (filled($liveDraw->background_path))
                        style="
                            background-image:
                                linear-gradient(
                                    to top,
                                    rgba(2, 6, 23, 0.96),
                                    rgba(2, 6, 23, 0.25)
                                ),
                                url('{{ asset('storage/'.$liveDraw->background_path) }}');
                            background-position:
                                {{ str_replace('-', ' ', $liveDraw->background_focal_point) }};
                            background-size: cover;
                        "
                    @endif
                >
                    <div class="relative z-10 flex w-full items-end justify-between gap-5">
                        <div class="flex min-w-0 items-center gap-4">
                            @if (filled($liveDraw->logo_path))
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-white/10 bg-white/95 p-2">
                                    <img
                                        src="{{ asset('storage/'.$liveDraw->logo_path) }}"
                                        alt="Logo {{ $liveDraw->market->name }}"
                                        class="max-h-full max-w-full object-contain"
                                        loading="lazy"
                                    >
                                </div>
                            @endif

                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">
                                    {{ $liveDraw->market->code }}
                                </p>

                                <h2 class="mt-1 truncate text-2xl font-bold text-white">
                                    {{ $liveDraw->title }}
                                </h2>

                                <p class="mt-1 text-sm text-slate-300">
                                    {{ $liveDraw->market->name }}
                                </p>
                            </div>
                        </div>

                        <span class="shrink-0 rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    @if (filled($liveDraw->headline))
                        <p class="text-base font-semibold leading-7 text-white">
                            {{ $liveDraw->headline }}
                        </p>
                    @endif

                    <dl class="mt-5 grid gap-4 rounded-xl border border-slate-800 bg-slate-950 p-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Hari draw
                            </dt>

                            <dd class="mt-1 text-sm font-medium text-slate-200">
                                {{ $dayLabels !== '' ? $dayLabels : 'Belum diatur' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Jam draw
                            </dt>

                            <dd class="mt-1 text-sm font-medium text-slate-200">
                                @if (filled($liveDraw->draw_time))
                                    {{ substr($liveDraw->draw_time, 0, 5) }}
                                    {{ $liveDraw->timezone }}
                                @else
                                    Belum diatur
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if ($embedUrl !== null)
                        <div class="mt-6 overflow-hidden rounded-xl border border-slate-700 bg-black">
                            <div class="aspect-video">
                                <iframe
                                    src="{{ $embedUrl }}"
                                    title="Live Draw {{ $liveDraw->market->name }}"
                                    class="h-full w-full"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                    referrerpolicy="strict-origin-when-cross-origin"
                                ></iframe>
                            </div>
                        </div>
                    @elseif (
                        $liveDraw->isLive()
                        && $liveDraw->stream_type === 'url'
                        && filled($liveDraw->source_url)
                    )
                        <a
                            href="{{ $liveDraw->source_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-6 inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300"
                        >
                            Buka Siaran Live
                        </a>
                    @elseif (
                        $liveDraw->isLive()
                        && $liveDraw->stream_type === 'hls'
                    )
                        <div class="mt-6 rounded-xl border border-amber-400/30 bg-amber-950/20 p-5 text-sm leading-6 text-amber-200">
                            Siaran HLS sedang aktif. Pemutar HLS akan tersedia
                            pada tahap pengembangan berikutnya.
                        </div>
                    @elseif ($liveDraw->status === 'scheduled')
                        <div class="mt-6 rounded-xl border border-amber-400/30 bg-amber-950/20 p-5 text-sm leading-6 text-amber-200">
                            Live draw belum dimulai. Silakan kembali sesuai jadwal
                            yang tercantum.
                        </div>
                    @elseif ($liveDraw->status === 'finished')
                        <div class="mt-6 rounded-xl border border-emerald-400/30 bg-emerald-950/20 p-5 text-sm leading-6 text-emerald-200">
                            Live draw telah selesai. Hasil resmi dapat dilihat
                            melalui halaman Data Result.
                        </div>

                        <a
                            href="{{ route('results.index', [
                                'market' => $liveDraw->market->slug,
                            ]) }}"
                            class="mt-4 inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-emerald-400 px-5 py-3 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400 hover:text-slate-950"
                        >
                            Lihat Data Result
                        </a>
                    @else
                        <div class="mt-6 rounded-xl border border-slate-700 bg-slate-950 p-5 text-sm leading-6 text-slate-400">
                            Siaran live draw sedang tidak tersedia.
                        </div>
                    @endif

                    @if (filled($liveDraw->footer))
                        <p class="mt-5 whitespace-pre-line text-sm leading-6 text-slate-400">
                            {{ $liveDraw->footer }}
                        </p>
                    @endif
                </div>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Belum ada Live Draw
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Konfigurasi Live Draw dari pasaran aktif akan ditampilkan
                    pada halaman ini.
                </p>
            </div>
        @endforelse
    </div>
</section>
@endsection
