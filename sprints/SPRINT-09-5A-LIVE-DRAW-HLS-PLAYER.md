# Sprint 09.5A — Live Draw HLS Player

## Status

Completed.

## Objective

Replace the public Live Draw HLS placeholder with a responsive video
player supporting native HLS playback and hls.js.

## Playback Strategy

The frontend uses the following playback order:

1. Use native browser HLS support when available.
2. Use hls.js when Media Source Extensions are supported.
3. Display a controlled fallback when playback is unsupported or a fatal
   HLS error occurs.

## Rendering Conditions

The player is rendered only when:

- The Live Draw status is `live`.
- The stream type is `hls`.
- The source URL is available.

Offline HLS source URLs are not rendered.

A live HLS record without a source URL continues to use the unavailable
state.

## Frontend Assets

This sprint adds:

- The `hls.js` frontend dependency.
- HLS initialization in `resources/js/app.js`.
- Responsive HTML5 video markup.
- Native HLS browser detection.
- Fatal playback error handling.
- Unsupported-browser fallback UI.

## Security Boundaries

- No arbitrary JavaScript supplied by administrators is executed.
- No raw administrator-supplied HTML is rendered.
- HLS playback remains separate from iframe embed handling.
- The existing Live Draw source configuration is reused.
- Non-live HLS source URLs remain hidden from public output.

## Existing Behavior Preserved

The sprint does not change:

- Database structure.
- Live Draw admin resources.
- Live Draw status automation.
- YouTube iframe handling.
- Vimeo iframe handling.
- External URL streams.
- Latest Result resolution.
- Latest Result public UI.

## Shared Hosting Build Compatibility

The production asset build was executed with one Rayon worker thread
because the shared hosting environment restricts thread creation.

The build environment used:

- `RAYON_NUM_THREADS=1`
- `TOKIO_WORKER_THREADS=1`
- `UV_THREADPOOL_SIZE=1`

This only affects the server-side asset compilation process and does not
change browser runtime behavior.

## Regression Coverage

Feature tests verify that:

- A live HLS source renders the video player.
- The HLS source is attached through a data attribute.
- A unique fallback target is rendered.
- Offline HLS source URLs are not exposed.
- Missing HLS source URLs do not render a player.
- The obsolete HLS development placeholder is removed.

## Verification

The sprint was verified with:

- PHP syntax checking.
- Blade compilation.
- Vite production asset build.
- Live Draw frontend module tests.
- Full automated test suite.
- Git whitespace validation.
