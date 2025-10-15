@extends('admin.layouts.app')

@section('title', 'Products | Glamer Admin')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold">Product catalog</h1>
            <p class="text-secondary mb-0">Shape the collection that arrives on every shelf and screen.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="ul-btn" style="background: var(--admin-accent); border: none;">Add product</a>
    </div>

    <div class="admin-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Search by name or SKU" value="{{ $search }}">
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
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td class="text-secondary small">{{ $product->sku ?? '—' }}</td>
                        <td class="text-secondary small">{{ ucfirst($product->category ?? 'men') }}</td>
                        <td><span class="status-pill {{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
                        <td>{{ $product->stock }}</td>
                        <td>${{ number_format($product->sale_price ?? $product->price, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Move this product to trash?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
