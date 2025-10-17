<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Storefront\AuthController as StorefrontAuthController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\BlogController;
use App\Http\Controllers\Storefront\UserDashboardController;
use App\Http\Controllers\Storefront\WishlistController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\HeaderMetricsController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\NotificationCenterController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;

Route::get('/', HomeController::class)->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{category}', [ShopController::class, 'category'])
    ->name('shop.category')
    ->whereIn('category', ['men', 'women', 'kids']);
Route::get('/shop/details/{slug?}', [ShopController::class, 'show'])->name('shop.details');
Route::get('/shop/no-sidebar', [ShopController::class, 'noSidebar'])->name('shop.no-sidebar');
Route::get('/shop/right-sidebar', [ShopController::class, 'rightSidebar'])->name('shop.right-sidebar');
Route::get('/shop/brand/{slug}', [ShopController::class, 'brand'])->name('shop.brand');
Route::get('/header/metrics', HeaderMetricsController::class)->name('header.metrics');
Route::get('/header/inbox', NotificationCenterController::class)->name('header.inbox');

Route::middleware('guest')->group(function () {
    Route::get('/login', [StorefrontAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StorefrontAuthController::class, 'login'])->name('login.submit');

    Route::get('/signup', [StorefrontAuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [StorefrontAuthController::class, 'register'])->name('signup.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [StorefrontAuthController::class, 'logout'])->name('logout');
    Route::get('/account/dashboard', [UserDashboardController::class, 'index'])->name('account.dashboard');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.items.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.items.destroy');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlistItem}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
});

Route::view('/our-store', 'pages.our-store')->name('our-store');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/about', 'pages.about')->name('about');
Route::view('/reviews', 'pages.reviews')->name('reviews');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/details/{slug?}', [BlogController::class, 'show'])->name('blog.details');
Route::get('/blog/classic', [BlogController::class, 'classic'])->name('blog.two');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('products', ProductController::class)->parameters([
            'products' => 'product',
        ])->except(['show']);

        Route::middleware('admin.full')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::resource('brands', BrandController::class)
                ->parameters(['brands' => 'brand']);
            Route::patch('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])
                ->name('brands.toggle-status');

            Route::resource('coupons', AdminCouponController::class)
                ->parameters(['coupons' => 'coupon'])
                ->except(['show']);

            Route::resource('announcements', AdminAnnouncementController::class)
                ->parameters(['announcements' => 'announcement'])
                ->except(['show']);

            Route::resource('blog', BlogPostController::class)->parameters([
                'blog' => 'blog',
            ])->except(['show']);

            Route::get('logs', LogController::class)->name('logs');
        });
    });
});

// Legacy .html support
foreach ([
    'index' => '/',
    'shop' => '/shop',
    'shop-details' => '/shop/details',
    'shop-no-sidebar' => '/shop/no-sidebar',
    'shop-right-sidebar' => '/shop/right-sidebar',
    'cart' => '/cart',
    'checkout' => '/checkout',
    'wishlist' => '/wishlist',
    'login' => '/login',
    'signup' => '/signup',
    'our-store' => '/our-store',
    'contact' => '/contact',
    'faq' => '/faq',
    'about' => '/about',
    'reviews' => '/reviews',
    'blog' => '/blog',
    'blog-details' => '/blog/details',
    'blog-2' => '/blog/classic',
] as $legacy => $route) {
    Route::redirect("/{$legacy}.html", $route);
}
