@extends('admin.layouts.app')

@section('title', 'Coupons | Glamer Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Coupons</h1>
            <p class="text-secondary mb-0">Craft limited-run offers and member surprises.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="flaticon-plus me-1"></i> New coupon
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Title</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Minimum spend</th>
                        <th scope="col">Window</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="fw-semibold text-uppercase">{{ $coupon->code }}</td>
                            <td>
                                <div class="fw-semibold">{{ $coupon->title }}</div>
                                @if($coupon->description)
                                    <small class="text-secondary d-block">{{ \Illuminate\Support\Str::limit($coupon->description, 80) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->type === 'percent')
                                    {{ rtrim(rtrim(number_format((float) $coupon->value, 2), '0'), '.') }}% off
                                    @if($coupon->max_discount)
                                        <small class="text-secondary d-block">Cap ${{ number_format((float) $coupon->max_discount, 2) }}</small>
                                    @endif
                                @else
                                    ${{ number_format((float) $coupon->value, 2) }} off
                                @endif
                            </td>
                            <td>
                                @if($coupon->min_spend > 0)
                                    ${{ number_format((float) $coupon->min_spend, 2) }}
                                @else
                                    <span class="text-secondary">None</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-secondary d-block">
                                    @if($coupon->starts_at)
                                        <span class="d-block">Starts {{ $coupon->starts_at->format('M j, Y g:i A') }}</span>
                                    @else
                                        <span class="d-block">Starts immediately</span>
                                    @endif
                                    @if($coupon->expires_at)
                                        <span>Ends {{ $coupon->expires_at->format('M j, Y g:i A') }}</span>
                                    @else
                                        <span>No end date</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                @php
                                    $active = $coupon->isCurrentlyActive();
                                @endphp
                                <span class="badge {{ $active ? 'bg-success-subtle text-success' : 'bg-secondary' }}">
                                    {{ $active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Remove this coupon?')" class="ms-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                No coupons yet. Launch one to delight your Glamer Collective.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="card-footer">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection
