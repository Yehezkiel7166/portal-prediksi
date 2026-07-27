@extends('frontend.layouts.app')
@section('title', $entry['title'].' '.$entry['number'].' — Buku Mimpi | '.config('app.name'))
@section('description', $entry['interpretation'])
@section('metadata')
<link rel="canonical" href="{{ route('tools.dream-book.show', $entry['slug']) }}">
<meta property="og:title" content="{{ $entry['title'] }} {{ $entry['number'] }} — Buku Mimpi | {{ config('app.name') }}">
<meta property="og:description" content="{{ $entry['interpretation'] }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('tools.dream-book.show', $entry['slug']) }}">
@endsection
@section('content')
<section class="bg-slate-950"><div class="mx-auto max-w-4xl px-4 py-12 md:py-16"><a href="{{ route('tools.dream-book.index') }}" class="text-sm font-semibold text-amber-400 hover:text-amber-300">← Kembali ke Buku Mimpi</a><article class="mt-6 rounded-xl border border-slate-800 bg-slate-900 p-6 md:p-8"><div class="flex items-center gap-5"><span class="flex h-20 w-20 items-center justify-center rounded-xl bg-amber-400 text-3xl font-bold text-slate-950">{{ $entry['number'] }}</span><div><p class="text-sm uppercase tracking-widest text-slate-400">Referensi simbol</p><h1 class="mt-2 text-3xl font-bold text-white">{{ $entry['title'] }}</h1></div></div><p class="mt-8 text-lg leading-8 text-slate-200">{{ $entry['interpretation'] }}</p><div class="mt-6 flex flex-wrap gap-2">@foreach($entry['keywords'] as $keyword)<span class="rounded-full border border-slate-700 px-3 py-1 text-sm text-slate-300">{{ $keyword }}</span>@endforeach</div></article>
@if($related->isNotEmpty())<section class="mt-10"><h2 class="text-xl font-semibold text-white">Referensi terkait</h2><div class="mt-4 grid gap-4 sm:grid-cols-2">@foreach($related as $item)<a href="{{ route('tools.dream-book.show', $item['slug']) }}" class="rounded-lg border border-slate-800 bg-slate-900 p-4 transition hover:border-amber-400"><span class="font-bold text-amber-400">{{ $item['number'] }}</span><span class="ml-3 text-white">{{ $item['title'] }}</span></a>@endforeach</div></section>@endif
</div></section>
@endsection
