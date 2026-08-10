@extends('frontend.layouts.app')

@section(
    'title',
    'Result '.$result->market->name.' '.$result->result_date->format('d-m-Y').' | '.config('app.name')
)

@section(
    'description',
    'Hasil '.$result->market->name.' tanggal '.$result->result_date->translatedFormat('d F Y').' beserta informasi result terbaru.'
)

@section('metadata')
    <link
        rel="canonical"
        href="{{ route('results.show', [
            'marketSlug' => $result->market->slug,
            'resultDate' => $result->result_date->format('Y-m-d'),
        ]) }}"
    >

    <meta
        property="og:title"
        content="Result {{ $result->market->name }} {{ $result->result_date->translatedFormat('d F Y') }}"
    >

    <meta
        property="og:description"
        content="Lihat hasil {{ $result->market->name }} tanggal {{ $result->result_date->translatedFormat('d F Y') }}."
    >

    <meta property="og:type" content="article">

    <meta
        property="og:url"
        content="{{ route('results.show', [
            'marketSlug' => $result->market->slug,
            'resultDate' => $result->result_date->format('Y-m-d'),
        ]) }}"
    >
@endsection

@section('content')
<section
    data-theme-result-hero
    class="border-b"
>
    <div class="mx-auto max-w-5xl px-4 py-8 md:py-12">
        <nav
            data-theme-muted
            class="text-sm"
            aria-label="Breadcrumb"
        >
            <a
                href="{{ route('results.index') }}"
                class="transition hover:opacity-80"
            >
                Data Result
            </a>

            <span class="mx-2">/</span>

            <a
                href="{{ route('results.history', [
                    'marketSlug' => $result->market->slug,
                ]) }}"
                class="transition hover:opacity-80"
            >
                {{ $result->market->name }}
            </a>

            <span class="mx-2">/</span>

            <span>
                {{ $result->result_date->format('d-m-Y') }}
            </span>
        </nav>
    </div>
</section>

<section data-theme-result-section>
    <div class="mx-auto max-w-5xl px-4 py-10 md:py-14">
        <article
            data-theme-result-detail
            data-theme-surface
            class="
                overflow-hidden rounded-2xl border
            "
        >
            <header
                data-theme-result-detail-header
                class="
                    border-b px-5 py-6
                    md:px-8 md:py-7
                "
            >
                <p
                    data-theme-accent
                    class="
                        text-xs font-semibold uppercase
                        tracking-[0.18em]
                    "
                >
                    Detail Result
                </p>

                <div
                    class="
                        mt-3 flex flex-col gap-4
                        sm:flex-row sm:items-end
                        sm:justify-between
                    "
                >
                    <div>
                        <h1
                            class="
                                text-3xl font-black
                                md:text-4xl
                            "
                        >
                            {{ $result->market->name }}
                        </h1>

                        <p
                            data-theme-muted
                            class="mt-2 text-sm"
                        >
                            Result resmi tanggal
                            {{ $result->result_date->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <span
                        data-theme-result-market-code
                        class="
                            self-start rounded-full
                            border px-3 py-1.5
                            text-xs font-bold
                            sm:self-auto
                        "
                    >
                        {{ $result->market->code }}
                    </span>
                </div>
            </header>

            <div class="p-5 md:p-8">
                <div
                    data-theme-result-number-panel
                    class="
                        rounded-2xl border
                        px-5 py-8 text-center
                        md:px-8 md:py-10
                    "
                >
                    <p
                        data-theme-muted
                        class="
                            text-xs font-semibold uppercase
                            tracking-[0.18em]
                        "
                    >
                        Hasil Result
                    </p>

                    <div
                        data-theme-result-number
                        class="
                            mt-4 whitespace-pre-line
                            break-words font-mono
                            text-4xl font-black
                            leading-tight tracking-[0.12em]
                            sm:text-5xl md:text-6xl
                        "
                    >
                        {{ $result->winning_numbers }}
                    </div>
                </div>

                <dl
                    class="
                        mt-6 grid gap-3
                        sm:grid-cols-3
                    "
                >
                    <div
                        data-theme-result-meta
                        class="rounded-xl border p-4"
                    >
                        <dt
                            data-theme-muted
                            class="text-xs"
                        >
                            Pasaran
                        </dt>

                        <dd class="mt-1 font-semibold">
                            {{ $result->market->name }}
                        </dd>
                    </div>

                    <div
                        data-theme-result-meta
                        class="rounded-xl border p-4"
                    >
                        <dt
                            data-theme-muted
                            class="text-xs"
                        >
                            Tanggal
                        </dt>

                        <dd class="mt-1 font-semibold">
                            {{ $result->result_date->translatedFormat('d M Y') }}
                        </dd>
                    </div>

                    <div
                        data-theme-result-meta
                        class="rounded-xl border p-4"
                    >
                        <dt
                            data-theme-muted
                            class="text-xs"
                        >
                            Zona Waktu
                        </dt>

                        <dd class="mt-1 font-semibold">
                            {{ $result->market->timezone }}
                        </dd>
                    </div>
                </dl>

                @if (filled($result->notes))
                    <section
                        data-theme-result-notes
                        class="mt-8 border-t pt-7"
                    >
                        <h2 class="text-lg font-semibold">
                            Catatan
                        </h2>

                        <p
                            data-theme-muted
                            class="
                                mt-3 whitespace-pre-line
                                leading-7
                            "
                        >
                            {{ $result->notes }}
                        </p>
                    </section>
                @endif
            </div>
        </article>

        <div
            class="
                mt-6 grid gap-3
                sm:grid-cols-2
            "
        >
            <a
                data-theme-primary-button
                href="{{ route('results.history', [
                    'marketSlug' => $result->market->slug,
                ]) }}"
                class="
                    inline-flex min-h-12
                    items-center justify-center
                    rounded-lg border px-5 py-3
                    text-center text-sm
                    font-semibold transition
                    hover:opacity-90
                "
            >
                Result {{ $result->market->name }} Lainnya
            </a>

            <a
                data-theme-secondary-button
                href="{{ route('results.index') }}"
                class="
                    inline-flex min-h-12
                    items-center justify-center
                    rounded-lg border px-5 py-3
                    text-center text-sm
                    font-semibold transition
                    hover:opacity-90
                "
            >
                Semua Data Result
            </a>
        </div>
    </div>
</section>
@endsection
