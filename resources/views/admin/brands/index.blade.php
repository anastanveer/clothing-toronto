@extends('admin.layouts.app')

@section('title', 'Brands | Glamer Admin')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold">Brand library</h1>
            <p class="text-secondary mb-0">Curate the houses behind every collection and control what goes live.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="ul-btn" style="background: var(--admin-accent); border: none;">Add brand</a>
    </div>

    <div class="admin-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Search by name or tagline" value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All</option>
                    <option value="published" @selected(($filters['status'] ?? '') === 'published')>Published</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Hidden</option>
                </select>
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
                    <th>Name</th>
                    <th>Status</th>
                    <th>Products</th>
                    <th>Updated</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $brand->name }}</div>
                            @if($brand->tagline)
                                <div class="text-secondary small">{{ $brand->tagline }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="status-pill {{ $brand->is_published ? 'published' : 'draft' }}">{{ $brand->is_published ? 'Published' : 'Hidden' }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.brands.show', $brand) }}" class="text-decoration-none">
                                {{ $brand->products_count }}
                            </a>
                        </td>
                        <td class="text-secondary small">{{ $brand->updated_at?->diffForHumans() ?? '—' }}</td>
                        <td class="text-end d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.brands.show', $brand) }}" class="btn btn-sm btn-outline-secondary">Overview</a>
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.brands.toggle-status', $brand) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-dark">{{ $brand->is_published ? 'Unpublish' : 'Publish' }}</button>
                            </form>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this brand? Products must be reassigned first.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary">No brands yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $brands->links() }}
        </div>
    </div>
@endsection
