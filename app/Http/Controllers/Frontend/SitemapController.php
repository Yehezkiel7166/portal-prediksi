<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\DreamBook\Support\DreamBookRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __invoke(DreamBookRepository $dreamBook): Response
    {
        $urls = collect([
            route('home'),
            route('results.index'),
            route('tools.lottery-schedule'),
            route('tools.shio-table'),
            route('tools.bbfs.create'),
            route('tools.dream-book.index'),
            route('tools.paito'),
            route('tools.sgp-converter.create'),
        ])->merge($dreamBook->all()->map(
            fn (array $entry): string => route('tools.dream-book.show', $entry['slug']),
        ));

        $xml = view('frontend.sitemap', compact('urls'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
