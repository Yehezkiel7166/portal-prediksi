@extends('frontend.layouts.app')

@section('title', 'Slot Gacor dan RTP Terbaru | '.config('app.name'))
@section('description', 'Daftar Slot Gacor dan snapshot RTP terbaru yang dipublikasikan oleh '.config('app.name').'.')
@section('metadata')
    <link rel="canonical" href="{{ route('slot-gacor.index') }}">
    <meta property="og:title" content="Slot Gacor dan RTP Terbaru | {{ config('app.name') }}">
    <meta property="og:description" content="Daftar Slot Gacor dan snapshot RTP terbaru yang telah dipublikasikan.">
    <meta property="og:url" content="{{ route('slot-gacor.index') }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
<section data-theme-special="slot-gacor" class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">RTP</p>
        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Slot Gacor dan RTP Terbaru</h1>
        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300">Data RTP ditampilkan sebagai snapshot historis. Nilai terbaru tidak menjamin hasil permainan berikutnya.</p>
    </div>
</section>
<section data-theme-special="slot-gacor" class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        @forelse ($slots as $slot)
            @if ($loop->first)<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">@endif
            <article data-theme-surface class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                <div class="aspect-[4/3] bg-slate-950">
                    @if (filled($slot->image_url))
                        <img src="{{ $slot->image_url }}" alt="{{ $slot->game_name }}" class="h-full w-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-full items-center justify-center text-sm font-semibold uppercase tracking-widest text-slate-600">Slot Gacor</div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">{{ $slot->provider_name }}</p>
                    <h2 class="mt-2 text-lg font-bold text-white">{{ $slot->game_name }}</h2>
                    @if ($slot->latestSnapshot)
                        <p data-theme-rtp-value class="mt-4 text-3xl font-bold text-emerald-400">{{ number_format((float) $slot->latestSnapshot->rtp_value, 2) }}%</p>
                        <p class="mt-2 text-xs text-slate-500">Snapshot {{ $slot->latestSnapshot->captured_at->translatedFormat('d M Y H:i') }}</p>
                    @else
                        <p class="mt-4 text-sm text-slate-400">RTP belum tersedia.</p>
                    @endif
                </div>
            </article>
            @if ($loop->last)</div>@endif
        @empty
            <div data-theme-surface class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">Belum ada data Slot Gacor</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">Data RTP yang aktif dan telah dipublikasikan akan ditampilkan di halaman ini.</p>
            </div>
        @endforelse
        @if ($slots->hasPages())<nav class="mt-10" aria-label="Navigasi halaman Slot Gacor">{{ $slots->links() }}</nav>@endif
    </div>
</section>
@endsection
