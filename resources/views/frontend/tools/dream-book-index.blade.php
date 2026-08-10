@extends('frontend.layouts.app')

@section(
    'title',
    'Buku Mimpi 2D 3D 4D | '.config('app.name')
)

@section(
    'description',
    'Cari tabel Buku Mimpi berdasarkan nomor, keterangan, kategori, atau angka.'
)

@section('metadata')
<link
    rel="canonical"
    href="{{ route('tools.dream-book.index') }}"
>
<meta
    property="og:title"
    content="Buku Mimpi 2D 3D 4D | {{ config('app.name') }}"
>
<meta
    property="og:description"
    content="Daftar Buku Mimpi berdasarkan kategori 2D, 3D, dan 4D."
>
<meta property="og:type" content="website">
<meta
    property="og:url"
    content="{{ route('tools.dream-book.index') }}"
>
@endsection

@section('content')
<section data-theme-tool="dream-book-index" class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-6xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Alat Togel
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Buku Mimpi dan Tafsir Angka
        </h1>

        <p class="mt-5 max-w-3xl text-slate-300">
            Cari keterangan dan angka berdasarkan kategori
            2D, 3D, atau 4D.
        </p>
    </div>
</section>

<section data-theme-tool="dream-book-index" class="bg-slate-950">
    <div class="mx-auto max-w-6xl px-4 py-10">
        <form
            data-theme-surface
            method="GET"
            action="{{ route('tools.dream-book.index') }}"
            class="rounded-xl border border-slate-800 bg-slate-900 p-5"
        >
            <div class="grid gap-4 md:grid-cols-[180px_minmax(0,1fr)_auto]">
                <div>
                    <label
                        for="category"
                        class="mb-2 block text-sm font-medium text-slate-200"
                    >
                        Kategori
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-amber-400"
                    >
                        @foreach ($categories as $option)
                            <option
                                value="{{ $option }}"
                                @selected($category === $option)
                            >
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label
                        for="q"
                        class="mb-2 block text-sm font-medium text-slate-200"
                    >
                        Pencarian
                    </label>

                    <input
                        id="q"
                        name="q"
                        value="{{ $query }}"
                        maxlength="80"
                        placeholder="Cari nomor, keterangan, atau angka"
                        class="w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-amber-400"
                    >
                </div>

                <button
                    type="submit"
                    class="self-end rounded-lg bg-amber-400 px-6 py-3 font-semibold text-slate-950 hover:bg-amber-300"
                >
                    Cari
                </button>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($categories as $option)
                    <a
                        href="{{ route('tools.dream-book.index', [
                            'category' => $option,
                            'q' => $query !== '' ? $query : null,
                        ]) }}"
                        class="rounded-lg border px-4 py-2 text-sm font-semibold transition
                            {{ $category === $option
                                ? 'border-amber-400 bg-amber-400 text-slate-950'
                                : 'border-slate-700 text-slate-300 hover:border-amber-400 hover:text-amber-300' }}"
                    >
                        {{ $option }}
                    </a>
                @endforeach
            </div>
        </form>

        @if ($entries->isEmpty())
            <div data-theme-surface class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-8 text-center text-slate-300">
                Tidak ada data kategori {{ $category }} yang cocok.
            </div>
        @else
            <div data-theme-surface class="mt-8 overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
                <div class="hidden grid-cols-[70px_minmax(0,1fr)_120px_220px] gap-4 border-b border-slate-700 bg-slate-800 px-5 py-4 text-sm font-bold text-white md:grid">
                    <div>No.</div>
                    <div>Keterangan</div>
                    <div>Kategori</div>
                    <div class="text-right">Angka</div>
                </div>

                <div class="divide-y divide-slate-800">
                    @foreach ($entries as $index => $entry)
                        <article class="grid gap-4 px-5 py-5 md:grid-cols-[70px_minmax(0,1fr)_120px_220px] md:items-center">
                            <div>
                                <span class="text-xs text-slate-500 md:hidden">
                                    No.
                                </span>

                                <div class="font-bold text-white">
                                    {{ $entries->firstItem() + $index }}.
                                </div>
                            </div>

                            <div>
                                <span class="text-xs text-slate-500 md:hidden">
                                    Keterangan
                                </span>

                                <a
                                    href="{{ route(
                                        'tools.dream-book.show',
                                        $entry['slug']
                                    ) }}"
                                    class="block font-semibold uppercase leading-6 text-amber-400 hover:text-amber-300"
                                >
                                    {{ $entry['description'] }}
                                </a>
                            </div>

                            <div>
                                <span class="text-xs text-slate-500 md:hidden">
                                    Kategori
                                </span>

                                <span class="inline-flex rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-sm font-semibold text-slate-200">
                                    {{ $entry['category'] }}
                                </span>
                            </div>

                            <div class="md:text-right">
                                <span class="text-xs text-slate-500 md:hidden">
                                    Angka
                                </span>

                                <div class="font-bold text-white">
                                    {{ $entry['numbers'] }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="mt-8">
                {{ $entries->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
