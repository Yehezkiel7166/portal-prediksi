<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\DreamBook\Support\DreamBookRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class DreamBookController extends Controller
{
    public function index(
        Request $request,
        DreamBookRepository $repository,
    ): View {
        $query = trim(
            (string) $request->query('q', '')
        );

        $category = Str::upper(
            trim((string) $request->query('category', '2D'))
        );

        if (! in_array(
            $category,
            ['2D', '3D', '4D'],
            true,
        )) {
            $category = '2D';
        }

        return view(
            'frontend.tools.dream-book-index',
            [
                'entries' => $repository->search(
                    $query,
                    max(1, $request->integer('page', 1)),
                    $category,
                ),
                'query' => $query,
                'category' => $category,
                'categories' => ['2D', '3D', '4D'],
            ],
        );
    }

    public function show(
        string $slug,
        DreamBookRepository $repository,
    ): View {
        $entry = $repository->findBySlug($slug);

        abort_if($entry === null, 404);

        return view(
            'frontend.tools.dream-book-show',
            [
                'entry' => $entry,
                'related' => $repository->related($entry),
            ],
        );
    }
}
