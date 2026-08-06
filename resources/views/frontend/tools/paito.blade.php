@extends('frontend.layouts.app')

@section('title', 'Paito Togel Warna | '.config('app.name'))
@section('description', 'Paito AS KOP KEPALA EKOR dan JUMLAH dari data Result.')

@section('metadata')
<link rel="canonical" href="{{ route('tools.paito') }}">
<meta property="og:title" content="Paito Togel Warna">
<meta property="og:type" content="website">
@endsection

@section('content')
@php
$palette = [
    'red' => '#ef4444',
    'blue' => '#3b82f6',
    'green' => '#22c55e',
    'yellow' => '#facc15',
    'orange' => '#f97316',
    'purple' => '#a855f7',
    'pink' => '#ec4899',
    'cyan' => '#06b6d4',
    'gray' => '#64748b',
];
@endphp

<section class="border-b border-slate-800 bg-slate-900">
<div class="mx-auto max-w-6xl px-4 py-10">
<h1 class="text-3xl font-bold text-white">Paito Togel Warna</h1>
<p class="mt-3 text-slate-300">
Data otomatis berasal dari modul Result.
</p>
</div>
</section>

<section class="bg-slate-950">
<div class="mx-auto max-w-6xl px-4 py-10">

<form method="GET" class="flex flex-col gap-3 rounded-xl border border-slate-800 bg-slate-900 p-5 sm:flex-row">
<select name="market" class="flex-1 rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white">
<option value="">Semua pasaran</option>
@foreach($markets as $market)
<option value="{{ $market->slug }}" @selected(($filters['market'] ?? '') === $market->slug)>
{{ $market->name }}
</option>
@endforeach
</select>
<button class="rounded-lg bg-amber-400 px-6 py-3 font-semibold text-slate-950">
Tampilkan
</button>
</form>

<div
    class="mt-6 rounded-xl border border-slate-800 bg-slate-900 p-5"
    data-paito-palette
>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-lg font-bold text-white">
                Pilih Warna
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Pilih warna, kemudian klik angka pada tabel.
            </p>
        </div>

        <div class="inline-flex w-fit items-center gap-3 rounded-lg border border-slate-700 bg-slate-950 px-4 py-2">
            <span
                id="active-color-preview"
                class="h-5 w-5 rounded border border-white/50"
                style="background-color: #ef4444"
            ></span>

            <span class="text-sm text-slate-400">
                Aktif:
            </span>

            <strong
                id="active-color-label"
                class="text-sm text-white"
            >
                Merah
            </strong>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap gap-3">
        @foreach($palette as $name => $hex)
            <button
                type="button"
                data-tool="paint"
                data-color="{{ $name }}"
                data-color-hex="{{ $hex }}"
                data-color-label="{{ ucfirst($name) }}"
                class="paito-tool inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-semibold text-white transition"
                aria-pressed="{{ $name === 'red' ? 'true' : 'false' }}"
                style="{{ $name === 'red'
                    ? 'border-color:#fff;box-shadow:0 0 0 3px rgba(255,255,255,.30)'
                    : 'border-color:#475569' }}"
            >
                <span
                    class="h-6 w-6 rounded border border-white/50"
                    style="background-color: {{ $hex }}"
                ></span>

                <span>{{ ucfirst($name) }}</span>
            </button>
        @endforeach

        <button
            type="button"
            data-tool="erase"
            data-color-label="Hapus"
            class="paito-tool inline-flex items-center gap-2 rounded-lg border border-slate-600 px-4 py-2 text-sm font-semibold text-white transition"
            aria-pressed="false"
        >
            <span class="text-lg leading-none">×</span>
            <span>Hapus</span>
        </button>

@if(($filters['market'] ?? '') !== '')
<button
type="button"
id="clear-all"
class="rounded-lg border border-red-500 px-4 py-2 text-sm font-semibold text-red-300"
>
Hapus Semua Warna
</button>
@endif
</div>
</div>

