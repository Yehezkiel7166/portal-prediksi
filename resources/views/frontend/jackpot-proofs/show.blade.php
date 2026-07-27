@extends('frontend.layouts.app')
@section('title', ($proof->seo_title ?: $proof->title).' | '.config('app.name'))
@section('description', $proof->seo_description ?: ($proof->description ?: 'Bukti jackpot terbaru dari '.config('app.name').'.'))
@section('metadata')
<link rel="canonical" href="{{ route('jackpot-proofs.show', $proof->slug) }}">
<meta property="og:title" content="{{ $proof->seo_title ?: $proof->title }}"><meta property="og:description" content="{{ $proof->seo_description ?: $proof->description }}"><meta property="og:type" content="article"><meta property="og:url" content="{{ route('jackpot-proofs.show', $proof->slug) }}"><meta property="og:image" content="{{ asset('storage/'.$proof->image_path) }}">
@endsection
@section('content')
<article class="bg-slate-950"><div class="mx-auto max-w-4xl px-4 py-12"><a href="{{ route('jackpot-proofs.index') }}" class="text-sm font-semibold text-amber-400 hover:text-amber-300">← Kembali ke Bukti Jackpot</a><h1 class="mt-6 text-3xl font-bold text-white md:text-5xl">{{ $proof->title }}</h1><p class="mt-4 text-sm text-slate-500">Dipublikasikan {{ $proof->published_at->translatedFormat('d F Y H:i') }}</p><div class="mt-8 overflow-hidden rounded-xl border border-slate-800 bg-slate-900"><img src="{{ asset('storage/'.$proof->image_path) }}" alt="{{ $proof->title }}" class="h-auto w-full"></div>@if(filled($proof->description))<div class="mt-8 whitespace-pre-line text-base leading-8 text-slate-300">{{ $proof->description }}</div>@endif</div></article>
@endsection
