@extends('frontend.layouts.app')

@section('title', 'Prediksi Togel Terbaru | '.config('app.name'))

@section(
    'description',
    'Daftar prediksi togel terbaru dari berbagai pasaran aktif yang diterbitkan melalui '.config('app.name').'.'
)

@section('content')
<section data-theme-module="predictions" class="border-b border-slate-800 bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-amber-400">
            Prediksi Togel
        </p>

        <h1 class="mt-3 text-3xl font-bold text-white md:text-5xl">
            Prediksi Togel Terbaru
        </h1>

        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 md:text-lg">
            Temukan prediksi terbaru dari berbagai pasaran aktif. Gunakan filter
            pasaran dan tanggal untuk mempersempit hasil yang ditampilkan.
        </p>
    </div>
</section>

<section data-theme-module="predictions" class="border-b border-slate-800 bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-8">
        <form
            data-theme-surface
            method="GET"
            action="{{ route('predictions.index') }}"
            class="grid gap-5 rounded-xl border border-slate-800 bg-slate-900 p-6 md:grid-cols-2 lg:grid-cols-[1fr_1fr_auto]"
        >
            <div>
                <label
                    for="market"
                    class="block text-sm font-medium text-slate-200"
                >
                    Pasaran
                </label>

                <select
                    id="market"
                    name="market"
                    class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white outline-none transition focus:border-amber-400"
                >
                    <option value="">Semua pasaran</option>

                    @foreach ($markets as $market)
                        <option
                            value="{{ $market->slug }}"
                            @selected($filters['market'] === $market->slug)
                        >
                            {{ $market->name }} ({{ $market->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label
                    for="date"
                    class="block text-sm font-medium text-slate-200"
                >
                    Tanggal prediksi
                </label>

                <div
                    data-dark-datepicker data-theme-datepicker
                    class="relative mt-2"
                >
                    <input
                        id="date"
                        name="date"
                        type="hidden"
                        value="{{ $filters['date'] }}"
                        data-datepicker-value
                    >

                    <button
                        type="button"
                        data-datepicker-trigger
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        class="flex w-full items-center justify-between rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-left text-sm text-white outline-none transition hover:border-slate-600 focus:border-amber-400"
                    >
                        <span
                            data-datepicker-display
                            class="{{ $filters['date'] ? 'text-white' : 'text-slate-500' }}"
                        >
                            {{ $filters['date'] ?: 'Pilih tanggal' }}
                        </span>

                        <svg
                            aria-hidden="true"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="h-5 w-5 shrink-0 text-amber-400"
                            style="width:1.25rem;height:1.25rem;"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5Z"
                            />
                        </svg>
                    </button>

                    <div
                        data-datepicker-panel
                        role="dialog"
                        aria-label="Pilih tanggal prediksi"
                        hidden
                        class="absolute left-0 top-full z-50 mt-2 w-full overflow-hidden rounded-xl border border-slate-700 bg-slate-900 p-4 shadow-2xl"
                        style="min-width:320px;box-shadow:0 24px 60px rgba(0,0,0,.55);"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <button
                                type="button"
                                data-datepicker-prev
                                aria-label="Bulan sebelumnya"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-200 transition hover:border-amber-400 hover:text-amber-400"
                                style="width:2.25rem;height:2.25rem;"
                            >
                                &#8249;
                            </button>

                            <p
                                data-datepicker-title
                                class="text-base font-semibold text-white"
                            ></p>

                            <button
                                type="button"
                                data-datepicker-next
                                aria-label="Bulan berikutnya"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-200 transition hover:border-amber-400 hover:text-amber-400"
                                style="width:2.25rem;height:2.25rem;"
                            >
                                &#8250;
                            </button>
                        </div>

                        <div
                            class="mt-4 grid grid-cols-7 gap-1 text-center text-xs font-semibold uppercase tracking-wide text-slate-400"
                            style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:.25rem;"
                        >
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div
                            data-datepicker-days
                            class="mt-2 grid grid-cols-7 gap-1"
                            style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:.25rem;"
                        ></div>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-800 pt-3">
                            <button
                                type="button"
                                data-datepicker-clear
                                class="text-sm font-semibold text-amber-400 transition hover:text-amber-300"
                            >
                                Bersihkan
                            </button>

                            <button
                                type="button"
                                data-datepicker-today
                                class="text-sm font-semibold text-amber-400 transition hover:text-amber-300"
                            >
                                Hari Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-end gap-3">
                <button
                    type="submit"
                    class="inline-flex min-h-11 items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300"
                >
                    Terapkan Filter
                </button>

                @if ($filters['market'] !== null || $filters['date'] !== null)
                    <a
                        href="{{ route('predictions.index') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:border-slate-500 hover:text-white"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @if ($errors->any())
            <div
                role="alert"
                class="mt-5 rounded-lg border border-red-500/40 bg-red-950/30 px-5 py-4 text-sm text-red-200"
            >
                <p class="font-semibold">Filter tidak dapat diproses.</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</section>

<section data-theme-module="predictions" class="bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="mb-7 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-400">
                Menampilkan
                <span class="font-semibold text-white">
                    {{ $predictions->total() }}
                </span>
                prediksi
            </p>

            @if ($filters['market'] !== null || $filters['date'] !== null)
                <p class="text-sm text-amber-400">
                    Filter aktif
                </p>
            @endif
        </div>

        @forelse ($predictions as $prediction)
            @if ($loop->first)
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @endif

            <article data-theme-surface class="flex h-full flex-col rounded-xl border border-slate-800 bg-slate-900 p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pasaran
                        </p>

                        <h2 class="mt-1 text-xl font-bold text-amber-400">
                            <a
                                href="{{ route('predictions.show', [
                                    'marketSlug' => $prediction->market->slug,
                                    'predictionDate' => $prediction->prediction_date->format('Y-m-d'),
                                ]) }}"
                                class="transition hover:text-amber-300"
                            >
                                {{ $prediction->market->name }}
                            </a>
                        </h2>
                    </div>

                    <span class="rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs font-medium text-slate-300">
                        {{ $prediction->market->code }}
                    </span>
                </div>

                <div class="mt-5 border-t border-slate-800 pt-5">
                    <p class="text-sm text-slate-400">
                        Tanggal prediksi
                    </p>

                    <time
                        datetime="{{ $prediction->prediction_date->format('Y-m-d') }}"
                        class="mt-1 block font-semibold text-slate-100"
                    >
                        {{ $prediction->prediction_date->translatedFormat('d F Y') }}
                    </time>
                </div>

                <div class="mt-5">
                    <p class="text-sm text-slate-400">
                        Angka prediksi
                    </p>

                    <div class="mt-2">
                        @php
                            $predictionRows = [
                                'BBFS' => $prediction->bbfs,
                                'Colok Bebas' => $prediction->colok_bebas,
                                '2D' => $prediction->prediction_2d,
                                '3D' => $prediction->prediction_3d,
                                '4D' => $prediction->prediction_4d,
                                'Kembar' => $prediction->kembar,
                                'Shio' => $prediction->shio,
                            ];

                            $hasStructuredPrediction = collect($predictionRows)
                                ->contains(fn ($value) => filled($value));
                        @endphp

                        @if ($hasStructuredPrediction)
                            <div class="overflow-hidden rounded-lg border border-amber-400/20 bg-slate-950 px-4 py-2">
                                @foreach ($predictionRows as $label => $value)
                                    @if (filled($value))
                                        <div
                                            class="border-b border-slate-800 py-2 last:border-b-0"
                                            style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;"
                                        >
                                            <span
                                                class="text-xs font-semibold uppercase tracking-wide text-slate-400"
                                                style="flex:0 0 88px;"
                                            >{{ $label }}</span>

                                            <span
                                                class="break-words text-right text-sm font-bold leading-5 text-amber-400"
                                                style="min-width:0;flex:1 1 auto;"
                                            >{{ $value }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="whitespace-pre-line break-words rounded-lg border border-amber-400/20 bg-slate-950 p-4 font-semibold leading-7 text-white">
                                {{ $prediction->predicted_numbers }}
                            </div>
                        @endif
                    </div>
                </div>

                @if (filled($prediction->notes))
                    <div class="mt-5">
                        <p class="text-sm text-slate-400">
                            Catatan
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-300">
                            {{ $prediction->notes }}
                        </p>
                    </div>
                @endif

                <a
                    href="{{ route('predictions.show', [
                        'marketSlug' => $prediction->market->slug,
                        'predictionDate' => $prediction->prediction_date->format('Y-m-d'),
                    ]) }}"
                    class="mt-6 inline-flex items-center justify-center rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 transition hover:bg-amber-400 hover:text-slate-950"
                >
                    Lihat Detail
                </a>

                <p class="mt-auto pt-6 text-xs text-slate-500">
                    Diterbitkan
                    <time datetime="{{ $prediction->published_at->toIso8601String() }}">
                        {{ $prediction->published_at->translatedFormat('d F Y H:i') }}
                    </time>
                </p>
            </article>

            @if ($loop->last)
                </div>
            @endif
        @empty
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-white">
                    Tidak ada prediksi yang ditemukan
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    Belum ada prediksi publik yang sesuai dengan pilihan pasaran
                    dan tanggal saat ini.
                </p>

                @if ($filters['market'] !== null || $filters['date'] !== null)
                    <a
                        href="{{ route('predictions.index') }}"
                        class="mt-6 inline-flex items-center justify-center rounded-lg border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-400 transition hover:bg-amber-400 hover:text-slate-950"
                    >
                        Tampilkan Semua Prediksi
                    </a>
                @endif
            </div>
        @endforelse

        @if ($predictions->hasPages())
            <nav class="mt-10" aria-label="Navigasi halaman prediksi">
                {{ $predictions->links() }}
            </nav>
        @endif
    </div>
