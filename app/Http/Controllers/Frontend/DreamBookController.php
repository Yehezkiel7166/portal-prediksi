<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\DreamBook\Support\DreamBookRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class DreamBookController extends Controller
{
    public function index(Request $request, DreamBookRepository $repository): View
    {
        $query = trim((string) $request->query('q', ''));

        return view('frontend.tools.dream-book-index', [
            'entries' => $repository->search($query, max(1, $request->integer('page', 1))),
            'query' => $query,
        ]);
    }

    public function show(string $slug, DreamBookRepository $repository): View
    {
        $entry = $repository->findBySlug($slug);
        abort_if($entry === null, 404);

        return view('frontend.tools.dream-book-show', [
            'entry' => $entry,
            'related' => $repository->related($entry),
        ]);
    }
}
