@extends('admin.layouts.app')

@section('title', 'Dashboard | Glamer Admin')
@section('page-title', 'Control Hub')

@section('content')
    <header class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Control Hub</h1>
            <p class="text-secondary mb-0">Track performance, publish new drops, and keep your stories polished.</p>
        </div>
        <span class="badge-soft">Fashion HQ</span>
    </header>

    @php
        $catalogTotal = max(1, $metrics['total_products']);
    @endphp

    <section class="admin-grid admin-grid--stats mb-4">
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Catalog</p>
            <h2 class="display-6 fw-bold">{{ $metrics['total_products'] }}</h2>
            <span class="text-secondary small">products in rotation</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Live</p>
            <h2 class="display-6 fw-bold text-success">{{ $metrics['published_products'] }}</h2>
            <span class="text-secondary small">ready for purchase</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Drafts</p>
            <h2 class="display-6 fw-bold text-primary">{{ $metrics['draft_products'] }}</h2>
            <span class="text-secondary small">awaiting review</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Archived</p>
            <h2 class="display-6 fw-bold text-warning">{{ $metrics['archived_products'] }}</h2>
            <span class="text-secondary small">seasonal rest</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Trash</p>
            <h2 class="display-6 fw-bold text-danger">{{ $metrics['trashed_products'] }}</h2>
            <span class="text-secondary small">awaiting purge</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Editorial</p>
            <h2 class="display-6 fw-bold">{{ $metrics['published_posts'] }}</h2>
            <span class="text-secondary small">of {{ $metrics['total_posts'] }} live</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Orders today</p>
            <h2 class="display-6 fw-bold text-info">{{ $metrics['orders_today'] }}</h2>
            <span class="text-secondary small">placed in the last 24h</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">All orders</p>
            <h2 class="display-6 fw-bold">{{ $metrics['orders_total'] }}</h2>
            <span class="text-secondary small">lifetime order count</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Revenue this month</p>
            <h2 class="display-6 fw-bold text-success">${{ number_format($metrics['revenue_month'], 2) }}</h2>
            <span class="text-secondary small">current calendar month</span>
        </div>
        <div class="admin-card">
            <p class="text-uppercase text-secondary small mb-2">Lifetime revenue</p>
            <h2 class="display-6 fw-bold text-dark">${{ number_format($metrics['revenue_total'], 2) }}</h2>
            <span class="text-secondary small">across all channels</span>
        </div>
    </section>

    <section class="admin-grid admin-grid--two mb-4">
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Category breakdown</h2>
            <ul class="list-unstyled mb-0">
                @foreach($categoryBreakdown as $category => $data)
                    @php
                        $share = $catalogTotal ? round(($data['total'] / $catalogTotal) * 100) : 0;
                        $publishedShare = $data['total'] ? round(($data['published'] / max(1, $data['total'])) * 100) : 0;
                    @endphp
                    <li class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $data['label'] }}</span>
                            <span class="text-secondary small">{{ $data['total'] }} items</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-dark" style="width: {{ $share }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-secondary small mt-1">
                            <span>Published {{ $data['published'] }} ({{ $publishedShare }}%)</span>
                            <span>Draft {{ $data['draft'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Team & editorial</h2>
            <div class="admin-grid admin-grid--metrics">
                <div class="border rounded-4 px-3 py-4 h-100 text-center">
                    <p class="text-uppercase text-secondary small mb-1">Admin team</p>
                    <h3 class="h2 fw-bold mb-0">{{ $metrics['admins'] }}</h3>
                    <span class="text-secondary small">trusted backstage crew</span>
                </div>
                <div class="border rounded-4 px-3 py-4 h-100 text-center">
                    <p class="text-uppercase text-secondary small mb-1">Editorial queue</p>
                    <h3 class="h2 fw-bold mb-0">{{ $metrics['total_posts'] - $metrics['published_posts'] }}</h3>
                    <span class="text-secondary small">stories in progress</span>
                </div>
                <div class="border rounded-4 px-3 py-4">
                    <p class="text-uppercase text-secondary small mb-1">System health</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Latest log snapshot</span>
                        <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-dark">View logs</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-grid admin-grid--two mb-4">
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Customer engagement</h2>
            <ul class="list-unstyled mb-4">
                <li class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Wishlisted looks</span>
                    <span class="text-secondary">{{ $engagement['wishlists'] }}</span>
                </li>
                <li class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">In-bag items</span>
                    <span class="text-secondary">{{ $engagement['active_carts'] }} active carts</span>
                </li>
                <li class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Product likes</span>
                    <span class="text-secondary">{{ $engagement['likes'] }}</span>
                </li>
            </ul>
            <h3 class="h6 text-uppercase text-secondary mb-3">Trending favourites</h3>
            <ul class="list-unstyled mb-0">
                @forelse($trendingProducts as $like)
                    <li class="mb-2 d-flex justify-content-between">
                        <span>{{ $like->product?->name ?? 'Product removed' }}</span>
                        <span class="text-secondary small">{{ $like->total_likes }} likes</span>
                    </li>
                @empty
                    <li class="text-secondary small">No engagement yet. Encourage shoppers to tap the heart.</li>
                @endforelse
            </ul>
        </div>
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Latest customer logins</h2>
            <ul class="list-unstyled mb-0">
                @forelse($recentLogins as $login)
                    <li class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">{{ $login->name }}</span>
                            <span class="text-secondary small">{{ optional($login->last_login_at)->diffForHumans() }}</span>
                        </div>
                        <span class="text-secondary small">{{ $login->email }}</span>
                    </li>
                @empty
                    <li class="text-secondary">No sign-ins recorded yet.</li>
                @endforelse
            </ul>
        </div>
    </section>

    <section class="admin-grid admin-grid--two mb-4">
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Recent orders</h2>
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->reference }}</td>
                            <td class="text-secondary small">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td><span class="{{ $order->status_class }}">{{ $order->status_label }}</span></td>
                            <td class="text-end">${{ number_format($order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-secondary text-center">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Top sellers</h2>
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Units</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSellers as $line)
                        <tr>
                            <td class="fw-semibold">{{ $line->product?->name ?? 'Product removed' }}</td>
                            <td>{{ $line->quantity_sold }}</td>
                            <td class="text-end">${{ number_format($line->revenue_generated, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-secondary text-center">No sales yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-grid admin-grid--two">
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Fresh products</h2>
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th class="text-end">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProducts as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td><span class="status-pill {{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
                            <td>${{ number_format($product->sale_price ?? $product->price, 2) }}</td>
                            <td class="text-end text-secondary small">{{ $product->updated_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary">No products yet. Ready to launch?</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="admin-card h-100">
            <h2 class="admin-section-title">Latest editorials</h2>
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Published</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPosts as $post)
                        <tr>
                            <td class="fw-semibold">{{ $post->title }}</td>
                            <td><span class="status-pill {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                            <td class="text-secondary small">{{ optional($post->published_at)->format('M d, Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary">No blog stories yet. Time to inspire.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
