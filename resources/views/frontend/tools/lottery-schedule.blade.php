@extends('frontend.layouts.app')

@section(
    'title',
    'Jadwal Togel | '.$siteConfiguration->siteName
)

@section(
    'description',
    'Jadwal tutup, hasil, dan buka pasaran togel aktif.'
)

@push('head')
    <link
        rel="canonical"
        href="{{ route('tools.lottery-schedule') }}"
    >

    <meta
        property="og:title"
        content="Jadwal Togel | {{ $siteConfiguration->siteName }}"
    >

    <meta
        property="og:description"
        content="Jadwal tutup, hasil, dan buka pasaran togel aktif."
    >

    <meta property="og:type" content="website">

    <meta
        property="og:url"
        content="{{ route('tools.lottery-schedule') }}"
    >
@endpush

@section('content')
<section data-theme-tool="lottery-schedule" class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Alat Togel
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Jadwal Togel
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Jadwal tutup, hasil, dan buka menggunakan konfigurasi
            pasaran sebagai sumber kebenaran.
        </p>
    </div>
</section>

<section data-theme-tool="lottery-schedule" class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div data-theme-surface class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-900/80 text-left text-xs uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-4">Pasaran</th>
                        <th class="px-5 py-4">Tutup</th>
                        <th class="px-5 py-4">Hasil</th>
                        <th class="px-5 py-4">Buka</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Link</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800">
                    @forelse ($markets as $market)
                        <tr>
                            <td class="px-5 py-4">
                                <span class="font-semibold text-white">
                                    {{ $market->name }}
                                </span>

                                <span class="ml-2 text-xs text-slate-500">
                                    {{ $market->code }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-300">
                                {{ $market->close_time ?: '—' }}
                            </td>

                            <td class="px-5 py-4 text-slate-300">
                                {{ $market->result_time ?: '—' }}
                            </td>

                            <td class="px-5 py-4 text-slate-300">
                                {{ $market->open_time ?: '—' }}
                            </td>

                            <td class="px-5 py-4">
                                <span
                                    data-theme-schedule-status="{{ $market->schedule_status['key'] }}"
                                    @class([
                                        'inline-flex rounded-full border px-3 py-1 text-xs font-semibold',
                                        'border-emerald-400/40 text-emerald-300' =>
                                            $market->schedule_status['key'] === 'open',
                                        'border-amber-400/40 text-amber-300' =>
                                            in_array(
                                                $market->schedule_status['key'],
                                                [
                                                    'live',
                                                    'result_available',
                                                    'upcoming',
                                                ],
                                                true,
                                            ),
                                        'border-slate-500/40 text-slate-300' =>
                                            !in_array(
                                                $market->schedule_status['key'],
                                                [
                                                    'open',
                                                    'live',
                                                    'result_available',
                                                    'upcoming',
                                                ],
                                                true,
                                            ),
                                    ])
                                >
                                    {{ $market->schedule_status['label'] }}
                                </span>

                                <p class="mt-2 max-w-xs text-xs text-slate-400">
                                    {{ $market->schedule_status['description'] }}
                                </p>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex min-w-48 flex-wrap gap-2">
                                    <a
                                        href="{{ route('predictions.index') }}"
                                        class="rounded-lg border border-amber-400/40 px-3 py-1.5 text-xs font-semibold text-amber-300 hover:bg-amber-400/10"
                                    >
                                        Prediksi
                                    </a>

                                    <a
                                        href="{{ route('results.index') }}"
                                        class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-800"
                                    >
                                        Result
                                    </a>

                                    <a
                                        href="{{ route('live-draw.index') }}"
                                        class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-800"
                                    >
                                        Live Draw
                                    </a>

                                    @if (filled($market->official_url))
                                        <a
                                            href="{{ $market->official_url }}"
                                            target="_blank"
                                            rel="noopener noreferrer nofollow"
                                            class="rounded-lg border border-slate-600 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-800"
                                        >
                                            Link Official
                                        </a>
                                    @else
                                        <span
                                            class="inline-flex cursor-not-allowed rounded-lg border border-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-600"
                                            title="Link resmi belum ditambahkan"
                                        >
                                            —
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-16 text-center"
                            >
                                <h2 class="text-xl font-semibold text-white">
                                    Belum ada jadwal
                                </h2>

                                <p class="mt-3 text-sm text-slate-400">
                                    Jadwal pasaran aktif akan tampil setelah
                                    dikonfigurasi administrator.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
