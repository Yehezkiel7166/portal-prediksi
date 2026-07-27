<header class="border-b border-amber-400/20 bg-slate-950">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 lg:flex-row lg:items-center lg:justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide text-amber-400">
            {{ config('app.name') }}
        </a>

        <nav aria-label="Navigasi utama">
            <ul class="flex flex-wrap items-center gap-x-5 gap-y-3 text-sm font-medium">
                <li>
                    <a
                        href="{{ route('home') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('home'),
                            'text-slate-300' => ! request()->routeIs('home'),
                        ])
                    >
                        Home
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('live-draw.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('live-draw.*'),
                            'text-slate-300' => ! request()->routeIs('live-draw.*'),
                        ])
                    >
                        Live Draw
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('predictions.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('predictions.*'),
                            'text-slate-300' => ! request()->routeIs('predictions.*'),
                        ])
                    >
                        Prediksi Togel
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('slot-gacor.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('slot-gacor.*'),
                            'text-slate-300' => ! request()->routeIs('slot-gacor.*'),
                        ])
                    >
                        Slot Gacor
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('jackpot-proofs.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('jackpot-proofs.*'),
                            'text-slate-300' => ! request()->routeIs('jackpot-proofs.*'),
                        ])
                    >
                        Bukti Jackpot
                    </a>
                </li>

                <li>
                    <details class="relative">
                        <summary class="cursor-pointer text-slate-300 transition hover:text-amber-300">
                            Alat Togel
                        </summary>

                        <div class="mt-3 w-56 rounded-lg border border-slate-700 bg-slate-900 p-3">
                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.lottery-schedule') }}">Jadwal Togel</a>
                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.shio-table') }}">Tabel Shio</a>
                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.bbfs.create') }}">BBFS Generator</a>
                            <p class="py-2 text-slate-500">Buku Mimpi</p>
                            <p class="py-2 text-slate-500">Paito Togel Warna</p>
                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.sgp-converter.create') }}">Konversi Angka SGP</a>
                        </div>
                    </details>
                </li>

                <li>
                    <a
                        href="{{ route('guides.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('guides.*'),
                            'text-slate-300' => ! request()->routeIs('guides.*'),
                        ])
                    >
                        Panduan
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('complaints.create') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('complaints.*'),
                            'text-slate-300' => ! request()->routeIs('complaints.*'),
                        ])
                    >
                        Keluhan
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('results.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('results.*'),
                            'text-slate-300' => ! request()->routeIs('results.*'),
                        ])
                    >
                        Data Result
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
