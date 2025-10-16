@extends('admin.layouts.app')

@section('title', 'Admin | Users')
@section('page-title', 'Customer Directory')

@section('content')
    <section class="admin-grid admin-grid--stats mb-4">
        <div class="admin-card">
            <p class="text-secondary text-uppercase small mb-2">Total customers</p>
            <h2 class="h3 mb-1">{{ number_format($summary['totalCustomers']) }}</h2>
            <p class="text-secondary small mb-0">${{ number_format($summary['lifetimeSpend'], 2) }} lifetime spend</p>
        </div>
        <div class="admin-card">
            <p class="text-secondary text-uppercase small mb-2">New this month</p>
            <h2 class="h3 mb-1">{{ number_format($summary['newThisMonth']) }}</h2>
            <p class="text-secondary small mb-0">Since {{ now()->startOfMonth()->format('M d') }}</p>
        </div>
        <div class="admin-card">
            <p class="text-secondary text-uppercase small mb-2">Active this week</p>
            <h2 class="h3 mb-1">{{ number_format($summary['activeThisWeek']) }}</h2>
            <p class="text-secondary small mb-0">Signed in within 7 days</p>
        </div>
        <div class="admin-card">
            <p class="text-secondary text-uppercase small mb-2">Loyalty points</p>
            <h2 class="h3 mb-1">{{ number_format($summary['totalLoyaltyPoints']) }} pts</h2>
            <p class="text-secondary small mb-0">Avg {{ number_format($summary['averagePoints']) }} pts per customer</p>
        </div>
    </section>

    <section class="admin-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="userSearch" class="form-label text-secondary text-uppercase small">Search</label>
                <input type="search"
                       id="userSearch"
                       name="q"
                       value="{{ $search }}"
                       class="form-control form-control-lg"
                       placeholder="Search by name or email">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            @if($search !== '')
                <div class="col-auto">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-link text-decoration-none">Reset</a>
                </div>
            @endif
        </form>
    </section>

    <section class="admin-card">
        <h2 class="admin-section-title mb-3">Customer roster</h2>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Loyalty</th>
                        <th>Orders</th>
                        <th>Latest order</th>
                        <th>Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $totalSpent = (float) ($user->total_spent ?? 0);
                            $loyaltyPoints = \App\Support\Loyalty::pointsForAmount($totalSpent);
                            $latestOrder = $user->orders->first();
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="text-secondary small">{{ $user->email }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ number_format($loyaltyPoints) }} pts</div>
                                <div class="text-secondary small">${{ number_format($totalSpent, 2) }} lifetime spend</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $user->orders_count }} orders</div>
                                <div class="text-secondary small">{{ $user->delivered_orders_count }} delivered</div>
                            </td>
                            <td>
                                @if ($latestOrder)
                                    <div class="fw-semibold">{{ $latestOrder->reference }}</div>
                                    <div class="text-secondary small">{{ optional($latestOrder->placed_at)->format('M d, Y') }}</div>
                                    <div class="text-secondary small">${{ number_format((float) $latestOrder->total, 2) }}</div>
                                    <span class="{{ $latestOrder->status_class }} mt-1 d-inline-block">{{ $latestOrder->status_label }}</span>
                                @else
                                    <span class="text-secondary small">No orders yet</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-secondary small">Joined {{ optional($user->created_at)->format('M d, Y') }}</div>
                                <div class="text-secondary small">
                                    @if ($user->last_login_at)
                                        Last login {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Never signed in
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-secondary text-center">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </section>
@endsection
