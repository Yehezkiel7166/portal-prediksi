@extends('frontend.layouts.app')
@section('title', 'Konversi Angka SGP | '.config('app.name'))
@section('description', 'Konversi angka SGP 4D menjadi posisi AS, KOP, KEPALA, EKOR, 3D, dan 2D.')
@section('metadata')
<link rel="canonical" href="{{ route('tools.sgp-converter.create') }}">
<meta property="og:title" content="Konversi Angka SGP | {{ config('app.name') }}">
<meta property="og:description" content="Konversi angka SGP 4D menjadi posisi AS, KOP, KEPALA, EKOR, 3D, dan 2D.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('tools.sgp-converter.create') }}">
@endsection
@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Alat Togel</p>
        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Konversi Angka SGP</h1>
        <p class="mt-5 max-w-3xl text-slate-300">Pisahkan angka 4D menjadi AS, KOP, KEPALA, EKOR serta turunan 3D dan 2D secara langsung.</p>
    </div>
</section>
<section class="bg-slate-950">
    <div class="mx-auto max-w-5xl px-4 py-12">
        <form method="POST" action="{{ route('tools.sgp-converter.store') }}" class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            @csrf
            <label for="number" class="block text-sm font-semibold text-white">Angka 4D</label>
            <input id="number" name="number" value="{{ old('number') }}" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="Contoh: 0123" class="mt-2 w-full max-w-md rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none focus:border-amber-400">
            @error('number')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            <button type="submit" class="mt-6 rounded-lg bg-amber-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">Konversi Angka</button>
        </form>

        @if($result)
            <section class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-6">
                <h2 class="text-xl font-semibold text-white">Hasil konversi {{ $result['input'] }}</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach(['as' => 'AS', 'kop' => 'KOP', 'kepala' => 'KEPALA', 'ekor' => 'EKOR'] as $key => $label)
                        <div class="rounded-lg border border-slate-800 bg-slate-950 p-5 text-center">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $label }}</p>
                            <p class="mt-2 text-3xl font-bold text-amber-400">{{ $result[$key] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg border border-slate-800 bg-slate-950 p-5"><p class="text-xs uppercase text-slate-400">4D</p><p class="mt-2 text-2xl font-semibold text-white">{{ $result['four_digit'] }}</p></div>
                    <div class="rounded-lg border border-slate-800 bg-slate-950 p-5"><p class="text-xs uppercase text-slate-400">3D (KOP–EKOR)</p><p class="mt-2 text-2xl font-semibold text-white">{{ $result['three_digit'] }}</p></div>
                    <div class="rounded-lg border border-slate-800 bg-slate-950 p-5"><p class="text-xs uppercase text-slate-400">2D (KEPALA–EKOR)</p><p class="mt-2 text-2xl font-semibold text-white">{{ $result['two_digit'] }}</p></div>
                </div>
            </section>
        @endif

        <section class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-6 text-sm text-slate-300">
            <h2 class="text-lg font-semibold text-white">Aturan konversi</h2>
            <p class="mt-3">Untuk angka ABCD: AS = A, KOP = B, KEPALA = C, EKOR = D, 3D = BCD, dan 2D = CD. Angka nol di depan tetap dipertahankan dan input tidak disimpan ke database.</p>
        </section>
    </div>
</section>
@endsection
