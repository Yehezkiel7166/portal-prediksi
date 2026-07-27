@php
    use App\Domains\Domain\Enums\DomainVerificationStatus;

    $status = $history->verification_status instanceof DomainVerificationStatus
        ? $history->verification_status
        : DomainVerificationStatus::tryFrom(
            (string) $history->verification_status
        );

    $statusLabel = match ($status) {
        DomainVerificationStatus::Healthy => 'Healthy',
        DomainVerificationStatus::Warning => 'Warning',
        DomainVerificationStatus::Critical => 'Critical',
        default => 'Unknown',
    };

    $statusClasses = match ($status) {
        DomainVerificationStatus::Healthy =>
            'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400',

        DomainVerificationStatus::Warning =>
            'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-400',

        DomainVerificationStatus::Critical =>
            'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400',

        default =>
            'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400',
    };

    $difference = $previous === null
        ? null
        : $history->verification_score
            - $previous->verification_score;

    $trendLabel = match (true) {
        $difference === null => 'Baseline',
        $difference > 0 => 'Improved +'.$difference,
        $difference < 0 => 'Degraded '.$difference,
        default => 'Unchanged',
    };

    $trendClasses = match (true) {
        $difference === null =>
            'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400',

        $difference > 0 =>
            'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400',

        $difference < 0 =>
            'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400',

        default =>
            'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400',
    };

    $checks = $history->verification_checks ?? [];
@endphp

<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div
            class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
        >
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Status
            </div>

            <div class="mt-2">
                <span
                    class="inline-flex items-center rounded-md px-2 py-1 text-sm font-medium ring-1 ring-inset {{ $statusClasses }}"
                >
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        <div
            class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
        >
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Verification Score
            </div>

            <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $history->verification_score }}/100
            </div>
        </div>

        <div
            class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
        >
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Perubahan
            </div>

            <div class="mt-2">
                <span
                    class="inline-flex items-center rounded-md px-2 py-1 text-sm font-medium ring-1 ring-inset {{ $trendClasses }}"
                >
                    {{ $trendLabel }}
                </span>
            </div>
        </div>

        <div
            class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
        >
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Waktu pemeriksaan
            </div>

            <div class="mt-2 text-sm font-semibold text-gray-950 dark:text-white">
                {{ $history->verified_at->format('d M Y H:i:s') }}
            </div>
        </div>
    </div>

    @if ($previous !== null)
        <div
            class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
        >
            <div class="text-sm font-semibold text-gray-950 dark:text-white">
                Perbandingan sebelumnya
            </div>

            <div class="mt-3 grid gap-4 sm:grid-cols-3">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Pemeriksaan sebelumnya
                    </div>

                    <div class="mt-1 text-sm text-gray-950 dark:text-white">
                        {{ $previous->verified_at->format('d M Y H:i:s') }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Skor sebelumnya
                    </div>

                    <div class="mt-1 text-sm text-gray-950 dark:text-white">
                        {{ $previous->verification_score }}/100
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Selisih
                    </div>

                    <div class="mt-1 text-sm text-gray-950 dark:text-white">
                        {{ $difference > 0 ? '+' : '' }}{{ $difference }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div>
        <div class="mb-3 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                Verification Checks
            </h3>

            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ count($checks) }} checks
            </span>
        </div>

        @if ($checks === [])
            <div
                class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500 dark:border-white/20 dark:text-gray-400"
            >
                Tidak ada detail pemeriksaan.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($checks as $check)
                    @php
                        $checkStatus = DomainVerificationStatus::tryFrom(
                            (string) ($check['status'] ?? 'unknown')
                        );

                        $checkClasses = match ($checkStatus) {
                            DomainVerificationStatus::Healthy =>
                                'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400',

                            DomainVerificationStatus::Warning =>
                                'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-400',

                            DomainVerificationStatus::Critical =>
                                'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400',

                            default =>
                                'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400',
                        };

                        $checkLabel = match ($checkStatus) {
                            DomainVerificationStatus::Healthy =>
                                'Healthy',

                            DomainVerificationStatus::Warning =>
                                'Warning',

                            DomainVerificationStatus::Critical =>
                                'Critical',

                            default =>
                                'Unknown',
                        };

                        $metadata = $check['metadata'] ?? [];
                    @endphp

                    <div
                        class="rounded-xl bg-white p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-gray-950 dark:text-white">
                                    {{ $check['label'] ?? $check['key'] ?? 'Check' }}
                                </div>

                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $check['message'] ?? 'Tidak ada pesan.' }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $checkClasses }}"
                                >
                                    {{ $checkLabel }}
                                </span>

                                <span
                                    class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400"
                                >
                                    {{ $check['score'] ?? 0 }}/100
                                </span>
                            </div>
                        </div>

                        @if ($metadata !== [])
                            <div class="mt-4 overflow-hidden rounded-lg bg-gray-50 dark:bg-black/20">
                                <table class="w-full text-left text-xs">
                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-white/10"
                                    >
                                        @foreach ($metadata as $key => $value)
                                            <tr>
                                                <th
                                                    class="w-1/3 px-3 py-2 font-medium text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ str($key)->replace('_', ' ')->title() }}
                                                </th>

                                                <td
                                                    class="px-3 py-2 text-gray-950 dark:text-white"
                                                >
                                                    @if (is_array($value))
                                                        <pre class="whitespace-pre-wrap break-all font-mono text-xs">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    @elseif (is_bool($value))
                                                        {{ $value ? 'true' : 'false' }}
                                                    @elseif ($value === null)
                                                        —
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
