<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Theme\Support\ThemePresetCatalog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApplyThemeQaPreview
{
    public function __construct(
        private readonly ThemePresetCatalog $catalog,
    ) {}

    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        $presetSlug = $request->query(
            'preset',
        );

        abort_unless(
            is_string($presetSlug)
            && $presetSlug !== '',
            404,
        );

        $preset = $this->catalog->find(
            $presetSlug,
        );

        abort_if(
            $preset === null,
            404,
        );

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Resolver reads only this server-side request attribute.
        | Normal query parameters never override production Theme directly.
        |
        */

        $request->attributes->set(
            'theme_qa_preview',
            $preset,
        );

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set(
            'X-Robots-Tag',
            'noindex, nofollow, noarchive',
        );

        $response->headers->set(
            'Cache-Control',
            'private, no-store, no-cache, must-revalidate, max-age=0',
        );

        return $response;
    }
}
