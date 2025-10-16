@extends('admin.layouts.app')

@section('title', $brand->name . ' | Glamer Admin')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold">{{ $brand->name }}</h1>
            @if($brand->tagline)
                <p class="text-secondary mb-0">{{ $brand->tagline }}</p>
            @endif
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.brands.toggle-status', $brand) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button class="btn btn-outline-dark">{{ $brand->is_published ? 'Unpublish' : 'Publish' }}</button>
            </form>
            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-outline-primary">Edit brand</a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Back to brands</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="status-pill {{ $brand->is_published ? 'published' : 'draft' }}">{{ $brand->is_published ? 'Published' : 'Hidden' }}</span>
                    <span class="text-secondary small">Updated {{ $brand->updated_at?->diffForHumans() ?? 'never' }}</span>
                </div>
                @if($brand->hero_image)
                    <img src="{{ asset($brand->hero_image) }}" alt="{{ $brand->name }}" class="img-fluid rounded mb-3" style="max-height: 260px; object-fit: cover;">
                @endif
                @if($brand->summary)
                    <p class="mb-3">{{ $brand->summary }}</p>
                @endif
                @if($brand->description)
                    <div class="text-secondary small">{!! nl2br(e($brand->description)) !!}</div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <h2 class="admin-section-title">Quick stats</h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total products</span>
                        <strong>{{ $stats['total'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Live now</span>
                        <strong>{{ $stats['published'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Drafts</span>
                        <strong>{{ $stats['draft'] }}</strong>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.products.create', ['brand_id' => $brand->id]) }}" class="ul-btn" style="background: var(--admin-accent); border: none;">Add product to {{ $brand->name }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="admin-section-title mb-0">Products from {{ $brand->name }}</h2>
            <span class="text-secondary small">{{ $products->total() }} entries</span>
        </div>
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td class="text-secondary small">{{ ucfirst($product->category) }}</td>
                        <td><span class="status-pill {{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
                        <td>${{ number_format($product->sale_price ?? $product->price, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary">No products yet for this brand.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
