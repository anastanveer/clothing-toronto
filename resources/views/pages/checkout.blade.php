@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<x-layout.page>
    <x-page.header
        title="Checkout"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home'), 'icon' => 'flaticon-home'],
            ['label' => 'Checkout', 'is_current' => true],
        ]"
    />

        <div class="ul-checkout-shell">
            <div class="ul-checkout-main">
                @if(session('coupon_success'))
                    <div class="alert alert-success mb-3" role="status">{{ session('coupon_success') }}</div>
                @endif

                @if(session('coupon_status'))
                    <div class="alert alert-info mb-3" role="status">{{ session('coupon_status') }}</div>
                @endif

                @if(!empty($couponNotice))
                    <div class="alert alert-warning mb-3" role="status">{{ $couponNotice }}</div>
                @endif

                @if(!empty($loyaltyBanner))
                    <div class="alert alert-info mb-4" role="status">{{ $loyaltyBanner }}</div>
                @endif

                <section class="ul-checkout-panel">
                    <header class="ul-checkout-panel__header">
                        <span class="ul-checkout-panel__step">01</span>
                        <div>
                            <h3>Contact details</h3>
                            <p>We’ll keep this information on file for a speedier next checkout.</p>
                        </div>
                    </header>
                    <div class="ul-checkout-panel__body">
                        <div class="ul-checkout-form-grid">
                            <label class="ul-checkout-field">
                                <span>First name</span>
                                <input type="text" placeholder="First name" value="{{ old('first_name', Str::of($customer->name)->before(' ')) }}">
                            </label>
                            <label class="ul-checkout-field">
                                <span>Last name</span>
                                <input type="text" placeholder="Last name" value="{{ old('last_name', Str::of($customer->name)->after(' ')) }}">
                            </label>
                            <label class="ul-checkout-field">
                                <span>Email address</span>
                                <input type="email" placeholder="you@example.com" value="{{ old('email', $customer->email) }}">
                            </label>
                            <label class="ul-checkout-field">
                                <span>Phone</span>
                                <input type="tel" placeholder="+1 202 555 0147">
                            </label>
                        </div>
                    </div>
                </section>

                <section class="ul-checkout-panel">
                    <header class="ul-checkout-panel__header">
                        <span class="ul-checkout-panel__step">02</span>
                        <div>
                            <h3>Delivery address</h3>
                            <p>Tailor your shipment details for this drop.</p>
                        </div>
                    </header>
                    <div class="ul-checkout-panel__body">
                        <div class="ul-checkout-form-grid">
                            <label class="ul-checkout-field ul-checkout-field--full">
                                <span>Street address</span>
                                <input type="text" placeholder="1837 E Homer M Adams Pkwy">
                            </label>
                            <label class="ul-checkout-field">
                                <span>City</span>
                                <input type="text" placeholder="Chicago">
                            </label>
                            <label class="ul-checkout-field">
                                <span>State / region</span>
                                <input type="text" placeholder="Illinois">
                            </label>
                            <label class="ul-checkout-field">
                                <span>Postal code</span>
                                <input type="text" placeholder="60616">
                            </label>
                            <label class="ul-checkout-field">
                                <span>Country</span>
                                <select>
                                    <option>United States</option>
                                    <option>Canada</option>
                                    <option>United Kingdom</option>
                                    <option>United Arab Emirates</option>
                                </select>
                            </label>
                            <label class="ul-checkout-field ul-checkout-field--full">
                                <span>Delivery instructions (optional)</span>
                                <textarea placeholder="Tell our couriers the best way to reach you."></textarea>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="ul-checkout-panel">
                    <header class="ul-checkout-panel__header">
                        <span class="ul-checkout-panel__step">03</span>
                        <div>
                            <h3>Payment method</h3>
                            <p>Select a secure gateway that suits your rhythm.</p>
                        </div>
                    </header>
                    <div class="ul-checkout-panel__body">
                        <input type="hidden" name="payment_gateway" value="apple-pay" id="checkoutGatewayInput">
                        <div class="ul-checkout-gateway-list" role="tablist">
                            <button type="button" class="ul-checkout-gateway-chip is-active" data-gateway="apple-pay" data-label="Apple Pay">
                                <span class="ul-checkout-gateway-chip__title">Apple Pay</span>
                                <span class="ul-checkout-gateway-chip__hint">One-tap checkout</span>
                            </button>
                            <button type="button" class="ul-checkout-gateway-chip" data-gateway="google-pay" data-label="Google Pay">
                                <span class="ul-checkout-gateway-chip__title">Google Pay</span>
                                <span class="ul-checkout-gateway-chip__hint">Android &amp; Chrome</span>
                            </button>
                            <button type="button" class="ul-checkout-gateway-chip" data-gateway="paypal" data-label="PayPal">
                                <span class="ul-checkout-gateway-chip__title">PayPal</span>
                                <span class="ul-checkout-gateway-chip__hint">Linked wallet</span>
                            </button>
                            <button type="button" class="ul-checkout-gateway-chip" data-gateway="card" data-label="Card • Stripe">
                                <span class="ul-checkout-gateway-chip__title">Visa · MasterCard</span>
                                <span class="ul-checkout-gateway-chip__hint">Powered by Stripe</span>
                            </button>
                        </div>

                        <div class="ul-checkout-gateway-detail">
                            <div class="ul-checkout-gateway-detail__pane is-active" data-gateway="apple-pay">
                                <h4>Apple Pay</h4>
                                <p>Confirm with Face ID, Touch ID, or passcode. Your shipping, billing, and contact details stay synced with your Apple device.</p>
                                <ul class="ul-checkout-wallet-list">
                                    <li>Instant confirmation on iPhone, iPad, Mac, and Apple Watch.</li>
                                    <li>Eligible for Glamer Assurance and instant refunds.</li>
                                    <li>No need to retype card details—just authenticate.</li>
                                </ul>
                            </div>

                            <div class="ul-checkout-gateway-detail__pane" data-gateway="google-pay">
                                <h4>Google Pay</h4>
                                <p>Perfect for Android and Chrome shoppers. Choose your saved card or balance, then confirm with fingerprint or device security.</p>
                                <ul class="ul-checkout-wallet-list">
                                    <li>Supports saved cards, loyalty IDs, and shipping addresses.</li>
                                    <li>Encrypted tokens—your real card number stays hidden.</li>
                                    <li>Works seamlessly on desktop Chrome and Android devices.</li>
                                </ul>
                            </div>

                            <div class="ul-checkout-gateway-detail__pane" data-gateway="paypal">
                                <h4>PayPal</h4>
                                <p>Sign in to your PayPal account to use balance, linked cards, or PayPal Credit. Eligible orders unlock purchase protection.</p>
                                <div class="ul-checkout-paypal-card">
                                    <span class="ul-checkout-paypal-card__logo">PayPal</span>
                                    <p class="mb-0">After selecting PayPal, you’ll be redirected to approve the payment, then returned here for confirmation.</p>
                                    <ul class="ul-checkout-wallet-list">
                                        <li>Use balance, bank, or stored cards instantly.</li>
                                        <li>Eligible for Glamer loyalty points on completion.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="ul-checkout-gateway-detail__pane" data-gateway="card">
                                <h4>Card payment</h4>
                                <p>Enter your card details below. Stripe handles the secure processing for Visa, MasterCard, American Express, and more.</p>
                                <div class="ul-payment-card">
                                    <div class="ul-payment-card__glow"></div>
                                    <div class="ul-payment-card__chip"></div>
                                    <label class="ul-payment-card__field">
                                        <span>Card number</span>
                                        <input type="text" placeholder="4242 4242 4242 4242" inputmode="numeric" maxlength="19">
                                    </label>
                                    <div class="ul-payment-card__row">
                                        <label class="ul-payment-card__field">
                                            <span>Expiry</span>
                                            <input type="text" placeholder="MM / YY" inputmode="numeric" maxlength="7">
                                        </label>
                                        <label class="ul-payment-card__field">
                                            <span>CVV</span>
                                            <input type="text" placeholder="123" inputmode="numeric" maxlength="4">
                                        </label>
                                    </div>
                                    <label class="ul-payment-card__field ul-payment-card__field--name">
                                        <span>Card holder</span>
                                        <input type="text" placeholder="SARA KHALID">
                                    </label>
                                </div>
                                <p class="ul-payment-card__note">Stripe encrypts your details end-to-end. We never store your full card number.</p>
                            </div>
                        </div>

                        <div class="ul-checkout-payment-badges">
                            <span class="badge bg-light text-uppercase text-muted">SSL encrypted</span>
                            <span class="badge bg-light text-uppercase text-muted">3D secure</span>
                            <span class="badge bg-light text-uppercase text-muted">Shopper protection</span>
                        </div>
                        <button class="ul-btn ul-btn--checkout" type="button">
                            <span class="ul-checkout-cta__text">
                                <span class="ul-checkout-cta__primary">Confirm &amp; pay</span>
                                <span class="ul-checkout-cta__secondary" data-gateway-label>with Apple Pay</span>
                            </span>
                            <span class="ul-checkout-cta__amount">{{ $summary['total_formatted'] }}</span>
                        </button>
                    </div>
                </section>
            </div>

            <aside class="ul-checkout-summary">
                <div class="ul-checkout-summary__card">
                    <header class="ul-checkout-summary__header">
                        <h4>Your bag</h4>
                        <span>{{ $lines->count() }} item{{ $lines->count() === 1 ? '' : 's' }}</span>
                    </header>
                    <div class="ul-checkout-summary__items">
                        @foreach($lines as $line)
                            @php
                                $product = $line['product'];
                                $image = $product?->featured_image ? asset($product->featured_image) : asset('assets/img/product-img-sm-6.jpg');
                            @endphp
                            <div class="ul-checkout-item">
                                <div class="ul-checkout-item__media">
                                    <img src="{{ $image }}" alt="{{ $product?->name ?? 'Product' }}">
                                    <span class="ul-checkout-item__quantity">×{{ $line['model']->quantity }}</span>
                                </div>
                                <div class="ul-checkout-item__body">
                                    <div class="ul-checkout-item__title">{{ $product?->name ?? 'Product unavailable' }}</div>
                                    @if($product?->brand)
                                        <span class="ul-checkout-item__brand">{{ $product->brand->name }}</span>
                                    @endif
                                </div>
                                <span class="ul-checkout-item__price">{{ $line['line_total_formatted'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="ul-checkout-summary__totals">
                        <div class="ul-checkout-summary__row">
                            <span>Subtotal</span>
                            <span>{{ $summary['subtotal_formatted'] }}</span>
                        </div>
                        <div class="ul-checkout-summary__row">
                            <span>Shipping</span>
                            <span>{{ $summary['shipping_formatted'] }}</span>
                        </div>
                        @if($summary['coupon_discount'] > 0)
                            <div class="ul-checkout-summary__row is-savings">
                                <span>Coupon savings</span>
                                <span>{{ $summary['coupon_discount_formatted'] }}</span>
                            </div>
                        @endif
                        @if($summary['loyalty_discount'] > 0)
                            <div class="ul-checkout-summary__row is-savings">
                                <span>Radiant Insider reward</span>
                                <span>{{ $summary['loyalty_discount_formatted'] }}</span>
                            </div>
                        @endif
                        <div class="ul-checkout-summary__total">
                            <span>Total due</span>
                            <span>{{ $summary['total_formatted'] }}</span>
                        </div>
                    </div>

                    <div class="ul-checkout-summary__coupon">
                        <span class="ul-checkout-summary__coupon-title">Redeem a perk</span>
                        @if($appliedCoupon)
                            <div class="ul-cart-applied-coupon">
                                <div>
                                    <span class="ul-cart-applied-coupon__eyebrow">Applied</span>
                                    <strong class="ul-cart-applied-coupon__code">{{ $appliedCoupon->code }}</strong>
                                    <span class="ul-cart-applied-coupon__title">{{ $appliedCoupon->title }}</span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ul-cart-applied-coupon__remove">Remove</button>
                                </form>
                            </div>
                        @endif
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="ul-cart-coupon-code-form">
                            @csrf
                            <input
                                type="text"
                                name="code"
                                placeholder="Gift or promo code"
                                value="{{ old('code', $couponCode) }}"
                                autocomplete="off"
                            >
                            <button type="submit" class="ul-btn">Apply</button>
                        </form>
                        @if($couponMessage && ! $appliedCoupon)
                            <p class="small text-secondary mt-2 mb-0">{{ $couponMessage }}</p>
                        @endif
                    </div>
                </div>

                <div class="ul-checkout-summary__card ul-checkout-summary__card--assurance">
                    <div class="ul-checkout-assurance">
                        <i class="flaticon-checked"></i>
                        <div>
                            <strong>Glamer Assurance</strong>
                            <p class="mb-0">Free returns within 30 days and concierge styling support on every order.</p>
                        </div>
                    </div>
                    <div class="ul-checkout-assurance">
                        <i class="flaticon-sparkle"></i>
                        <div>
                            <strong>Loyalty boost</strong>
                            <p class="mb-0">Earn {{ number_format(\App\Support\Loyalty::pointsForAmount($summary['total'])) }} pts towards your next badge.</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
</x-layout.page>
@endsection
