<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasRole(Role::CASHIER)) {
            return $this->cashierDashboard($user);
        }

        return $this->adminManagerDashboard($user);
    }

    private function cashierDashboard(User $user): View
    {
        return view('dashboard', [
            'metrics' => [
                'todaySales' => Payment::completed()->today()->forUser($user->id)->sum('amount'),
                'myTransactionsToday' => Order::today()->forUser($user->id)->count(),
                'productsInStock' => Product::available()->count(),
            ],
            'topProducts' => $this->getTopProducts(today: true),
            'recentUsers' => collect(),
            'lowStockItems' => collect(),
        ]);
    }

    private function adminManagerDashboard(User $user): View
    {
        return view('dashboard', [
            'metrics' => [
                'todaySales' => Payment::completed()->today()->sum('amount'),
                'totalUsers' => User::count(),
                'totalRoles' => Role::count(),
                'openOrders' => Order::pending()->count(),
                'totalSales' => Payment::completed()->sum('amount'),
                'lowStock' => Product::lowStock()->count(),
            ],
            'recentUsers' => User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
            'topProducts' => $this->getTopProducts(),
            'lowStockItems' => Product::lowStock()->orderBy('stock')->take(5)->get(),
        ]);
    }

    private function getTopProducts(bool $today = false): \Illuminate\Support\Collection
    {
        $query = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5);

        if ($today) {
            $query->whereHas('order', fn($q) => $q->today());
        }

        return $query->get();
    }
}
