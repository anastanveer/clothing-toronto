<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'metrics' => [
                'total_products' => Product::count(),
                'published_products' => Product::where('status', 'published')->count(),
                'draft_products' => Product::where('status', 'draft')->count(),
                'archived_products' => Product::where('status', 'archived')->count(),
                'trashed_products' => Product::onlyTrashed()->count(),
                'total_posts' => BlogPost::count(),
                'published_posts' => BlogPost::where('status', 'published')->count(),
                'admins' => User::where('is_admin', true)->count(),
            ],
            'recentProducts' => Product::latest()->take(5)->get(),
            'recentPosts' => BlogPost::latest()->take(5)->get(),
            'categoryBreakdown' => $this->categoryBreakdown(),
        ]);
    }

    protected function categoryBreakdown(): array
    {
        $totals = [];

        foreach (Product::CATEGORIES as $category) {
            $totals[$category] = [
                'label' => ucfirst($category),
                'total' => Product::where('category', $category)->count(),
                'published' => Product::where('category', $category)->where('status', 'published')->count(),
                'draft' => Product::where('category', $category)->where('status', 'draft')->count(),
            ];
        }

        return $totals;
    }
}
