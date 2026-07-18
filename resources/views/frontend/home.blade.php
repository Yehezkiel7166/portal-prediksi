@extends('frontend.layouts.app')

@section('title', config('app.name').' | Portal Prediksi dan Data Result')

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-20">
        <div class="max-w-3xl">
            <p class="mb-4 text-sm font-semibold uppercase tracking-widest text-amber-400">
                Portal Informasi Terpadu
            </p>

            <h1 class="text-4xl font-bold leading-tight text-white md:text-6xl">
                Prediksi, hasil pasaran, dan kalender shio dalam satu portal.
            </h1>

            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                Akses informasi live draw, prediksi togel, data result, dan alat togel
                melalui tampilan yang cepat, terstruktur, dan mudah digunakan.
            </p>
        </div>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-16">
        <h2 class="text-2xl font-bold text-white">Layanan Utama</h2>

        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                <h3 class="font-semibold text-amber-400">Live Draw</h3>
                <p class="mt-3 text-sm leading-6 text-slate-400">
                    Informasi jadwal dan hasil live draw berbagai pasaran.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                <h3 class="font-semibold text-amber-400">Prediksi Togel</h3>
                <p class="mt-3 text-sm leading-6 text-slate-400">
                    Publikasi prediksi yang dikelola langsung melalui panel admin.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                <h3 class="font-semibold text-amber-400">Data Result</h3>
                <p class="mt-3 text-sm leading-6 text-slate-400">
                    Riwayat hasil pasaran yang tersusun berdasarkan tanggal.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900 p-6">
                <h3 class="font-semibold text-amber-400">Alat Togel</h3>
                <p class="mt-3 text-sm leading-6 text-slate-400">
                    Buku mimpi, kalender shio, dan alat pendukung lainnya.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection
