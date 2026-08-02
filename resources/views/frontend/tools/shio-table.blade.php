@extends('frontend.layouts.app')
@section('title', 'Tabel Shio | '.config('app.name'))
@section('description', 'Tabel shio dan kelompok angka untuk periode aktif.')
@section('metadata')
<link rel="canonical" href="{{ route('tools.shio-table') }}">
<meta property="og:title" content="Tabel Shio | {{ config('app.name') }}">
<meta property="og:description" content="Tabel shio dan kelompok angka untuk periode aktif.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('tools.shio-table') }}">
@endsection
@section('content')
@if ($period && $bannerUrl)
    <section class="border-b border-slate-800 bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 py-8">
            <figure class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 shadow-xl">
                <img
                    src="{{ $bannerUrl }}"
                    alt="Banner {{ $period->title }}"
                    class="h-auto w-full object-cover"
                    loading="eager"
                >
            </figure>
        </div>
    </section>
@endif
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Alat Togel</p>
        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Tabel Shio</h1>
        <p class="mt-5 max-w-3xl text-slate-300">Referensi kelompok angka shio untuk periode yang sedang berlaku.</p>
    </div>
</section>
<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        @if($period)
            <div class="mb-8 rounded-xl border border-slate-800 bg-slate-900 p-6">
                <h2 class="text-2xl font-bold text-white">{{ $period->title }}</h2>
                <p class="mt-2 text-sm text-slate-400">Periode {{ $period->start_date->format('d M Y') }} sampai {{ $period->end_date->format('d M Y') }}</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($period->shios as $shio)
                    <article class="rounded-xl border border-slate-800 bg-slate-900 p-5">
                        <h3 class="text-lg font-bold text-amber-400">{{ $shio->name }}</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($shio->numbers ?? [] as $number)
                                <span class="inline-flex min-w-10 justify-center rounded-md bg-slate-800 px-3 py-2 text-sm font-semibold text-white">{{ $number }}</span>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center"><h2 class="text-xl font-semibold text-white">Tabel shio belum tersedia</h2><p class="mt-3 text-sm text-slate-400">Periode shio yang diterbitkan akan tampil di halaman ini.</p></div>
        @endif
    </div>
</section>
@endsection
