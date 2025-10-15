@extends('admin.layouts.app')

@section('title', 'Blog | Glamer Admin')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold">Editorial lineup</h1>
            <p class="text-secondary mb-0">Keep your audience inspired with fresh fashion stories.</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="ul-btn" style="background: var(--admin-accent); border: none;">Write story</a>
    </div>

    <div class="admin-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Title or keywords" value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-dark w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td class="fw-semibold">{{ $post->title }}</td>
                        <td class="text-secondary small">{{ $post->author->name ?? '—' }}</td>
                        <td><span class="status-pill {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                        <td class="text-secondary small">{{ optional($post->published_at)->format('M d, Y') ?? 'Draft' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Move this story to trash?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary">No stories yet. Let’s publish a new trend report.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
