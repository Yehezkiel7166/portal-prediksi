<header class="border-b border-amber-400/20 bg-slate-950">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 lg:flex-row lg:items-center">
        <a
            href="{{ route('home') }}"
            class="flex shrink-0 items-center text-amber-400"
        >
            @if ($siteConfiguration->logoUrl)
                <img
                    src="{{ $siteConfiguration->logoUrl }}"
                    alt="{{ $siteConfiguration->siteName }}"
                    class="h-10 w-auto"
                >
            @endif

            <span class="sr-only">
                {{ $siteConfiguration->siteName }}

                @if ($siteConfiguration->tagline)
                    <span>{{ $siteConfiguration->tagline }}</span>
                @endif
            </span>
        </a>

        <nav
            aria-label="Navigasi utama"
            class="min-w-0 flex-1 lg:ml-8"
        >
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
                        LiveDraw
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
                        Prediksi
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
                        href="{{ route('results.index') }}"
                        @class([
                            'transition hover:text-amber-300',
                            'text-amber-400' => request()->routeIs('results.*'),
                            'text-slate-300' => ! request()->routeIs('results.*'),
                        ])
                    >
                        Result
                    </a>
                </li>

                <li>
                    <details class="relative">
                        <summary
                            @class([
                                'cursor-pointer transition hover:text-amber-300',
                                'text-amber-400' => request()->routeIs('tools.*'),
                                'text-slate-300' => ! request()->routeIs('tools.*'),
                            ])
                        >
                            Alat Togel
                        </summary>

                        <div class="absolute left-0 z-50 mt-3 w-56 rounded-lg border border-slate-700 bg-slate-900 p-3 shadow-xl">
                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.lottery-schedule') }}">
                                Jadwal Togel
                            </a>

                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.shio-table') }}">
                                Tabel Shio
                            </a>

                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.bbfs.create') }}">
                                BBFS Generator
                            </a>

                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.dream-book.index') }}">
                                Buku Mimpi
                            </a>

                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.paito') }}">
                                Paito Togel Warna
                            </a>

                            <a class="block py-2 text-slate-300 transition hover:text-amber-300" href="{{ route('tools.sgp-converter.create') }}">
                                Konversi Angka SGP
                            </a>
                        </div>
                    </details>
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
            </ul>
        </nav>

        <time
            data-live-clock
            role="timer"
            aria-live="off"
            aria-label="Waktu Indonesia Barat"
            class="shrink-0 self-start whitespace-nowrap rounded-lg border border-amber-400/20 bg-slate-900 px-3 py-2 text-xs font-semibold tabular-nums text-amber-300 lg:ml-auto lg:self-center"
        >
            Memuat waktu...
        </time>
    </div>
</header>

<script>
    (() => {
        const clock = document.querySelector('[data-live-clock]');

        if (!clock) {
            return;
        }

        const dateFormatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });

        const timeFormatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hourCycle: 'h23',
        });

        const capitalize = (value) => {
            if (!value) {
                return value;
            }

            return value.charAt(0).toUpperCase() + value.slice(1);
        };

        const renderClock = () => {
            const now = new Date();

            const dateText = capitalize(
                dateFormatter
                    .format(now)
                    .replace(/\./g, '')
            );

            const timeParts = timeFormatter.formatToParts(now);

            const timeValues = Object.fromEntries(
                timeParts
                    .filter(({ type }) => (
                        type === 'hour'
                        || type === 'minute'
                        || type === 'second'
                    ))
                    .map(({ type, value }) => [type, value])
            );

            const timeText = [
                timeValues.hour,
                timeValues.minute,
                timeValues.second,
            ].join(':');

            clock.textContent = `${dateText} ( ${timeText} )`;
            clock.setAttribute(
                'datetime',
                now.toISOString()
            );
        };

        renderClock();
        window.setInterval(renderClock, 1000);
    })();
</script>
