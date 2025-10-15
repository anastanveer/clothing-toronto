<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = BlogPost::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%' . $request->string('search')->trim()->toString() . '%';
                $query->where('title', 'like', $term)
                    ->orWhere('excerpt', 'like', $term)
                    ->orWhere('meta_title', 'like', $term);
            })
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.blog.index', [
            'posts' => $posts,
            'search' => $request->query('search'),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['author_id'] = Auth::id();

        BlogPost::create($data);

        return redirect()
            ->route('admin.blog.index')
            ->with('status', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog): View
    {
        return view('admin.blog.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $blog->update($this->validatedData($request, $blog));

        return redirect()
            ->route('admin.blog.edit', $blog)
            ->with('status', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()
            ->route('admin.blog.index')
            ->with('status', 'Blog post moved to trash.');
    }

    protected function validatedData(Request $request, ?BlogPost $blog = null): array
    {
        $id = $blog?->id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug,' . $id],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'string'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['tags'] = $request->filled('tags')
            ? collect(explode(',', $request->input('tags')))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->values()
                ->all()
            : null;

        if (($data['status'] === 'published' || $data['status'] === 'scheduled') && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        return $data;
    }
}
