@extends('user.layouts.app')

@section('title', 'My Glamer Dashboard')
@section('page-title', 'Wardrobe Command Center')

@section('content')
    @php
        $activeStage = $loyaltySummary['activeStage'] ?? null;
        $currentStage = $loyaltySummary['currentStage'] ?? null;
        $nextStage = $loyaltySummary['nextStage'] ?? null;
    @endphp

    <section class="user-hero">
        <div>
            <p class="user-hero__eyebrow">Welcome back</p>
            <h2 class="user-hero__title">Let’s curate your looks for the week</h2>
            <p class="user-hero__subtitle">Track orders, organize your saved edits, and keep your bag ready for spontaneous plans.</p>
        </div>
        <div class="user-hero__meta">
            <span class="user-pill">{{ $metrics['lifetimeOrders'] }} orders placed</span>
            <span class="user-pill user-pill--glow">${{ number_format($metrics['totalSpent'], 2) }} lifetime spend</span>
            <span class="user-pill">{{ $loyaltySummary['completedStages'] }} / 5 stages unlocked</span>
        </div>
        <div class="user-hero__score">
            <div class="user-score-chip">
                <span class="user-score-chip__icon"><i class="flaticon-heart"></i></span>
                <div>
                    <strong>{{ number_format($loyaltySummary['loyaltyPoints']) }} pts</strong>
                    <small>Loyalty balance</small>
                </div>
            </div>
            <div class="user-score-chip user-score-chip--accent">
                <span class="user-score-chip__icon"><i class="flaticon-cart"></i></span>
                <div>
                    <strong>{{ number_format($loyaltySummary['cartPoints']) }} pts</strong>
                    <small>Pending in bag</small>
                </div>
            </div>
            <div class="user-score-chip user-score-chip--outline">
                <span class="user-score-chip__icon"><i class="flaticon-price-tag"></i></span>
                <div>
                    <strong>
                        @if ($nextStage)
                            ${{ number_format($nextStage['remaining'], 0) }}
                        @else
                            All stages
                        @endif
                    </strong>
                    <small>
                        @if ($nextStage)
                            {{ $nextStage['title'] }} unlock
                        @else
                            Roadmap complete
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </section>

    <section class="user-metrics-grid">
        <article class="user-metric-card">
            <span class="icon"><i class="flaticon-price-tag"></i></span>
            <h3>${{ number_format($metrics['totalSpent'], 2) }}</h3>
            <p>Lifetime spend</p>
            <small>{{ $metrics['lifetimeOrders'] }} orders fulfilled</small>
        </article>
        <article class="user-metric-card">
            <span class="icon"><i class="flaticon-calendar"></i></span>
            <h3>{{ $metrics['processingCount'] }}</h3>
            <p>Active orders</p>
            <small>{{ $metrics['deliveredCount'] }} delivered this season</small>
        </article>
        <article class="user-metric-card">
            <span class="icon"><i class="flaticon-heart"></i></span>
            <h3>{{ $metrics['wishlistCount'] }}</h3>
            <p>Wishlist gems</p>
            <small>{{ $metrics['likesCount'] }} inspo taps</small>
        </article>
        <article class="user-metric-card">
            <span class="icon"><i class="flaticon-cart"></i></span>
            <h3>{{ $metrics['cartCount'] }}</h3>
            <p>Pieces in bag</p>
            <small>${{ number_format($metrics['cartValue'], 2) }} ready to check out</small>
        </article>
    </section>

    <section class="user-grid user-grid--two">
        <article class="user-card user-card--highlight">
            <header class="user-card__header">
                <h3>Next delivery</h3>
                @if ($nextDelivery)
                    <span class="status-pill {{ $nextDelivery->status_class }}">{{ $nextDelivery->status_label }}</span>
                @else
                    <span class="status-pill">No active shipments</span>
                @endif
            </header>
            @if ($nextDelivery)
                <div class="user-delivery">
                    <div class="user-delivery__stripe"></div>
                    <div>
                        <p class="user-delivery__date">Placed {{ optional($nextDelivery->placed_at)->format('M d, Y') }}</p>
                        <p class="user-delivery__ref">Order {{ $nextDelivery->reference }}</p>
                        <ul class="user-delivery__items">
                            @foreach ($nextDelivery->items->take(3) as $item)
                                <li>{{ $item->product?->name }} <span>×{{ $item->quantity }}</span></li>
                            @endforeach
                        </ul>
                        <p class="user-delivery__total">Total &mdash; ${{ number_format($nextDelivery->total, 2) }}</p>
                    </div>
                </div>
            @else
                <p class="text-secondary mb-0">Add something to your bag to kick off the next delivery.</p>
            @endif
        </article>

        <article class="user-card user-card--accent">
            <header class="user-card__header">
                <div>
                    <p class="user-card__eyebrow">Glamer Loyalty Circuit</p>
                    <h3>Loyalty roadmap</h3>
                </div>
                <div class="user-stage-counter">
                    <span>{{ $loyaltySummary['completedStages'] }}/5</span>
                    <small>stages cleared</small>
                </div>
            </header>

            <div class="user-loyalty-highlight">
                @php
                    $highlightStage = $activeStage ?? $currentStage ?? $nextStage;
                @endphp
                @if ($highlightStage)
                    <div>
                        <p class="user-loyalty-highlight__label">
                            @if ($activeStage && $highlightStage['key'] === $activeStage['key'])
                                Latest badge unlocked
                            @elseif ($currentStage && $highlightStage['key'] === $currentStage['key'])
                                Next milestone in play
                            @else
                                Future reward preview
                            @endif
                        </p>
                        <h4>{{ $highlightStage['title'] }}</h4>
                        <p>{{ $highlightStage['reward'] }} {{ $highlightStage['bonus'] ? 'Bonus: ' . $highlightStage['bonus'] : '' }}</p>
                    </div>
                    <div class="user-loyalty-highlight__badge">
                        <span>{{ $highlightStage['badge'] }}</span>
                    </div>
                @else
                    <div>
                        <p class="user-loyalty-highlight__label">Roadmap complete</p>
                        <h4>Legend status</h4>
                        <p>Every stage has been conquered. We will reach out with bespoke experiences for you.</p>
                    </div>
                @endif
            </div>

            <ol class="user-loyalty-roadmap">
                @foreach ($loyaltyStages as $stage)
                    <li class="user-loyalty-step is-{{ $stage['state'] }}">
                        <div class="user-loyalty-step__badge">{{ $stage['badge'] }}</div>
                        <div class="user-loyalty-step__body">
                            <div class="user-loyalty-step__top">
                                <h4>{{ $stage['title'] }}</h4>
                                <span class="user-loyalty-step__state">
                                    @if ($stage['state'] === 'achieved')
                                        Badge minted
                                    @elseif ($stage['state'] === 'current')
                                        ${{ number_format($stage['remaining'], 0) }} to go
                                    @else
                                        Locked stage
                                    @endif
                                </span>
                            </div>
                            <p class="user-loyalty-step__headline">{{ $stage['headline'] }}</p>
                            <ul class="user-loyalty-step__perks">
                                <li>{{ $stage['reward'] }}</li>
                                <li>{{ $stage['bonus'] }}</li>
                            </ul>
                            <div class="user-progress user-progress--roadmap" aria-hidden="true">
                                <div class="user-progress__ghost" style="width: {{ $stage['ghost_progress'] }}%"></div>
                                <div class="user-progress__bar" style="width: {{ $stage['progress'] }}%"></div>
                            </div>
                            <div class="user-loyalty-step__footer">
                                <span>${{ number_format($stage['threshold'], 0) }} spend goal</span>
                                <span>
                                    @if ($stage['state'] === 'achieved')
                                        Enjoy your perks
                                    @elseif ($stage['state'] === 'current')
                                        Keep shopping to unlock
                                    @else
                                        Clear prior stages first
                                    @endif
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>

            @if($flashReward)
                <div class="user-flash-callout">
                    <strong>Flash reward:</strong> {{ $flashReward }}
                </div>
            @endif

            <a href="{{ route('shop') }}" class="user-card__cta">Plan your next unlock</a>
        </article>
    </section>

    <section class="user-card">
        <header class="user-card__header">
            <h3>Recent orders</h3>
            <a href="{{ route('shop') }}" class="user-link">Shop again</a>
        </header>
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <span class="user-table__title">{{ $order->reference }}</span>
                                <small>{{ optional($order->shipping_address)['city'] ?? 'Toronto' }}</small>
                            </td>
                            <td>{{ optional($order->placed_at)->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $summary = $order->items->map(fn($item) => $item->product?->name)->filter()->take(2);
                                @endphp
                                <span>{{ $summary->join(', ') }}@if ($order->items->count() > 2) &hellip; @endif</span>
                            </td>
                            <td><span class="status-pill {{ $order->status_class }}">{{ $order->status_label }}</span></td>
                            <td class="text-end">${{ number_format($order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary">No orders yet. Your first look is waiting.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="user-grid user-grid--three">
        <article class="user-card">
            <header class="user-card__header">
                <h3>Wishlist</h3>
                <a href="{{ route('wishlist') }}" class="user-link">View all</a>
            </header>
            <ul class="user-product-grid">
                @forelse ($wishlistItems as $item)
                    @php
                        $product = $item->product;
                    @endphp
                    <li>
                        <a href="{{ route('shop.details', ['slug' => $product?->slug ?? $product?->id]) }}" class="user-product">
                            <img src="{{ $product?->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-1.jpg') }}" alt="{{ $product?->name }}">
                            <div>
                                <p class="user-product__title">{{ $product?->name }}</p>
                                <p class="user-product__meta">{{ $product?->brand?->name }}</p>
                                <p class="user-product__price">${{ number_format($product?->sale_price ?? $product?->price ?? 0, 2) }}</p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="text-secondary">No saved products yet.</li>
                @endforelse
            </ul>
        </article>

        <article class="user-card">
            <header class="user-card__header">
                <h3>Bag preview</h3>
                <a href="{{ route('cart') }}" class="user-link">Checkout</a>
            </header>
            <ul class="user-product-list">
                @forelse ($cartItems->take(4) as $item)
                    <li>
                        @php
                            $product = $item->product;
                            $unitPrice = $item->unit_price;
                        @endphp
                        <div>
                            <p class="user-product__title">{{ $product?->name }}</p>
                            <small>{{ $item->quantity }} × ${{ number_format($unitPrice, 2) }}</small>
                        </div>
                        <span class="user-product__price">${{ number_format($item->line_total, 2) }}</span>
                    </li>
                @empty
                    <li class="text-secondary">Your bag is clean. Add a statement piece.</li>
                @endforelse
            </ul>
            <div class="user-card__footer">
                <span>Total</span>
                <strong>${{ number_format($metrics['cartValue'], 2) }}</strong>
            </div>
        </article>

        <article class="user-card">
            <header class="user-card__header">
                <h3>Recently liked</h3>
                <a href="{{ route('shop') }}" class="user-link">Keep exploring</a>
            </header>
            <ul class="user-product-grid">
                @forelse ($likes as $like)
                    @php
                        $product = $like->product;
                    @endphp
                    <li>
                        <a href="{{ route('shop.details', ['slug' => $product?->slug ?? $product?->id]) }}" class="user-product user-product--compact">
                            <img src="{{ $product?->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-2.jpg') }}" alt="{{ $product?->name }}">
                            <div>
                                <p class="user-product__title">{{ $product?->name }}</p>
                                <p class="user-product__meta">Liked {{ optional($like->liked_at)->diffForHumans() }}</p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="text-secondary">Tap the heart on products you love.</li>
                @endforelse
            </ul>
        </article>
    </section>
@endsection
