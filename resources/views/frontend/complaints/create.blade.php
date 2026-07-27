@extends('frontend.layouts.app')

@section('title', 'Form Keluhan | '.config('app.name'))
@section('description', 'Sampaikan keluhan atau masukan kepada tim '.config('app.name').'.')

@section('metadata')
    <meta name="robots" content="noindex,follow">
@endsection

@section('content')
<section class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-4xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">Layanan Pengguna</p>
        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">Form Keluhan</h1>
        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300">
            Jelaskan kendala secara lengkap. Jangan mengirim kata sandi, PIN, kode OTP, atau data pembayaran sensitif.
        </p>
    </div>
</section>

<section class="bg-slate-950">
    <div class="mx-auto max-w-4xl px-4 py-12">
        @if (session('complaint_submitted'))
            <div role="status" class="mb-8 rounded-xl border border-emerald-500/40 bg-emerald-950/30 p-6 text-emerald-100">
                <h2 class="text-lg font-bold">Keluhan berhasil dikirim</h2>
                <p class="mt-2">Simpan nomor referensi berikut:</p>
                <p class="mt-3 font-mono text-xl font-bold">{{ session('complaint_reference') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="mb-8 rounded-xl border border-red-500/40 bg-red-950/30 p-6 text-red-200">
                <p class="font-semibold">Form belum dapat dikirim.</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('complaints.store') }}" class="space-y-6 rounded-xl border border-slate-800 bg-slate-900 p-6 md:p-8">
            @csrf
            <div class="hidden" aria-hidden="true">
                <label for="website">Website</label>
                <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-200">Nama</label>
                    <input id="name" name="name" value="{{ old('name') }}" required maxlength="120" class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-amber-400">
                </div>
                <div>
                    <label for="contact" class="block text-sm font-medium text-slate-200">Email atau nomor kontak</label>
                    <input id="contact" name="contact" value="{{ old('contact') }}" required maxlength="190" class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-amber-400">
                </div>
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-slate-200">Subjek</label>
                <input id="subject" name="subject" value="{{ old('subject') }}" required maxlength="190" class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-amber-400">
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-slate-200">Isi keluhan</label>
                <textarea id="message" name="message" required minlength="20" maxlength="5000" rows="8" class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-amber-400">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-amber-400 px-6 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">Kirim Keluhan</button>
        </form>
    </div>
</section>
@endsection
