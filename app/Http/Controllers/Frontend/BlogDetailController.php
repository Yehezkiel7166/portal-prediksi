<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Blog\Models\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class BlogDetailController extends Controller
{
    public function __invoke(string $slug): View
    {
        $blogPost = BlogPost::query()
            ->select([
                'id',
                'title',
                'slug',
                'excerpt',
                'content',
                'image_source',
                'image_path',
                'image_url',
                'focal_point',
                'published_at',
                'seo_title',
                'seo_description',
            ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.blog.show', [
            'blogPost' => $blogPost,
        ]);
    }
}
