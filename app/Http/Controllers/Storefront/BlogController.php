<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        if (! Schema::hasTable('blog_posts')) {
            return view('pages.blog', ['posts' => collect()]);
        }

        $posts = BlogPost::where('status', 'published')
            ->latest('published_at')
            ->take(9)
            ->get();

        return view('pages.blog', compact('posts'));
    }

    public function classic(Request $request): View
    {
        if (! Schema::hasTable('blog_posts')) {
            return view('pages.blog-2', ['posts' => LengthAwarePaginator::make([], 0, 6, page: $request->integer('page', 1))]);
        }

        $posts = BlogPost::where('status', 'published')
            ->latest('published_at')
            ->paginate(6);

        return view('pages.blog-2', compact('posts'));
    }

    public function show(?string $slug = null): View
    {
        if (! Schema::hasTable('blog_posts')) {
            abort(404);
        }

        $post = BlogPost::where('status', 'published')
            ->when($slug, fn ($query) => $query->where('slug', $slug))
            ->firstOrFail();

        $related = BlogPost::where('status', 'published')
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.blog-details', [
            'post' => $post,
            'relatedPosts' => $related,
        ]);
    }
}
