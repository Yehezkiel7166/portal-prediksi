@extends('frontend.layouts.app')
@section('title', 'Buku Mimpi dan Tafsir Angka | '.config('app.name'))
@section('description', 'Cari referensi Buku Mimpi berdasarkan angka, simbol, atau kata kunci.')
@section('metadata')
<link rel="canonical" href="{{ route('tools.dream-book.index') }}">
<meta property="og:title" content="Buku Mimpi dan Tafsir Angka | {{ config('app.name') }}">
<meta property="og:description" content="Referensi simbol dan tafsir angka yang dapat dicari.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('tools.dream-book.index') }}">
@endsection
@section('content')
<section class="border-b border-slate-800 bg-slate-900"><div class="mx-auto max-w-6xl px-4 py-12 md:py-16"><p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Alat Togel</p><h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Buku Mimpi</h1><p class="mt-5 max-w-3xl text-slate-300">Telusuri referensi simbol berdasarkan angka, nama, atau kata kunci. Konten ini bersifat referensi dan tidak menjamin hasil apa pun.</p></div></section>
<section class="bg-slate-950"><div class="mx-auto max-w-6xl px-4 py-12">
<form method="GET" action="{{ route('tools.dream-book.index') }}" class="flex flex-col gap-3 rounded-xl border border-slate-800 bg-slate-900 p-5 sm:flex-row">
<label for="q" class="sr-only">Cari Buku Mimpi</label><input id="q" name="q" value="{{ $query }}" maxlength="80" placeholder="Cari angka, simbol, atau kata kunci" class="min-w-0 flex-1 rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-amber-400"><button class="rounded-lg bg-amber-400 px-5 py-3 font-semibold text-slate-950 hover:bg-amber-300">Cari</button>
</form>
@if($entries->isEmpty())<div class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-8 text-center text-slate-300">Tidak ada referensi yang cocok.</div>@else
<div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
@foreach($entries as $entry)<article class="rounded-xl border border-slate-800 bg-slate-900 p-5"><div class="flex items-center gap-4"><span class="flex h-14 w-14 items-center justify-center rounded-lg bg-amber-400 text-xl font-bold text-slate-950">{{ $entry['number'] }}</span><div><h2 class="text-xl font-semibold text-white"><a class="hover:text-amber-300" href="{{ route('tools.dream-book.show', $entry['slug']) }}">{{ $entry['title'] }}</a></h2><p class="mt-1 text-sm text-slate-400">{{ implode(' · ', $entry['keywords']) }}</p></div></div><p class="mt-4 text-sm leading-6 text-slate-300">{{ $entry['interpretation'] }}</p></article>@endforeach
</div><div class="mt-8">{{ $entries->links() }}</div>@endif
</div></section>
@endsection
