<header class="border-b border-amber-400/20 bg-slate-950">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 lg:flex-row lg:items-center lg:justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide text-amber-400">
            {{ config('app.name') }}
        </a>

        <nav aria-label="Navigasi utama">
            <ul class="flex flex-wrap items-center gap-x-5 gap-y-3 text-sm font-medium">
                <li>
                    <a href="{{ route('home') }}" class="text-amber-400">Home</a>
                </li>
                <li>
                    <span class="text-slate-300">Live Draw</span>
                </li>
                <li>
                    <span class="text-slate-300">Prediksi Togel</span>
                </li>
                <li>
                    <span class="text-slate-300">Slot Gacor</span>
                </li>
                <li>
                    <details class="relative">
                        <summary class="cursor-pointer text-slate-300">Alat Togel</summary>
                        <div class="mt-3 w-48 rounded-lg border border-slate-700 bg-slate-900 p-3">
                            <p class="py-1 text-slate-300">Buku Mimpi</p>
                            <p class="py-1 text-slate-300">Kalkulator Shio</p>
                            <p class="py-1 text-slate-300">Generator Angka</p>
                        </div>
                    </details>
                </li>
                <li>
                    <span class="text-slate-300">Data Result</span>
                </li>
            </ul>
        </nav>
    </div>
</header>
