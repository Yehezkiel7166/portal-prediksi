@if ($homepageBanners->isNotEmpty())
    <section
        data-homepage-slider
        data-autoplay-delay="5000"
        aria-roledescription="carousel"
        aria-label="Banner utama"
        class="relative overflow-hidden border-b border-slate-800 bg-slate-950"
    >
        <div
            data-slider-track
            class="relative"
            style="height: clamp(280px, 32vw, 460px); min-height: 280px;"
        >
            @foreach ($homepageBanners as $index => $banner)
                @php
                    $desktopUrl = Storage::disk('public')->url(
                        $banner->desktop_image_path
                    );

                    $mobileUrl = filled($banner->mobile_image_path)
                        ? Storage::disk('public')->url(
                            $banner->mobile_image_path
                        )
                        : $desktopUrl;


                @endphp

                <article
                    data-slider-slide
                    aria-roledescription="slide"
                    aria-label="{{ $index + 1 }} dari {{ $homepageBanners->count() }}"
                    aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                    @class([
                        'absolute inset-0 transition-opacity duration-700',
                        'z-10 opacity-100' => $index === 0,
                        'pointer-events-none z-0 opacity-0' => $index !== 0,
                    ])
                >
                    <picture class="absolute inset-0 block h-full w-full">
                        <source
                            media="(max-width: 639px)"
                            srcset="{{ $mobileUrl }}"
                        >

                        <img
                            src="{{ $desktopUrl }}"
                            alt="{{ $banner->title }}"
                            class="block h-full w-full object-contain"
                            style="height: 100%; width: 100%; object-fit: contain;"
                            @if ($index === 0)
                                fetchpriority="high"
                            @else
                                loading="lazy"
                            @endif
                        >
                    </picture>

                </article>
            @endforeach
        </div>

        @if ($homepageBanners->count() > 1)
            <button
                type="button"
                data-slider-previous
                aria-label="Banner sebelumnya"
                class="absolute left-3 top-1/2 z-30 -translate-y-1/2 rounded-full border border-white/20 bg-slate-950/70 p-3 text-white backdrop-blur transition hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-400"
            >
                <span aria-hidden="true">‹</span>
            </button>

            <button
                type="button"
                data-slider-next
                aria-label="Banner berikutnya"
                class="absolute right-3 top-1/2 z-30 -translate-y-1/2 rounded-full border border-white/20 bg-slate-950/70 p-3 text-white backdrop-blur transition hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-400"
            >
                <span aria-hidden="true">›</span>
            </button>

            <div
                data-slider-indicators
                aria-label="Pilih banner"
                class="absolute bottom-4 left-1/2 z-30 flex -translate-x-1/2 gap-2"
            >
                @foreach ($homepageBanners as $index => $banner)
                    <button
                        type="button"
                        data-slider-indicator="{{ $index }}"
                        aria-label="Tampilkan banner {{ $index + 1 }}"
                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        @class([
                            'h-2.5 rounded-full transition-all',
                            'w-8 bg-amber-400' => $index === 0,
                            'w-2.5 bg-white/50 hover:bg-white/80' => $index !== 0,
                        ])
                    ></button>
                @endforeach
            </div>
        @endif
    </section>
@else
    <section class="border-b border-slate-800 bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-16 md:py-20">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase tracking-widest text-amber-400">
                    Portal Informasi Terpadu
                </p>

                <h1 class="text-4xl font-bold leading-tight text-white md:text-6xl">
                    Prediksi, hasil pasaran, dan informasi draw dalam satu portal.
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                    Akses Live Draw, Prediksi Togel, Data Result, Promosi,
                    dan layanan publik melalui tampilan yang cepat,
                    terstruktur, dan responsif.
                </p>
            </div>
        </div>
    </section>
@endif

