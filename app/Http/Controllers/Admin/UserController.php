<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Support\Loyalty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $customerBase = User::query()->where('is_admin', false);

        $totalCustomers = (clone $customerBase)->count();
        $newThisMonth = (clone $customerBase)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $activeThisWeek = (clone $customerBase)
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subWeek())
            ->count();

        $lifetimeCustomerSpend = (float) Order::whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })->sum('total');
        $aggregatePoints = Loyalty::pointsForAmount($lifetimeCustomerSpend);

        $search = trim((string) $request->query('q', ''));

        $usersQuery = (clone $customerBase)
            ->withCount('orders')
            ->withCount(['orders as delivered_orders_count' => function ($query) {
                $query->where('status', 'delivered');
            }])
            ->withSum('orders as total_spent', 'total')
            ->with(['orders' => function ($query) {
                $query->select('id', 'user_id', 'reference', 'status', 'total', 'placed_at')
                    ->latest('placed_at')
                    ->limit(1);
            }])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $usersQuery->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'summary' => [
                'totalCustomers' => $totalCustomers,
                'newThisMonth' => $newThisMonth,
                'activeThisWeek' => $activeThisWeek,
                'totalLoyaltyPoints' => $aggregatePoints,
                'averagePoints' => $totalCustomers > 0
                    ? (int) round($aggregatePoints / $totalCustomers)
                    : 0,
                'lifetimeSpend' => $lifetimeCustomerSpend,
            ],
        ]);
    }
}
