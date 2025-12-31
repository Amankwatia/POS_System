<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
        return view('pos-dashboard', [
            'metrics' => [
                'todaySales' => $this->getTodaySales($user->id),
                'monthlySales' => $this->getMonthlySales($user->id),
                'myTransactionsToday' => $this->getTodayTransactions($user->id),
                'productsInStock' => $this->getProductsInStockCount(),
            ],
            'topProducts' => $this->getTopProducts(today: true),
            'recentUsers' => collect(),
            'lowStockItems' => collect(),
        ]);
    }

    private function adminManagerDashboard(User $user): View
    {
        // Cache aggregated dashboard metrics for 60 seconds
        $metrics = Cache::remember('dashboard_metrics_admin', 60, function () {
            return [
                'totalUsers' => User::withoutRoles()->count(),
                'totalRoles' => Role::count(),
                'openOrders' => Order::pending()->count(),
                'totalSales' => Payment::completed()->sum('amount'),
                'lowStock' => Product::lowStock()->count(),
            ];
        });

        return view('pos-dashboard', [
            'metrics' => array_merge($metrics, [
                'todaySales' => $this->getTodaySales(),
                'monthlySales' => $this->getMonthlySales(),
            ]),
            'recentUsers' => User::withoutRoles()
                ->latest()
                ->take(5)
                ->get(['id', 'name', 'email', 'created_at']),
            'topProducts' => $this->getTopProducts(),
            'lowStockItems' => Product::lowStock()
                ->select(['id', 'name', 'sku', 'stock', 'reorder_level'])
                ->orderBy('stock')
                ->take(5)
                ->get(),
            'recentOrders' => Order::with('user:id,name')
                ->select(['id', 'user_id', 'total', 'status', 'created_at'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Get cached count of products in stock.
     */
    private function getProductsInStockCount(): int
    {
        return Cache::remember('products_in_stock_count', 120, function () {
            return Product::available()->count();
        });
    }

    /**
     * Get today's sales with timezone-aware date boundaries.
     * Automatically resets at midnight based on server timezone.
     * Cached for 60 seconds to reduce database queries.
     */
    private function getTodaySales(?int $userId = null): float
    {
        $timezone = config('app.timezone', 'UTC');
        $todayStart = Carbon::now($timezone)->startOfDay();
        $todayEnd = Carbon::now($timezone)->endOfDay();

        $cacheKey = $userId
            ? "today_sales_user_{$userId}_{$todayStart->format('Y-m-d')}"
            : "today_sales_all_{$todayStart->format('Y-m-d')}";

        return Cache::remember($cacheKey, 60, function () use ($todayStart, $todayEnd, $userId) {
            $query = Payment::completed()
                ->whereBetween('created_at', [$todayStart, $todayEnd]);

            if ($userId) {
                $query->forUser($userId);
            }

            return (float) $query->sum('amount');
        });
    }

    /**
     * Get monthly sales - sum of all completed payment amounts within the current month.
     * This includes all daily sales added together.
     * Cached for 60 seconds to balance performance with accuracy.
     */
    private function getMonthlySales(?int $userId = null): float
    {
        $timezone = config('app.timezone', 'UTC');
        $monthStart = Carbon::now($timezone)->startOfMonth();
        $monthEnd = Carbon::now($timezone)->endOfMonth();

        $cacheKey = $userId
            ? "monthly_sales_user_{$userId}_{$monthStart->format('Y-m')}"
            : "monthly_sales_all_{$monthStart->format('Y-m')}";

        // Cache for 60 seconds to ensure recent sales are reflected quickly
        return Cache::remember($cacheKey, 60, function () use ($monthStart, $monthEnd, $userId) {
            $query = Payment::completed()
                ->whereBetween('created_at', [$monthStart, $monthEnd]);

            if ($userId) {
                $query->forUser($userId);
            }

            // Sum all payment amounts for the month (includes all daily sales)
            return (float) $query->sum('amount');
        });
    }

    /**
     * Get today's transaction count for a user.
     * Cached for 60 seconds.
     */
    private function getTodayTransactions(int $userId): int
    {
        $timezone = config('app.timezone', 'UTC');
        $todayStart = Carbon::now($timezone)->startOfDay();
        $todayEnd = Carbon::now($timezone)->endOfDay();

        $cacheKey = "today_transactions_user_{$userId}_{$todayStart->format('Y-m-d')}";

        return Cache::remember($cacheKey, 60, function () use ($todayStart, $todayEnd, $userId) {
            return Order::where('user_id', $userId)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count();
        });
    }

    /**
     * Get top selling products.
     * Fixed N+1 issue: eager load products AFTER groupBy aggregation.
     * Cached for 5 minutes.
     */
    private function getTopProducts(bool $today = false): \Illuminate\Support\Collection
    {
        $timezone = config('app.timezone', 'UTC');
        $cacheKey = $today
            ? 'top_products_today_' . Carbon::now($timezone)->format('Y-m-d')
            : 'top_products_all';

        return Cache::remember($cacheKey, 300, function () use ($today, $timezone) {
            $query = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->take(5);

            if ($today) {
                $todayStart = Carbon::now($timezone)->startOfDay();
                $todayEnd = Carbon::now($timezone)->endOfDay();

                // Use exists subquery instead of whereHas for better performance
                $query->whereExists(function ($subquery) use ($todayStart, $todayEnd) {
                    $subquery->select(DB::raw(1))
                        ->from('orders')
                        ->whereColumn('orders.id', 'order_items.order_id')
                        ->whereBetween('orders.created_at', [$todayStart, $todayEnd]);
                });
            }

            // Get results first, then eager load relationships
            // This fixes the N+1 issue with groupBy
            return $query->get()->load('product:id,name,sku,price');
        });
    }
}
