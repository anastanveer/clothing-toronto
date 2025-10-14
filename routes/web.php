<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('home');

Route::view('/shop', 'pages.shop')->name('shop');
Route::view('/shop/details', 'pages.shop-details')->name('shop.details');
Route::view('/shop/no-sidebar', 'pages.shop-no-sidebar')->name('shop.no-sidebar');
Route::view('/shop/right-sidebar', 'pages.shop-right-sidebar')->name('shop.right-sidebar');

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

Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/blog/details', 'pages.blog-details')->name('blog.details');
Route::view('/blog/classic', 'pages.blog-2')->name('blog.two');

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
