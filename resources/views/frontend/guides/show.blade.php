@extends('frontend.layouts.app')
@section('title', filled($guide->seo_title) ? $guide->seo_title : $guide->title.' | '.config('app.name'))
@section('description', filled($guide->seo_description) ? $guide->seo_description : (filled($guide->excerpt) ? $guide->excerpt : 'Panduan dari '.config('app.name').'.'))
@section('metadata')
<link rel="canonical" href="{{ route('guides.show', $guide->slug) }}"><meta property="og:title" content="{{ filled($guide->seo_title) ? $guide->seo_title : $guide->title }}"><meta property="og:description" content="{{ filled($guide->seo_description) ? $guide->seo_description : $guide->excerpt }}"><meta property="og:type" content="article"><meta property="og:url" content="{{ route('guides.show', $guide->slug) }}">
@endsection
@section('content')
<section data-theme-content="guide-detail" class="border-b border-slate-800 bg-slate-900"><div class="mx-auto max-w-5xl px-4 py-10 md:py-14"><a class="text-sm font-semibold text-amber-400" href="{{ route('guides.index') }}">← Kembali ke Panduan</a><p class="mt-7 text-sm font-semibold uppercase tracking-widest text-amber-400">{{ $guide->category ?: 'Panduan' }}</p><h1 class="mt-3 text-3xl font-bold md:text-5xl">{{ $guide->title }}</h1>@if(filled($guide->excerpt))<p class="mt-5 text-slate-300">{{ $guide->excerpt }}</p>@endif</div></section>
<section data-theme-content="guide-detail" class="bg-slate-950"><div class="mx-auto max-w-5xl px-4 py-12"><article data-theme-surface class="rounded-2xl border border-slate-800 bg-slate-900 p-6 md:p-8"><div data-theme-rich-content class="prose prose-invert max-w-none break-words leading-8">{!! $guide->content !!}</div></article></div></section>
@endsection
