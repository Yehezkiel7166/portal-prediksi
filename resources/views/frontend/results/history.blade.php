@extends('frontend.layouts.app')

@section(
    'title',
    'History Result '.$market->name.' | '.config('app.name')
)

@section(
    'description',
    'History result pasaran '.$market->name.' melalui '.config('app.name').'.'
)

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <a
            href="{{ route('results.index') }}"
            class="text-sm font-semibold text-amber-400 hover:text-amber-300"
        >
            ← Semua Pasaran
        </a>

        <div class="mt-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
                    History Result
                </p>

                <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
                    {{ $market->name }}
                </h1>

                <p class="mt-3 text-slate-400">
                    Kode pasaran: {{ $market->code }}
                </p>
            </div>

            @php
                $statusClass = match ($status['key']) {
                    'open' =>
                        'border-emerald-500/40 bg-emerald-950/40 text-emerald-300',
                    'closed' =>
                        'border-red-500/40 bg-red-950/40 text-red-300',
                    'holiday' =>
                        'border-amber-500/40 bg-amber-950/40 text-amber-300',
                    default =>
                        'border-slate-700 bg-slate-950 text-slate-400',
                };
            @endphp

            <span class="inline-flex rounded-full border px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                {{ $status['label'] }}
            </span>
        </div>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <p class="mb-7 text-sm text-slate-400">
            Total
            <span class="font-semibold text-white">
                {{ $results->total() }}
            </span>
            history result
        </p>

        @forelse ($results as $result)
            @if ($loop->first)
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @endif

            <article class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                <time
                    datetime="{{ $result->result_date->format('Y-m-d') }}"
                    class="text-sm font-semibold text-amber-400"
                >
                    {{ $result->result_date->translatedFormat('d F Y') }}
                </time>

                <div class="mt-4 whitespace-pre-line break-words rounded-lg border border-amber-400/20 bg-slate-950 p-4 text-center text-xl font-bold leading-7 text-white">
                    {{ $result->winning_numbers }}
                </div>

                @if (filled($result->notes))
                    <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-400">
                        {{ $result->notes }}
                    </p>
                @endif

                <a
                    href="{{ route('results.show', [
                        'marketSlug' => $market->slug,
                        'resultDate' => $result->result_date->format('Y-m-d'),
                    ]) }}"
                    class="mt-5 inline-flex text-sm font-semibold text-amber-400 hover:text-amber-300"
                >
                    Buka result →
                </a>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Belum ada history result
                </h2>
            </div>
        @endforelse

        @if ($results->hasPages())
            <nav
                class="mt-10"
                aria-label="Navigasi history result"
            >
                {{ $results->links() }}
            </nav>
        @endif
    </div>
</section>
@endsection
