<div class="space-y-4">
    <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Status
            </div>

            <div class="mt-1 font-semibold text-gray-950 dark:text-white">
                {{ $record->verification_status?->label() ?? 'Belum diverifikasi' }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Skor
            </div>

            <div class="mt-1 font-semibold text-gray-950 dark:text-white">
                {{ $record->verification_score === null
                    ? '—'
                    : $record->verification_score.'/100' }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Terakhir diverifikasi
            </div>

            <div class="mt-1 font-semibold text-gray-950 dark:text-white">
                {{ $record->verified_at?->format('d M Y H:i:s') ?? 'Belum pernah' }}
            </div>
        </div>
    </div>

    @if ($checks === [])
        <div class="rounded-xl border border-gray-200 p-5 text-sm text-gray-600 dark:border-white/10 dark:text-gray-300">
            Belum ada detail pemeriksaan yang tersimpan.
        </div>
    @else
        <div class="space-y-3">
            @foreach ($checks as $check)
                @php
                    $status = $check['status'] ?? 'unknown';

                    $statusClasses = match ($status) {
                        'healthy' => 'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400',
                        'warning' => 'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-400',
                        'critical' => 'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400',
                        default => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-white/5 dark:text-gray-300',
                    };
                @endphp

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold text-gray-950 dark:text-white">
                                {{ $check['label'] ?? $check['key'] ?? 'Pemeriksaan' }}
                            </div>

                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                {{ $check['message'] ?? 'Tidak ada pesan pemeriksaan.' }}
                            </div>
                        </div>

                        <span
                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClasses }}"
                        >
                            {{ ucfirst((string) $status) }}
                        </span>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-gray-500 dark:text-gray-400">
                        <span>
                            Skor:
                            {{ isset($check['score'])
                                ? $check['score'].'/100'
                                : '—' }}
                        </span>

                        <span>
                            Bobot:
                            {{ $check['weight'] ?? '—' }}
                        </span>

                        <span>
                            Key:
                            {{ $check['key'] ?? '—' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
