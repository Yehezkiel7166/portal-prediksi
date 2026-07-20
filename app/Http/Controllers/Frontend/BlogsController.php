<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Blog\Models\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class BlogsController extends Controller
{
    public function __invoke(): View
    {
        $blogPosts = BlogPost::query()
            ->select([
                'id',
                'title',
                'slug',
                'excerpt',
                'image_source',
                'image_path',
                'image_url',
                'focal_point',
                'published_at',
            ])
            ->published()
            ->ordered()
            ->paginate(12);

        return view('frontend.blog.index', [
            'blogPosts' => $blogPosts,
        ]);
    }
}
