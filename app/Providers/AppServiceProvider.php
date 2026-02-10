<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $catalog = (array) config('catalog', []);
        View::share('catalogStore', $catalog['store'] ?? []);
        View::share('catalogBrands', $catalog['brands'] ?? []);
        View::share('catalogCategories', $catalog['categories'] ?? []);
        View::share('catalogDefaultBrand', $catalog['default_brand'] ?? null);
    }
}
