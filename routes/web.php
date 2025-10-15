<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\BlogController;

Route::get('/', HomeController::class)->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{category}', [ShopController::class, 'category'])
    ->name('shop.category')
    ->whereIn('category', ['men', 'women', 'kids']);
Route::get('/shop/details/{slug?}', [ShopController::class, 'show'])->name('shop.details');
Route::get('/shop/no-sidebar', [ShopController::class, 'noSidebar'])->name('shop.no-sidebar');
Route::get('/shop/right-sidebar', [ShopController::class, 'rightSidebar'])->name('shop.right-sidebar');

Route::view('/cart', 'pages.cart')->name('cart');
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::view('/wishlist', 'pages.wishlist')->name('wishlist');

Route::view('/login', 'pages.login')->name('login');
Route::view('/signup', 'pages.signup')->name('signup');

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
        Route::resource('blog', BlogPostController::class)->parameters([
            'blog' => 'blog',
        ])->except(['show']);
        Route::get('logs', LogController::class)->name('logs');
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
