@extends('frontend.layouts.app')
@section('title', 'Bukti Jackpot Terbaru | '.config('app.name'))
@section('description', 'Lihat bukti jackpot terbaru yang telah melalui moderasi dan dipublikasikan oleh '.config('app.name').'.')
@section('metadata')
<link rel="canonical" href="{{ route('jackpot-proofs.index') }}">
<meta property="og:title" content="Bukti Jackpot Terbaru | {{ config('app.name') }}">
<meta property="og:description" content="Lihat bukti jackpot terbaru yang telah diverifikasi dan dipublikasikan.">
<meta property="og:type" content="website"><meta property="og:url" content="{{ route('jackpot-proofs.index') }}">
@endsection
@section('content')
<section data-theme-special="jackpot-index" class="border-b border-slate-800 bg-slate-900"><div class="mx-auto max-w-7xl px-4 py-12 md:py-16"><p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Bukti Jackpot</p><h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Bukti Jackpot Terbaru</h1><p class="mt-5 max-w-3xl text-slate-300">Konten yang tampil telah melalui alur moderasi sebelum dipublikasikan.</p></div></section>
<section data-theme-special="jackpot-index" class="bg-slate-950"><div class="mx-auto max-w-7xl px-4 py-12"><p class="mb-7 text-sm text-slate-400">Menampilkan <span class="font-semibold text-white">{{ $proofs->total() }}</span> bukti</p>
@forelse($proofs as $proof) @if($loop->first)<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">@endif
<article data-theme-surface class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900"><a href="{{ route('jackpot-proofs.show', $proof->slug) }}" class="block aspect-[4/3] overflow-hidden bg-slate-950"><img src="{{ asset('storage/'.($proof->thumbnail_path ?: $proof->image_path)) }}" alt="{{ $proof->title }}" class="h-full w-full object-cover transition duration-300 hover:scale-105" loading="lazy"></a><div class="p-6"><h2 class="text-xl font-bold text-white"><a class="hover:text-amber-400" href="{{ route('jackpot-proofs.show', $proof->slug) }}">{{ $proof->title }}</a></h2>@if(filled($proof->description))<p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-300">{{ $proof->description }}</p>@endif<p class="mt-5 text-xs text-slate-500">Dipublikasikan {{ $proof->published_at->translatedFormat('d F Y H:i') }}</p></div></article>
@if($loop->last)</div>@endif @empty<div data-theme-surface class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center"><h2 class="text-xl font-semibold text-white">Belum ada bukti jackpot</h2><p class="mt-3 text-sm text-slate-400">Bukti yang telah disetujui akan muncul di halaman ini.</p></div>@endforelse
@if($proofs->hasPages())<nav class="mt-10">{{ $proofs->links() }}</nav>@endif</div></section>
@endsection
