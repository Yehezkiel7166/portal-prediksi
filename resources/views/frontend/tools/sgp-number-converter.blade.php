@extends('frontend.layouts.app')

@section('title', 'Konversi SGP TOTO | '.config('app.name'))

@section(
    'description',
    'Konversi tujuh angka Winning Numbers menjadi hasil SGP empat digit.'
)

@section('metadata')
<link
    rel="canonical"
    href="{{ route('tools.sgp-converter.create') }}"
>
<meta
    property="og:title"
    content="Konversi SGP TOTO | {{ config('app.name') }}"
>
<meta
    property="og:description"
    content="Konversi tujuh angka Winning Numbers menjadi hasil SGP empat digit."
>
<meta property="og:type" content="website">
<meta
    property="og:url"
    content="{{ route('tools.sgp-converter.create') }}"
>
@endsection

@section('content')

<style>
    [data-sgp-converter] {
        --sgp-page-bg: #020617;
        --sgp-surface: #0f172a;
        --sgp-surface-soft: rgba(2, 6, 23, .80);
        --sgp-border: #334155;

        --sgp-primary: #22d3ee;
        --sgp-danger: #e11d48;

        --sgp-text: #ffffff;
        --sgp-muted: #94a3b8;

        --sgp-result-border: #f43f5e;

        background: var(--sgp-page-bg);
        border-bottom: 1px solid var(--sgp-border);
        padding: 28px 14px 34px;
    }

    .sgp-shell {
        width: min(100%, 660px);
        margin-inline: auto;
    }

    .sgp-card {
        overflow: hidden;

        border: 1px solid var(--sgp-border);
        border-radius: 16px;

        background:
            radial-gradient(
                circle at 50% 0%,
                rgba(34, 211, 238, .10),
                transparent 42%
            ),
            var(--sgp-surface);

        box-shadow:
            0 18px 45px rgba(0, 0, 0, .26);
    }

    .sgp-inner {
        padding: 24px 22px 26px;
    }

    .sgp-heading {
        text-align: center;
    }

    .sgp-eyebrow {
        margin: 0;

        color: var(--sgp-primary);

        font-size: 10px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .sgp-title {
        margin: 5px 0 0;

        color: var(--sgp-text);

        font-size: clamp(21px, 3vw, 28px);
        font-weight: 900;
        line-height: 1.15;
    }

    .sgp-description {
        margin: 7px 0 0;

        color: var(--sgp-muted);

        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | Desktop / Tablet: 7 compact cells
    |--------------------------------------------------------------------------
    */

    .sgp-input-grid {
        display: grid;
        grid-template-columns: repeat(7, 64px);
        justify-content: center;

        gap: 8px;

        margin-top: 22px;
    }

    .sgp-number-input {
        box-sizing: border-box;

        width: 64px;
        height: 44px;

        border: 1px solid #475569;
        border-radius: 8px;

        background: var(--sgp-surface-soft);
        color: var(--sgp-text);

        padding: 0 4px;

        font-size: 16px;
        font-weight: 800;
        line-height: 1;
        text-align: center;

        outline: none;

        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    .sgp-number-input::placeholder {
        color: #64748b;
        opacity: .8;
    }

    .sgp-number-input:focus {
        border-color: var(--sgp-primary);

        box-shadow:
            0 0 0 3px rgba(34, 211, 238, .12);
    }

    .sgp-validation {
        margin: 11px 0 0;

        color: #fb7185;

        font-size: 12px;
        font-weight: 700;
        text-align: center;
    }

    .sgp-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;

        gap: 9px;

        margin-top: 18px;
    }

    .sgp-button {
        min-width: 106px;
        min-height: 36px;

        border: 0;
        border-radius: 7px;

        padding: 8px 16px;

        cursor: pointer;

        font-size: 12px;
        font-weight: 800;

        transition:
            filter .15s ease,
            transform .15s ease;
    }

    .sgp-button:hover {
        filter: brightness(1.08);
    }

    .sgp-button:active {
        transform: translateY(1px);
    }

    .sgp-button-convert {
        background: var(--sgp-primary);
        color: #020617;
    }

    .sgp-button-reset {
        background: var(--sgp-danger);
        color: #ffffff;
    }

    .sgp-result-section {
        margin-top: 22px;
        text-align: center;
    }

    .sgp-result-label {
        margin: 0 0 7px;

        color: var(--sgp-primary);

        font-size: 10px;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .sgp-result {
        display: flex;
        align-items: center;
        justify-content: center;

        box-sizing: border-box;

        width: min(100%, 360px);
        min-height: 66px;

        margin-inline: auto;

        border: 1px solid var(--sgp-result-border);
        border-radius: 10px;

        background: var(--sgp-surface-soft);
        color: var(--sgp-text);

        padding: 10px 16px;

        font-size: clamp(32px, 5vw, 46px);
        font-weight: 900;
        letter-spacing: .16em;
        line-height: 1;

        box-shadow:
            0 0 22px rgba(244, 63, 94, .10);
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    |
    | Tidak pernah menjadi 7 input full width vertikal.
    | Layout: 4 sel + 3 sel.
    |
    */

    @media (max-width: 639px) {
        [data-sgp-converter] {
            padding:
                22px
                10px
                28px;
        }

        .sgp-inner {
            padding:
                21px
                12px
                23px;
        }

        .sgp-shell {
            width: min(100%, 390px);
        }

        .sgp-input-grid {
            grid-template-columns: repeat(4, 58px);

            width: fit-content;

            margin-top: 18px;
            margin-inline: auto;

            gap: 7px;
        }

        .sgp-number-input {
            width: 58px;
            height: 42px;

            font-size: 15px;
        }

        .sgp-result {
            width: min(100%, 300px);
            min-height: 62px;
        }
    }

    @media (max-width: 300px) {
        .sgp-input-grid {
            grid-template-columns: repeat(4, 52px);

            gap: 5px;
        }

        .sgp-number-input {
            width: 52px;
            height: 40px;
        }

        .sgp-button {
            min-width: 96px;
        }
    }
</style>

<section data-sgp-converter>
    <div class="sgp-shell">
        <div
            class="sgp-card"
            data-theme-surface
        >
            <div class="sgp-inner">
                <div class="sgp-heading">
                    <p class="sgp-eyebrow">
                        Alat Togel
                    </p>

                    <h1 class="sgp-title">
                        Konversi SGP TOTO
                    </h1>

                    <p class="sgp-description">
                        Masukkan 7 angka Winning Numbers
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('tools.sgp-converter.store') }}"
                    data-sgp-form
                >
                    @csrf

                    <div
                        class="sgp-input-grid"
                        data-sgp-inputs
                    >
                        @foreach (range(0, 6) as $index)
                            <input
                                type="text"
                                name="balls[]"
                                value="{{ old("balls.$index", $balls[$index] ?? '') }}"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="off"
                                maxlength="3"
                                aria-label="Winning number {{ $index + 1 }}"
                                data-sgp-ball="{{ $index + 1 }}"
                                class="sgp-number-input"
                                placeholder="--"
                            >
                        @endforeach
                    </div>

                    @error('balls')
                        <p
                            class="sgp-validation"
                            role="alert"
                        >
                            {{ $message }}
                        </p>
                    @enderror

                    @error('balls.*')
                        <p
                            class="sgp-validation"
                            role="alert"
                        >
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="sgp-actions">
                        <button
                            type="submit"
                            class="
                                sgp-button
                                sgp-button-convert
                            "
                            data-sgp-convert
                        >
                            Convert
                        </button>

                        <button
                            type="button"
                            class="
                                sgp-button
                                sgp-button-reset
                            "
                            data-sgp-reset
                        >
                            Hapus
                        </button>
                    </div>
                </form>

                <div class="sgp-result-section">
                    <p class="sgp-result-label">
                        Hasil Konversi
                    </p>

                    <output
                        class="sgp-result"
                        data-sgp-result
                        aria-live="polite"
                    >{{ $result ?? 'XXXX' }}</output>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector(
        '[data-sgp-converter]',
    );

    if (!root) {
        return;
    }

    const form = root.querySelector(
        '[data-sgp-form]',
    );

    const inputs = Array.from(
        root.querySelectorAll(
            '[data-sgp-ball]',
        ),
    );

    const result = root.querySelector(
        '[data-sgp-result]',
    );

    const reset = root.querySelector(
        '[data-sgp-reset]',
    );

    inputs.forEach((input, index) => {
        input.addEventListener(
            'input',
            () => {
                input.value =
                    input.value.replace(/\D/g, '');
            },
        );

        input.addEventListener(
            'keydown',
            (event) => {
                if (
                    event.key === 'Enter'
                    && index < inputs.length - 1
                ) {
                    event.preventDefault();

                    inputs[index + 1].focus();
                }
            },
        );
    });

    reset?.addEventListener(
        'click',
        () => {
            form?.reset();

            inputs.forEach((input) => {
                input.value = '';
            });

            if (result) {
                result.textContent = 'XXXX';
            }

            inputs[0]?.focus();
        },
    );
});
</script>
@endsection
