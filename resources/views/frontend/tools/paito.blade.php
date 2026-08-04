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

<div class="mt-6 rounded-xl border border-slate-800 bg-slate-900 p-5">
<p class="mb-3 text-sm font-semibold text-white">Pilih Warna</p>

<div class="flex flex-wrap gap-3">
@foreach($palette as $name => $hex)
<button
type="button"
data-tool="paint"
data-color="{{ $name }}"
class="paito-tool h-10 w-10 rounded-lg border-2 border-transparent"
style="background-color: {{ $hex }}"
title="{{ $name }}"
></button>
@endforeach

<button
type="button"
data-tool="erase"
class="paito-tool rounded-lg border border-slate-600 px-4 py-2 text-sm font-semibold text-white"
>
Hapus
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

@if($rows->isEmpty())
<div class="mt-8 rounded-xl border border-slate-800 bg-slate-900 p-8 text-center text-slate-300">
Belum ada Result pada pasaran yang dipilih.
</div>
@else
<div class="mt-8 overflow-x-auto rounded-xl border border-slate-800">
<table class="min-w-full border-collapse bg-slate-900">
<thead>
<tr class="bg-slate-800 text-center text-sm font-bold text-white">
<th class="border border-slate-700 px-5 py-4">AS</th>
<th class="border border-slate-700 px-5 py-4">KOP</th>
<th class="border border-slate-700 px-5 py-4">KEPALA</th>
<th class="border border-slate-700 px-5 py-4">EKOR</th>
<th class="border border-slate-700 px-5 py-4">JUMLAH</th>
</tr>
</thead>
<tbody>
@foreach($rows as $row)
<tr
data-date="{{ $row['date'] }}"
data-market="{{ $row['market'] }}"
data-result="{{ $row['winning_numbers'] }}"
>
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

    document.querySelectorAll('.paito-tool').forEach((button) => {
        button.addEventListener('click', () => {
            tool = button.dataset.tool;

            if (button.dataset.color) {
                activeColor = button.dataset.color;
            }

            document.querySelectorAll('.paito-tool')
                .forEach((item) => item.classList.remove('ring-4', 'ring-white'));

            button.classList.add('ring-4', 'ring-white');
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