@if ($homepageBanners->isNotEmpty())
    @once
        <script>
            (() => {
                const initializeHomepageSlider = (slider) => {
                    const slides = Array.from(
                        slider.querySelectorAll(
                            '[data-slider-slide]'
                        )
                    );

                    if (slides.length <= 1) {
                        return;
                    }

                    const previousButton = slider.querySelector(
                        '[data-slider-previous]'
                    );

                    const nextButton = slider.querySelector(
                        '[data-slider-next]'
                    );

                    const indicators = Array.from(
                        slider.querySelectorAll(
                            '[data-slider-indicator]'
                        )
                    );

                    const autoplayDelay = Number.parseInt(
                        slider.dataset.autoplayDelay ?? '5000',
                        10
                    );

                    const normalizedDelay = (
                        Number.isFinite(autoplayDelay)
                        && autoplayDelay >= 1000
                    )
                        ? autoplayDelay
                        : 5000;

                    let activeIndex = 0;
                    let intervalId = null;
                    let isPaused = false;

                    const showSlide = (requestedIndex) => {
                        const nextIndex = (
                            requestedIndex + slides.length
                        ) % slides.length;

                        slides.forEach((slide, index) => {
                            const isActive =
                                index === nextIndex;

                            slide.classList.toggle(
                                'z-10',
                                isActive
                            );

                            slide.classList.toggle(
                                'opacity-100',
                                isActive
                            );

                            slide.classList.toggle(
                                'z-0',
                                ! isActive
                            );

                            slide.classList.toggle(
                                'opacity-0',
                                ! isActive
                            );

                            slide.classList.toggle(
                                'pointer-events-none',
                                ! isActive
                            );

                            slide.setAttribute(
                                'aria-hidden',
                                isActive
                                    ? 'false'
                                    : 'true'
                            );
                        });

                        indicators.forEach(
                            (indicator, index) => {
                                const isActive =
                                    index === nextIndex;

                                indicator.setAttribute(
                                    'aria-current',
                                    isActive
                                        ? 'true'
                                        : 'false'
                                );

                                indicator.classList.toggle(
                                    'w-8',
                                    isActive
                                );

                                indicator.classList.toggle(
                                    'bg-amber-400',
                                    isActive
                                );

                                indicator.classList.toggle(
                                    'w-2.5',
                                    ! isActive
                                );

                                indicator.classList.toggle(
                                    'bg-white/50',
                                    ! isActive
                                );
                            }
                        );

                        activeIndex = nextIndex;
                    };

                    const stopAutoplay = () => {
                        if (intervalId === null) {
                            return;
                        }

                        window.clearInterval(intervalId);
                        intervalId = null;
                    };

                    const startAutoplay = () => {
                        stopAutoplay();

                        if (isPaused) {
                            return;
                        }

                        intervalId = window.setInterval(
                            () => {
                                showSlide(activeIndex + 1);
                            },
                            normalizedDelay
                        );
                    };

                    const pauseAutoplay = () => {
                        isPaused = true;
                        stopAutoplay();
                    };

                    const resumeAutoplay = () => {
                        isPaused = false;
                        startAutoplay();
                    };

                    previousButton?.addEventListener(
                        'click',
                        () => {
                            showSlide(activeIndex - 1);
                            startAutoplay();
                        }
                    );

                    nextButton?.addEventListener(
                        'click',
                        () => {
                            showSlide(activeIndex + 1);
                            startAutoplay();
                        }
                    );

                    indicators.forEach((indicator) => {
                        indicator.addEventListener(
                            'click',
                            () => {
                                const index =
                                    Number.parseInt(
                                        indicator.dataset
                                            .sliderIndicator
                                            ?? '0',
                                        10
                                    );

                                showSlide(index);
                                startAutoplay();
                            }
                        );
                    });

                    slider.addEventListener(
                        'mouseenter',
                        pauseAutoplay
                    );

                    slider.addEventListener(
                        'mouseleave',
                        resumeAutoplay
                    );

                    slider.addEventListener(
                        'focusin',
                        pauseAutoplay
                    );

                    slider.addEventListener(
                        'focusout',
                        resumeAutoplay
                    );

                    document.addEventListener(
                        'visibilitychange',
                        () => {
                            if (document.hidden) {
                                stopAutoplay();

                                return;
                            }

                            startAutoplay();
                        }
                    );

                    showSlide(0);
                    startAutoplay();
                };

                const initializeHomepageSliders = () => {
                    document
                        .querySelectorAll(
                            '[data-homepage-slider]'
                        )
                        .forEach(
                            initializeHomepageSlider
                        );
                };

                if (
                    document.readyState === 'loading'
                ) {
                    document.addEventListener(
                        'DOMContentLoaded',
                        initializeHomepageSliders,
                        { once: true }
                    );
                } else {
                    initializeHomepageSliders();
                }
            })();
        </script>
    @endonce
@endif
