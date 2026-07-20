import Hls from 'hls.js';

const showHlsFallback = (video) => {
    const fallbackId = video.dataset.hlsFallback;
    const fallback = fallbackId
        ? document.getElementById(fallbackId)
        : null;

    video.hidden = true;
    fallback?.classList.remove('hidden');
};

const initializeHlsPlayer = (video) => {
    const source = video.dataset.hlsSource;

    if (!source) {
        showHlsFallback(video);

        return;
    }

    if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = source;

        return;
    }

    if (!Hls.isSupported()) {
        showHlsFallback(video);

        return;
    }

    const hls = new Hls({
        enableWorker: true,
        lowLatencyMode: true,
    });

    hls.loadSource(source);
    hls.attachMedia(video);

    hls.on(Hls.Events.ERROR, (_event, data) => {
        if (!data.fatal) {
            return;
        }

        hls.destroy();
        showHlsFallback(video);
    });
};

const initializeHlsPlayers = () => {
    document
        .querySelectorAll('[data-hls-player]')
        .forEach(initializeHlsPlayer);
};

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializeHlsPlayers,
        { once: true },
    );
} else {
    initializeHlsPlayers();
}
