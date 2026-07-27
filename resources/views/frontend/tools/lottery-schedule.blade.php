@extends('frontend.layouts.app')
@section('title', 'Jadwal Togel | '.config('app.name'))
@section('description', 'Jadwal buka, tutup, dan hasil pasaran togel aktif.')
@section('metadata')
<link rel="canonical" href="{{ route('tools.lottery-schedule') }}">
<meta property="og:title" content="Jadwal Togel | {{ config('app.name') }}">
<meta property="og:description" content="Jadwal buka, tutup, dan hasil pasaran togel aktif.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('tools.lottery-schedule') }}">
@endsection
@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Alat Togel</p>
        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Jadwal Togel</h1>
        <p class="mt-5 max-w-3xl text-slate-300">Jadwal ini menggunakan konfigurasi pasaran sebagai sumber kebenaran.</p>
    </div>
</section>
<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-900/80 text-left text-xs uppercase tracking-wider text-slate-400">
                    <tr><th class="px-5 py-4">Pasaran</th><th class="px-5 py-4">Zona Waktu</th><th class="px-5 py-4">Buka</th><th class="px-5 py-4">Tutup</th><th class="px-5 py-4">Hasil</th><th class="px-5 py-4">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($markets as $market)
                        <tr>
                            <td class="px-5 py-4"><span class="font-semibold text-white">{{ $market->name }}</span><span class="ml-2 text-xs text-slate-500">{{ $market->code }}</span></td>
                            <td class="px-5 py-4 text-slate-300">{{ $market->timezone }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $market->open_time ?: '—' }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $market->close_time ?: '—' }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $market->result_time ?: '—' }}</td>
                            <td class="px-5 py-4"><span class="inline-flex rounded-full border border-amber-400/40 px-3 py-1 text-xs font-semibold text-amber-300">{{ $market->schedule_status['label'] }}</span><p class="mt-2 max-w-xs text-xs text-slate-400">{{ $market->schedule_status['description'] }}</p></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center"><h2 class="text-xl font-semibold text-white">Belum ada jadwal</h2><p class="mt-3 text-sm text-slate-400">Jadwal pasaran aktif akan tampil setelah dikonfigurasi administrator.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
