@extends('frontend.layouts.app')
@section('title', 'Panduan | '.config('app.name'))
@section('description', 'Panduan penggunaan dan informasi penting dari '.config('app.name').'.')
@section('metadata')
<link rel="canonical" href="{{ route('guides.index') }}">
<meta property="og:title" content="Panduan | {{ config('app.name') }}">
<meta property="og:description" content="Panduan penggunaan dan informasi penting dari {{ config('app.name') }}.">
<meta property="og:type" content="website"><meta property="og:url" content="{{ route('guides.index') }}">
@endsection
@section('content')
<section class="border-b border-slate-800 bg-slate-900"><div class="mx-auto max-w-7xl px-4 py-12 md:py-16"><p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Panduan</p><h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Pusat Panduan</h1><p class="mt-5 max-w-3xl text-slate-300">Temukan petunjuk penggunaan, keamanan, dan informasi operasional yang telah diterbitkan.</p></div></section>
<section class="bg-slate-950"><div class="mx-auto max-w-7xl px-4 py-12"><div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
@forelse($guides as $guide)<article class="rounded-xl border border-slate-800 bg-slate-900 p-6"><p class="text-xs font-semibold uppercase tracking-widest text-amber-400">{{ $guide->category ?: 'Panduan' }}</p><h2 class="mt-3 text-xl font-bold"><a class="hover:text-amber-400" href="{{ route('guides.show', $guide->slug) }}">{{ $guide->title }}</a></h2>@if(filled($guide->excerpt))<p class="mt-4 text-sm leading-6 text-slate-300">{{ $guide->excerpt }}</p>@endif<a class="mt-6 inline-flex rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 hover:bg-amber-400 hover:text-slate-950" href="{{ route('guides.show', $guide->slug) }}">Baca Panduan</a></article>
@empty<div class="col-span-full rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center"><h2 class="text-xl font-semibold">Belum ada panduan</h2><p class="mt-3 text-sm text-slate-400">Panduan yang diterbitkan akan tampil di halaman ini.</p></div>@endforelse
</div>@if($guides->hasPages())<nav class="mt-10" aria-label="Navigasi halaman panduan">{{ $guides->links() }}</nav>@endif</div></section>
@endsection
