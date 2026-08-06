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

    <button
        type="button"
        id="auto-paint"
        class="mt-4 rounded-lg bg-emerald-500 px-6 py-3 font-semibold text-white hover:bg-emerald-400"
    >
        Proses Otomatis
    </button>
</div>

@if($rows->isEmpty())
<div class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-8 text-center text-slate-300">
Belum ada Result pada pasaran yang dipilih.
</div>
@else
<div class="mt-8 overflow-x-auto rounded-xl border border-slate-800">
<table class="min-w-full border-collapse bg-slate-900">
<thead>
<tr class="bg-slate-800 text-center text-sm font-bold text-white">
    <th class="border border-slate-700 px-4 py-4">
        Hari
    </th>

    @for ($column = 1; $column <= 5; $column++)
        <th
            class="border border-slate-700 px-5 py-4"
            aria-label="Angka {{ $column }}"
        ></th>
    @endfor
</tr>
</thead>
<tbody>
@foreach($rows as $row)
<tr
    data-date="{{ $row['date'] }}"
    data-market="{{ $row['market'] }}"
    data-result="{{ $row['winning_numbers'] }}"
>
    <th
        scope="row"
        data-paito-day
        class="whitespace-nowrap border border-slate-700 bg-slate-800 px-4 py-4 text-left text-sm font-bold uppercase text-amber-300"
    >
        {{
            \Illuminate\Support\Carbon::parse($row['date'])
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
class="paito-cell cursor-pointer select-none border border-slate-700 px-5 py-4 text-center text-2xl font-bold text-white"
data-result-id="{{ $row['id'] }}"
data-position="{{ $position }}"
data-color="{{ $colorName }}"
style="{{ $background ? 'background-color: '.$background : '' }}"
>
{{ $digit }}
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

    document.querySelectorAll('.paito-cell').forEach((cell) => {
        cell.addEventListener('click', async () => {
            const resultId = cell.dataset.resultId;
            const position = cell.dataset.position;
            const deleting = tool === 'erase';

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
                alert('Gagal menyimpan warna.');
                return;
            }

            cell.style.backgroundColor =
                deleting ? '' : colors[activeColor];

            cell.dataset.color =
                deleting ? '' : activeColor;
        });
    });

    document.getElementById('auto-paint')?.addEventListener('click', async () => {
        const rules = {};

        document.querySelectorAll('.auto-paito-input')
            .forEach((input) => {
                const digits = input.value.replace(/\D/g, '');

                if (digits !== '') {
                    const position = input.dataset.autoPosition;
                    const color = document.querySelector(
                        `[data-auto-color="${position}"]`
                    )?.value || 'red';

                    rules[position] = {
                        digits: [...new Set(digits.split(''))],
                        color,
                    };
                }
            });

        if (Object.keys(rules).length === 0) {
            alert('Isi minimal satu kolom angka.');
            return;
        }

        const cells = [];

        document.querySelectorAll('.paito-cell')
            .forEach((cell) => {
                const rule = rules[cell.dataset.position];

                if (
                    rule
                    && rule.digits.includes(cell.textContent.trim())
                ) {
                    cells.push({
                        result_id: Number(cell.dataset.resultId),
                        position: cell.dataset.position,
                        color: rule.color,
                    });
                }
            });

        if (cells.length === 0) {
            alert('Tidak ada angka yang cocok.');
            return;
        }

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
            alert('Gagal memproses pewarnaan otomatis.');
            return;
        }

        document.querySelectorAll('.paito-cell')
            .forEach((cell) => {
                const rule = rules[cell.dataset.position];

                if (
                    rule
                    && rule.digits.includes(cell.textContent.trim())
                ) {
                    cell.style.backgroundColor = colors[rule.color];
                    cell.dataset.color = rule.color;
                }
            });
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