<div class="mt-6 rounded-xl border border-slate-800 bg-slate-900 p-5">
    <h2 class="text-lg font-bold text-white">
        Pewarnaan Otomatis
    </h2>

    <p class="mt-1 text-sm text-slate-400">
        Isi digit yang ingin ditandai. Warna mengikuti
        warna aktif pada pilihan warna di atas.
    </p>

    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        @foreach([
            'as' => 'AS',
            'kop' => 'KOP',
            'kepala' => 'KEPALA',
            'ekor' => 'EKOR',
            'jumlah' => 'JUMLAH',
        ] as $position => $label)
            <div>
                <label
                    for="auto-{{ $position }}"
                    class="mb-2 block text-sm font-semibold text-white"
                >
                    {{ $label }}
                </label>

                <input
                    id="auto-{{ $position }}"
                    data-auto-position="{{ $position }}"
                    maxlength="10"
                    inputmode="numeric"
                    placeholder="Contoh: 123"
                    class="auto-paito-input w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white"
                >

                <select
                    data-auto-color="{{ $position }}"
                    class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-3 text-white"
                >
                    @foreach ($palette as $colorName => $hex)
                        <option
                            value="{{ $colorName }}"
                            @selected($colorName === 'red')
                        >
                            {{ ucfirst($colorName) }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-4">
        <button
            type="button"
            id="auto-paint"
            class="rounded-lg bg-emerald-500 px-6 py-3 font-semibold text-white transition hover:bg-emerald-400 disabled:cursor-not-allowed disabled:opacity-50"
        >
            Proses Otomatis
        </button>

        <p
            id="paito-status"
            class="hidden text-sm font-semibold"
            role="status"
            aria-live="polite"
        ></p>
    </div>
</div>

@if($rows->isEmpty())
<div class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-8 text-center text-slate-300">
Belum ada Result pada pasaran yang dipilih.
</div>
@else
<div
    class="mt-8 overflow-x-auto rounded-xl border border-slate-700 bg-slate-950 shadow-xl"
    data-paito-classic-grid
>
<table
    class="w-full min-w-[560px] table-fixed border-collapse bg-slate-900"
>
<thead>
<tr class="bg-slate-800 text-center text-xs font-bold uppercase tracking-wider text-slate-300">
    <th
        class="w-28 border border-slate-600 px-3 py-3"
    >
        Hari
    </th>

    @for ($column = 1; $column <= 5; $column++)
        <th
            class="border border-slate-600 p-0"
            aria-label="Kolom angka {{ $column }}"
        >
            <span class="sr-only">
                Angka {{ $column }}
            </span>
        </th>
    @endfor
</tr>
</thead>
<tbody>
@foreach($rows as $row)
<tr
    data-date="{{ $row['date'] }}"
    data-market="{{ $row['market'] }}"
    data-result="{{ $row['winning_numbers'] }}"
    class="odd:bg-slate-900 even:bg-slate-950/60 hover:bg-slate-800/70"
>
    <th
        scope="row"
        data-paito-day
        class="border border-slate-600 bg-slate-800 px-3 py-3 text-center text-sm font-black uppercase tracking-wide text-amber-300"
    >
        {{
            \Illuminate\Support\Carbon::parse(
                $row['date']
            )
                ->locale('id')
                ->translatedFormat('l')
        }}
    </th>

@foreach($row['values'] as $position => $digit)
@php
$colorName = $row['colors'][$position] ?? null;
$background = $colorName ? ($palette[$colorName] ?? null) : null;
@endphp
<td
    class="paito-cell h-16 cursor-pointer select-none border border-slate-600 p-0 text-center align-middle text-2xl font-black text-white transition hover:brightness-125 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-amber-300"
    data-result-id="{{ $row['id'] }}"
    data-position="{{ $position }}"
    data-color="{{ $colorName }}"
    data-digit="{{ $digit }}"
    tabindex="0"
    role="button"
    aria-label="Warnai angka {{ $digit }}"
    style="{{ $background ? 'background-color: '.$background : '' }}"
>
    <span class="inline-flex h-12 w-12 items-center justify-center">
        {{ $digit }}
    </span>
</td>
@endforeach
</tr>
@endforeach
</tbody>
</table>
</div>
@endif

</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const colors = @json($palette);
    let tool = 'paint';
    let activeColor = 'red';
    let requestInProgress = false;

    const statusElement = document.getElementById(
        'paito-status'
    );

    const showStatus = (message, type = 'success') => {
        if (!statusElement) {
            return;
        }

        statusElement.textContent = message;
        statusElement.classList.remove(
            'hidden',
            'text-emerald-400',
            'text-red-400',
            'text-amber-400'
        );

        statusElement.classList.add(
            type === 'error'
                ? 'text-red-400'
                : type === 'warning'
                    ? 'text-amber-400'
                    : 'text-emerald-400'
        );
    };

    const paletteButtons = document.querySelectorAll(
        '.paito-tool'
    );

    const activeColorPreview = document.getElementById(
        'active-color-preview'
    );

    const activeColorLabel = document.getElementById(
        'active-color-label'
    );

    paletteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            tool = button.dataset.tool || 'paint';

            if (button.dataset.color) {
                activeColor = button.dataset.color;
            }

            paletteButtons.forEach((item) => {
                item.setAttribute(
                    'aria-pressed',
                    item === button ? 'true' : 'false'
                );

                item.style.borderColor = '#475569';
                item.style.boxShadow = '';
            });

            button.style.borderColor = '#ffffff';
            button.style.boxShadow =
                '0 0 0 3px rgba(255,255,255,.30)';

            if (tool === 'erase') {
                activeColorPreview.style.backgroundColor =
                    'transparent';

                activeColorPreview.textContent = '×';
                activeColorLabel.textContent = 'Hapus';
                return;
            }

            activeColorPreview.textContent = '';
            activeColorPreview.style.backgroundColor =
                button.dataset.colorHex;

            activeColorLabel.textContent =
                button.dataset.colorLabel || activeColor;
        });
    });

    const handleCellPaint = async (cell) => {
        if (requestInProgress) {
            return;
        }

        const resultId = cell.dataset.resultId;
        const position = cell.dataset.position;
        const deleting = tool === 'erase';

        requestInProgress = true;
        cell.classList.add('opacity-60');

        try {
            const response = await fetch(
                `/alat-togel/paito-warna/result/${resultId}/color`,
                {
                    method: deleting ? 'DELETE' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify(
                        deleting
                            ? { position }
                            : { position, color: activeColor }
                    ),
                }
            );

            if (!response.ok) {
                throw new Error('Gagal menyimpan warna.');
            }

            cell.style.backgroundColor =
                deleting ? '' : colors[activeColor];

            cell.dataset.color =
                deleting ? '' : activeColor;

            showStatus(
                deleting
                    ? 'Warna sel berhasil dihapus.'
                    : 'Warna sel berhasil disimpan.'
            );
        } catch (error) {
            showStatus(
                error.message || 'Terjadi kesalahan.',
                'error'
            );
        } finally {
            requestInProgress = false;
            cell.classList.remove('opacity-60');
        }
    };

    document.querySelectorAll('.paito-cell')
        .forEach((cell) => {
            cell.addEventListener(
                'click',
                () => handleCellPaint(cell)
            );

            cell.addEventListener('keydown', (event) => {
                if (
                    event.key !== 'Enter'
                    && event.key !== ' '
                ) {
                    return;
                }

                event.preventDefault();
                handleCellPaint(cell);
            });
        });

    document.getElementById('auto-paint')
        ?.addEventListener('click', async () => {
            if (requestInProgress) {
                return;
            }

            const autoButton = document.getElementById(
                'auto-paint'
            );

            const rules = {};

            document.querySelectorAll('.auto-paito-input')
                .forEach((input) => {
                    const digits = input.value.replace(/\D/g, '');

                    if (digits === '') {
                        return;
                    }

                    const position = input.dataset.autoPosition;
                    const color = document.querySelector(
                        `[data-auto-color="${position}"]`
                    )?.value || 'red';

                    rules[position] = {
                        digits: [...new Set(digits.split(''))],
                        color,
                    };
                });

            if (Object.keys(rules).length === 0) {
                showStatus(
                    'Isi minimal satu kolom angka.',
                    'warning'
                );
                return;
            }

            const cells = [];

            document.querySelectorAll('.paito-cell')
                .forEach((cell) => {
                    const rule = rules[cell.dataset.position];
                    const digit =
                        cell.dataset.digit
                        || cell.textContent.trim();

                    if (
                        rule
                        && rule.digits.includes(digit)
                    ) {
                        cells.push({
                            result_id: Number(
                                cell.dataset.resultId
                            ),
                            position: cell.dataset.position,
                            color: rule.color,
                        });
                    }
                });

            if (cells.length === 0) {
                showStatus(
                    'Tidak ada angka yang cocok.',
                    'warning'
                );
                return;
            }

            requestInProgress = true;
            autoButton?.setAttribute('disabled', 'disabled');

            try {
                const response = await fetch(
                    '/alat-togel/paito-warna/colors/bulk',
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({ cells }),
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        'Gagal memproses pewarnaan otomatis.'
                    );
                }

                document.querySelectorAll('.paito-cell')
                    .forEach((cell) => {
                        const rule =
                            rules[cell.dataset.position];

                        const digit =
                            cell.dataset.digit
                            || cell.textContent.trim();

                        if (
                            rule
                            && rule.digits.includes(digit)
                        ) {
                            cell.style.backgroundColor =
                                colors[rule.color];

                            cell.dataset.color = rule.color;
                        }
                    });

                showStatus(
                    `${cells.length} sel berhasil diwarnai.`
                );
            } catch (error) {
                showStatus(
                    error.message || 'Terjadi kesalahan.',
                    'error'
                );
            } finally {
                requestInProgress = false;
                autoButton?.removeAttribute('disabled');
            }
        });

    document.getElementById('clear-all')?.addEventListener('click', async () => {
        if (!confirm('Hapus semua warna pada pasaran ini?')) {
            return;
        }

        const market = @json($markets->firstWhere(
            'slug',
            $filters['market'] ?? ''
        )?->getKey());

        const response = await fetch(
            `/alat-togel/paito-warna/market/${market}/colors`,
            {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
            }
        );

        if (!response.ok) {
            alert('Gagal menghapus warna.');
            return;
        }

        document.querySelectorAll('.paito-cell').forEach((cell) => {
            cell.style.backgroundColor = '';
            cell.dataset.color = '';
        });
    });
});
</script>
@endsection
