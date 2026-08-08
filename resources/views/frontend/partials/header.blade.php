<header
    data-theme-header
    class="border-b"
>
    <div
        class="
            mx-auto flex max-w-7xl flex-col
            gap-4 px-4 py-5
            lg:flex-row lg:items-center
        "
    >
        <a
            href="{{ route('home') }}"
            class="flex shrink-0 items-center"
            @if (request()->routeIs('home'))
                aria-current="page"
            @endif
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
                    <span>
                        {{ $siteConfiguration->tagline }}
                    </span>
                @endif
            </span>
        </a>

        <nav
            aria-label="Navigasi utama"
            class="min-w-0 flex-1 lg:ml-8"
        >
            <ul
                class="
                    flex flex-wrap items-center
                    gap-x-5 gap-y-3
                    text-sm font-medium
                "
            >
                <li>
                    <a
                        href="{{ route('home') }}"
                        class="transition"
                        @if (request()->routeIs('home'))
                            aria-current="page"
                        @endif
                    >
                        Home
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('live-draw.index') }}"
                        class="transition"
                        @if (request()->routeIs('live-draw.*'))
                            aria-current="page"
                        @endif
                    >
                        LiveDraw
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('predictions.index') }}"
                        class="transition"
                        @if (request()->routeIs('predictions.*'))
                            aria-current="page"
                        @endif
                    >
                        Prediksi
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('slot-gacor.index') }}"
                        class="transition"
                        @if (request()->routeIs('slot-gacor.*'))
                            aria-current="page"
                        @endif
                    >
                        Slot Gacor
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('results.index') }}"
                        class="transition"
                        @if (request()->routeIs('results.*'))
                            aria-current="page"
                        @endif
                    >
                        Result
                    </a>
                </li>

                <li>
                    <details class="relative">
                        <summary
                            class="cursor-pointer transition"
                            @if (request()->routeIs('tools.*'))
                                aria-current="page"
                            @endif
                        >
                            Alat Togel
                        </summary>

                        <div
                            data-theme-header-menu
                            class="
                                absolute left-0 z-50 mt-3
                                w-56 rounded-lg border
                                p-3 shadow-xl
                            "
                        >
                            <a
                                class="block py-2 transition"
                                href="{{ route('tools.lottery-schedule') }}"
                            >
                                Jadwal Togel
                            </a>

                            <a
                                class="block py-2 transition"
                                href="{{ route('tools.shio-table') }}"
                            >
                                Tabel Shio
                            </a>

                            <a
                                class="block py-2 transition"
                                href="{{ route('tools.bbfs.create') }}"
                            >
                                BBFS Generator
                            </a>

                            <a
                                class="block py-2 transition"
                                href="{{ route('tools.dream-book.index') }}"
                            >
                                Buku Mimpi
                            </a>

                            <a
                                class="block py-2 transition"
                                href="{{ route('tools.paito') }}"
                            >
                                Paito Togel Warna
                            </a>

                            <a
                                class="block py-2 transition"
                                href="{{ route('tools.sgp-converter.create') }}"
                            >
                                Konversi Angka SGP
                            </a>
                        </div>
                    </details>
                </li>

                <li>
                    <a
                        href="{{ route('jackpot-proofs.index') }}"
                        class="transition"
                        @if (request()->routeIs('jackpot-proofs.*'))
                            aria-current="page"
                        @endif
                    >
                        Bukti Jackpot
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('guides.index') }}"
                        class="transition"
                        @if (request()->routeIs('guides.*'))
                            aria-current="page"
                        @endif
                    >
                        Panduan
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('complaints.create') }}"
                        class="transition"
                        @if (request()->routeIs('complaints.*'))
                            aria-current="page"
                        @endif
                    >
                        Keluhan
                    </a>
                </li>
            </ul>
        </nav>

        <time
            data-live-clock
            data-theme-clock
            role="timer"
            aria-live="off"
            aria-label="Waktu Indonesia Barat"
            class="
                shrink-0 self-start
                whitespace-nowrap rounded-lg
                border px-3 py-2
                text-xs font-semibold
                tabular-nums
                lg:ml-auto lg:self-center
            "
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
