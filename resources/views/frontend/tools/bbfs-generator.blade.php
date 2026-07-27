@extends('frontend.layouts.app')
@section('title', 'BBFS Generator | '.config('app.name'))
@section('description', 'Generator BBFS deterministik untuk membentuk kombinasi angka 2D, 3D, atau 4D.')
@section('metadata')
<link rel="canonical" href="{{ route('tools.bbfs.create') }}">
<meta property="og:title" content="BBFS Generator | {{ config('app.name') }}">
<meta property="og:description" content="Generator BBFS deterministik untuk kombinasi angka 2D, 3D, atau 4D.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('tools.bbfs.create') }}">
@endsection
@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Alat Togel</p>
        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">BBFS Generator</h1>
        <p class="mt-5 max-w-3xl text-slate-300">Masukkan 2 sampai 7 digit unik. Sistem menghapus duplikasi sambil mempertahankan urutan pertama, lalu membuat seluruh permutasi tanpa pengulangan digit.</p>
    </div>
</section>
<section class="bg-slate-950">
    <div class="mx-auto max-w-5xl px-4 py-12">
        <form method="POST" action="{{ route('tools.bbfs.store') }}" class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-[1fr_180px]">
                <div>
                    <label for="digits" class="block text-sm font-semibold text-white">Digit BBFS</label>
                    <input id="digits" name="digits" value="{{ old('digits') }}" maxlength="64" inputmode="numeric" placeholder="Contoh: 1234567" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-amber-400">
                    @error('digits')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="length" class="block text-sm font-semibold text-white">Keluaran</label>
                    <select id="length" name="length" class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-amber-400">
                        @foreach([2, 3, 4] as $length)
                            <option value="{{ $length }}" @selected((int) old('length', 2) === $length)>{{ $length }}D</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-6 rounded-lg bg-amber-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">Generate BBFS</button>
        </form>

        @if($result)
            <section class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-white">Hasil {{ $result['length'] }}D</h2>
                        <p class="mt-1 text-sm text-slate-400">Digit unik: {{ $result['digits'] }} · Total {{ $result['count'] }} kombinasi</p>
                    </div>
                </div>
                <div class="mt-5 max-h-96 overflow-y-auto rounded-lg border border-slate-800 bg-slate-950 p-4 font-mono text-sm leading-7 text-slate-200">
                    {{ implode(' · ', $result['combinations']) }}
                </div>
            </section>
        @endif

        <section class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-6 text-sm text-slate-300">
            <h2 class="text-lg font-semibold text-white">Aturan generator</h2>
            <p class="mt-3">Keluaran bersifat deterministik: input, urutan digit, dan panjang yang sama selalu menghasilkan urutan keluaran yang sama. Digit tidak diulang dalam satu kombinasi dan input pengunjung tidak disimpan ke database.</p>
        </section>
    </div>
</section>
@endsection
