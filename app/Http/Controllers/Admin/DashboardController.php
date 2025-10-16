<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $orders = Order::with('user')->latest('placed_at')->take(6)->get();
        $topSellers = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as quantity_sold'), DB::raw('SUM(total_price) as revenue_generated'))
            ->with('product.brand')
            ->groupBy('product_id')
            ->orderByDesc(DB::raw('SUM(quantity)'))
            ->take(5)
            ->get();

        $trendingProducts = ProductLike::query()
            ->select('product_id', DB::raw('COUNT(*) as total_likes'))
            ->with('product.brand')
            ->groupBy('product_id')
            ->orderByDesc('total_likes')
            ->take(5)
            ->get();

        $engagement = [
            'wishlists' => WishlistItem::count(),
            'likes' => ProductLike::count(),
            'active_carts' => CartItem::distinct('user_id')->count('user_id'),
        ];

        $recentLogins = User::whereNotNull('last_login_at')
            ->orderByDesc('last_login_at')
            ->take(6)
            ->get();

        $totalRevenue = (float) Order::sum('total');
        $monthlyRevenue = (float) Order::whereBetween('placed_at', [now()->startOfMonth(), now()])->sum('total');

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
                'orders_total' => Order::count(),
                'orders_today' => Order::whereDate('placed_at', now()->toDateString())->count(),
                'revenue_total' => $totalRevenue,
                'revenue_month' => $monthlyRevenue,
            ],
            'recentProducts' => Product::latest()->take(5)->get(),
            'recentPosts' => BlogPost::latest()->take(5)->get(),
            'categoryBreakdown' => $this->categoryBreakdown(),
            'recentOrders' => $orders,
            'topSellers' => $topSellers,
            'trendingProducts' => $trendingProducts,
            'engagement' => $engagement,
            'recentLogins' => $recentLogins,
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