</section>
<script>
    (() => {
        const roots = document.querySelectorAll('[data-dark-datepicker data-theme-datepicker]');

        if (roots.length === 0) {
            return;
        }

        const monthNames = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        const pad = (value) => String(value).padStart(2, '0');

        const toIsoDate = (date) => [
            date.getFullYear(),
            pad(date.getMonth() + 1),
            pad(date.getDate()),
        ].join('-');

        const parseIsoDate = (value) => {
            if (!/^\d{4}-\d{2}-\d{2}$/.test(value || '')) {
                return null;
            }

            const [year, month, day] = value
                .split('-')
                .map(Number);

            const parsed = new Date(year, month - 1, day);

            if (
                parsed.getFullYear() !== year
                || parsed.getMonth() !== month - 1
                || parsed.getDate() !== day
            ) {
                return null;
            }

            return parsed;
        };

        const formatDisplayDate = (date) => {
            if (!date) {
                return 'Pilih tanggal';
            }

            return [
                pad(date.getDate()),
                monthNames[date.getMonth()],
                date.getFullYear(),
            ].join(' ');
        };

        roots.forEach((root) => {
            const input = root.querySelector('[data-datepicker-value]');
            const trigger = root.querySelector('[data-datepicker-trigger]');
            const display = root.querySelector('[data-datepicker-display]');
            const panel = root.querySelector('[data-datepicker-panel]');
            const title = root.querySelector('[data-datepicker-title]');
            const days = root.querySelector('[data-datepicker-days]');
            const previous = root.querySelector('[data-datepicker-prev]');
            const next = root.querySelector('[data-datepicker-next]');
            const clear = root.querySelector('[data-datepicker-clear]');
            const today = root.querySelector('[data-datepicker-today]');

            if (
                !input
                || !trigger
                || !display
                || !panel
                || !title
                || !days
                || !previous
                || !next
                || !clear
                || !today
            ) {
                return;
            }

            let selectedDate = parseIsoDate(input.value);
            let visibleMonth = selectedDate
                ? new Date(
                    selectedDate.getFullYear(),
                    selectedDate.getMonth(),
                    1,
                )
                : new Date();

            visibleMonth.setDate(1);

            const close = () => {
                panel.hidden = true;
                trigger.setAttribute('aria-expanded', 'false');
            };

            const open = () => {
                panel.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
            };

            const updateDisplay = () => {
                display.textContent = formatDisplayDate(selectedDate);

                if (selectedDate) {
                    display.classList.remove('text-slate-500');
                    display.classList.add('text-white');
                } else {
                    display.classList.remove('text-white');
                    display.classList.add('text-slate-500');
                }
            };

            const selectDate = (date) => {
                selectedDate = new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate(),
                );

                input.value = toIsoDate(selectedDate);

                visibleMonth = new Date(
                    selectedDate.getFullYear(),
                    selectedDate.getMonth(),
                    1,
                );

                updateDisplay();
                render();
                close();
            };

            const render = () => {
                const year = visibleMonth.getFullYear();
                const month = visibleMonth.getMonth();

                title.textContent = `${monthNames[month]} ${year}`;
                days.replaceChildren();

                const firstDay = new Date(year, month, 1);
                const gridStart = new Date(year, month, 1 - firstDay.getDay());

                for (let index = 0; index < 42; index += 1) {
                    const date = new Date(
                        gridStart.getFullYear(),
                        gridStart.getMonth(),
                        gridStart.getDate() + index,
                    );

                    const button = document.createElement('button');
                    const belongsToMonth = date.getMonth() === month;
                    const isSelected = selectedDate
                        && toIsoDate(selectedDate) === toIsoDate(date);
                    const isToday = toIsoDate(new Date()) === toIsoDate(date);

                    button.type = 'button';
                    button.textContent = String(date.getDate());
                    button.setAttribute(
                        'aria-label',
                        formatDisplayDate(date),
                    );

                    button.style.width = '2.25rem';
                    button.style.height = '2.25rem';
                    button.style.borderRadius = '.5rem';
                    button.style.display = 'inline-flex';
                    button.style.alignItems = 'center';
                    button.style.justifyContent = 'center';
                    button.style.margin = '0 auto';
                    button.style.fontSize = '.875rem';
                    button.style.transition = 'background-color .15s,color .15s,border-color .15s';

                    if (isSelected) {
                        button.style.background = '#fbbf24';
                        button.style.color = '#020617';
                        button.style.fontWeight = '700';
                    } else {
                        button.style.background = 'transparent';
                        button.style.color = belongsToMonth
                            ? '#f8fafc'
                            : '#64748b';

                        if (isToday) {
                            button.style.border = '1px solid #fbbf24';
                        }
                    }

                    button.addEventListener('mouseenter', () => {
                        if (!isSelected) {
                            button.style.background = '#1e293b';
                            button.style.color = '#fbbf24';
                        }
                    });

                    button.addEventListener('mouseleave', () => {
                        if (!isSelected) {
                            button.style.background = 'transparent';
                            button.style.color = belongsToMonth
                                ? '#f8fafc'
                                : '#64748b';
                        }
                    });

                    button.addEventListener('click', () => {
                        selectDate(date);
                    });

                    days.appendChild(button);
                }
            };

            trigger.addEventListener('click', () => {
                if (panel.hidden) {
                    open();
                    render();
                } else {
                    close();
                }
            });

            previous.addEventListener('click', () => {
                visibleMonth = new Date(
                    visibleMonth.getFullYear(),
                    visibleMonth.getMonth() - 1,
                    1,
                );

                render();
            });

            next.addEventListener('click', () => {
                visibleMonth = new Date(
                    visibleMonth.getFullYear(),
                    visibleMonth.getMonth() + 1,
                    1,
                );

                render();
            });

            clear.addEventListener('click', () => {
                selectedDate = null;
                input.value = '';
                updateDisplay();
                render();
                close();
            });

            today.addEventListener('click', () => {
                selectDate(new Date());
            });

            document.addEventListener('click', (event) => {
                if (!root.contains(event.target)) {
                    close();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    close();
                    trigger.focus();
                }
            });

            updateDisplay();
            render();
        });
    })();
</script>
@endsection
