<x-layouts.pos>
    @php
        $isAdmin = auth()->user()?->hasRole(\App\Models\Role::ADMIN);
        $isManager = auth()->user()?->hasRole(\App\Models\Role::STORE_MANAGER);
        $isCashier = auth()->user()?->hasRole(\App\Models\Role::CASHIER);
        $productRoute = $isAdmin ? route('admin.products.index') : route('manager.products.index');
    @endphp

    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-content-muted text-sm">Welcome back,</p>
                <h2 class="text-2xl font-bold text-content">{{ auth()->user()->name }}</h2>
            </div>
            <div class="flex items-center gap-2 text-sm text-content-muted">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>

        @if($isCashier)
        {{-- ============================================ --}}
        {{-- CASHIER DASHBOARD --}}
        {{-- ============================================ --}}
        
        <!-- KPI Cards - Cashier -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Today's Sales -->
            <div class="kpi-card bg-primary text-white border-transparent">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80">Today's Sales</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight">₵{{ number_format($metrics['todaySales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-xs text-white/60">Resets at midnight</p>
                    </div>
                    <div class="kpi-icon bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="kpi-card border-primary-200 bg-primary-50">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary-700">Monthly Sales</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-primary-800">₵{{ number_format($metrics['monthlySales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-xs text-primary-600">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="kpi-icon bg-primary-100 text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Transactions -->
            <div class="kpi-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-content-secondary">My Transactions Today</p>
                        <p class="mt-2 text-3xl font-bold text-content tracking-tight">{{ $metrics['myTransactionsToday'] ?? 0 }}</p>
                    </div>
                    <div class="kpi-icon bg-info-light text-info">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Products Available -->
            <div class="kpi-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-content-secondary">Products Available</p>
                        <p class="mt-2 text-3xl font-bold text-content tracking-tight">{{ $metrics['productsInStock'] ?? 0 }}</p>
                    </div>
                    <div class="kpi-icon bg-success-light text-success">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions - Cashier -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-content">Quick Actions</h3>
                    <p class="text-sm text-content-muted">Start selling or view your history</p>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">
                    <a href="{{ route('cashier.pos') }}" class="quick-action quick-action-primary">
                        <span class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-primary-800">Point of Sale</span>
                        <span class="text-xs text-primary-600">Create new sales</span>
                    </a>
                    
                    <a href="{{ route('cashier.transactions') }}" class="quick-action border-gray-200 bg-gray-50 hover:border-gray-300 hover:bg-gray-100">
                        <span class="w-14 h-14 rounded-2xl bg-gray-600 text-white flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-content">My Transactions</span>
                        <span class="text-xs text-content-muted">View sales history</span>
                    </a>
                </div>
            </div>

            <!-- Top Products Today -->
            <div class="card">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-content">Top Products Today</h3>
                    <p class="text-sm text-content-muted">Best-selling items</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($topProducts as $item)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-primary-100 text-primary flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($item->product?->name ?? 'P', 0, 2)) }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-content">{{ $item->product?->name ?? 'Product' }}</p>
                                    <p class="text-xs text-content-muted">₵{{ number_format($item->product?->price ?? 0, 2) }}</p>
                                </div>
                            </div>
                            <span class="badge badge-success">{{ $item->total_qty }} sold</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="mt-2 text-sm text-content-muted">No sales yet today</p>
                            <a href="{{ route('cashier.pos') }}" class="mt-2 inline-flex text-sm font-medium text-primary hover:text-primary-800">Start selling →</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @elseif($isManager)
        {{-- ============================================ --}}
        {{-- MANAGER DASHBOARD --}}
        {{-- ============================================ --}}

        <!-- KPI Cards - Manager -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="kpi-card bg-primary text-white border-transparent">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80">Today's Sales</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight">₵{{ number_format($metrics['todaySales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-xs text-white/60">Resets at midnight</p>
                    </div>
                    <div class="kpi-icon bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card border-primary-200 bg-primary-50">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary-700">Monthly Sales</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-primary-800">₵{{ number_format($metrics['monthlySales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-xs text-primary-600">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="kpi-icon bg-primary-100 text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-content-secondary">Pending Orders</p>
                        <p class="mt-2 text-3xl font-bold text-content tracking-tight">{{ $metrics['openOrders'] ?? 0 }}</p>
                    </div>
                    <div class="kpi-icon bg-info-light text-info">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card {{ ($metrics['lowStock'] ?? 0) > 0 ? 'bg-warning text-white border-transparent' : '' }}">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium {{ ($metrics['lowStock'] ?? 0) > 0 ? 'text-white/80' : 'text-content-secondary' }}">Low Stock Items</p>
                        <p class="mt-2 text-3xl font-bold {{ ($metrics['lowStock'] ?? 0) > 0 ? '' : 'text-content' }} tracking-tight">{{ $metrics['lowStock'] ?? 0 }}</p>
                    </div>
                    <div class="kpi-icon {{ ($metrics['lowStock'] ?? 0) > 0 ? 'bg-white/20' : 'bg-warning-light text-warning' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-content-secondary">Total Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-content tracking-tight">₵{{ number_format($metrics['totalSales'] ?? 0, 2) }}</p>
                    </div>
                    <div class="kpi-icon bg-success-light text-success">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        @if(($metrics['lowStock'] ?? 0) > 0)
            <div class="alert alert-warning">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="font-semibold">Inventory Alert</p>
                    <p class="text-sm">{{ $metrics['lowStock'] }} items are below reorder level. Consider restocking soon.</p>
                </div>
            </div>
        @endif

        <!-- Quick Actions & Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-content">Quick Actions</h3>
                    <p class="text-sm text-content-muted">Manage your store</p>
                </div>
                <div class="p-5 space-y-3">
                    <a href="{{ route('manager.products.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-primary hover:bg-primary-50 transition group">
                        <span class="w-12 h-12 rounded-xl bg-primary text-white flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                        <div class="flex-1">
                            <p class="font-semibold text-content">Add Product</p>
                            <p class="text-sm text-content-muted">Add new inventory</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="{{ route('manager.products.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-primary hover:bg-primary-50 transition group">
                        <span class="w-12 h-12 rounded-xl bg-gray-600 text-white flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </span>
                        <div class="flex-1">
                            <p class="font-semibold text-content">Update Stock</p>
                            <p class="text-sm text-content-muted">Manage inventory levels</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Top Products -->
            <div class="card">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-semibold text-content">Top Products</h3>
                        <p class="text-sm text-content-muted">Best sellers</p>
                    </div>
                    <a href="{{ route('manager.products.index') }}" class="text-sm font-medium text-primary hover:text-primary-800">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($topProducts->take(5) as $index => $item)
                        <div class="px-5 py-3 flex items-center gap-4">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-200 text-gray-600' : 'bg-gray-100 text-gray-500') }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-content truncate">{{ $item->product?->name ?? 'Product' }}</p>
                                <p class="text-xs text-content-muted">₵{{ number_format($item->product?->price ?? 0, 2) }}</p>
                            </div>
                            <span class="badge badge-info">{{ $item->total_qty }} sold</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <p class="text-sm text-content-muted">No sales data yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="card">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-semibold text-content">Stock Alerts</h3>
                        <p class="text-sm text-content-muted">{{ $metrics['lowStock'] ?? 0 }} items low</p>
                    </div>
                    <a href="{{ route('manager.products.index') }}" class="text-sm font-medium text-primary hover:text-primary-800">Manage</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($lowStockItems->take(5) as $item)
                        <div class="px-5 py-3 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-danger-light text-danger flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-content truncate">{{ $item->name }}</p>
                                <p class="text-xs text-content-muted">SKU: {{ $item->sku }}</p>
                            </div>
                            <span class="badge badge-danger">{{ $item->stock }} left</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <div class="w-12 h-12 mx-auto rounded-full bg-success-light flex items-center justify-center">
                                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="mt-2 text-sm font-medium text-content">All stocked up!</p>
                            <p class="text-xs text-content-muted">No low stock items</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @else
        {{-- ============================================ --}}
        {{-- ADMIN DASHBOARD --}}
        {{-- ============================================ --}}

        <!-- KPI Cards - Admin -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="kpi-card bg-primary text-white border-transparent">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80">Today's Sales</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight">₵{{ number_format($metrics['todaySales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-xs text-white/60">Resets at midnight</p>
                    </div>
                    <div class="kpi-icon bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card border-primary-200 bg-primary-50">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary-700">Monthly Sales</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-primary-800">₵{{ number_format($metrics['monthlySales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-xs text-primary-600">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="kpi-icon bg-primary-100 text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-content-secondary">Total Users</p>
                        <p class="mt-2 text-3xl font-bold text-content tracking-tight">{{ $metrics['totalUsers'] ?? 0 }}</p>
                    </div>
                    <div class="kpi-icon bg-info-light text-info">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card {{ ($metrics['lowStock'] ?? 0) > 0 ? 'bg-warning text-white border-transparent' : '' }}">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium {{ ($metrics['lowStock'] ?? 0) > 0 ? 'text-white/80' : 'text-content-secondary' }}">Low Stock Items</p>
                        <p class="mt-2 text-3xl font-bold {{ ($metrics['lowStock'] ?? 0) > 0 ? '' : 'text-content' }} tracking-tight">{{ $metrics['lowStock'] ?? 0 }}</p>
                    </div>
                    <div class="kpi-icon {{ ($metrics['lowStock'] ?? 0) > 0 ? 'bg-white/20' : 'bg-warning-light text-warning' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="kpi-card bg-success text-white border-transparent">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/80">Total Revenue</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight">₵{{ number_format($metrics['totalSales'] ?? 0, 2) }}</p>
                    </div>
                    <div class="kpi-icon bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(($metrics['lowStock'] ?? 0) > 0)
            <div class="alert alert-warning">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="font-semibold">Inventory Alert</p>
                    <p class="text-sm">{{ $metrics['lowStock'] }} items are below reorder level. Review inventory immediately.</p>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-content">Quick Actions</h3>
                <p class="text-sm text-content-muted">Common tasks</p>
            </div>
            <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.index') }}" class="quick-action quick-action-primary">
                    <span class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold">Add Product</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="quick-action border-gray-200 bg-gray-50 hover:border-gray-300 hover:bg-gray-100">
                    <span class="w-14 h-14 rounded-2xl bg-gray-600 text-white flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold">Update Stock</span>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" class="quick-action border-gray-200 bg-white hover:border-primary hover:bg-primary-50">
                    <span class="w-14 h-14 rounded-2xl bg-gray-100 text-content-secondary flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold">View Orders</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="quick-action border-gray-200 bg-white hover:border-primary hover:bg-primary-50">
                    <span class="w-14 h-14 rounded-2xl bg-gray-100 text-content-secondary flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold">Manage Users</span>
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Users -->
            <div class="card">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-content">Recent Users</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-primary hover:text-primary-800">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentUsers as $user)
                        <div class="px-5 py-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-100 text-primary flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-content truncate">{{ $user->name }}</p>
                                <p class="text-xs text-content-muted truncate">{{ $user->email }}</p>
                            </div>
                            <span class="text-xs text-content-muted">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-content-muted">No users yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="card">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-content">Top Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-primary hover:text-primary-800">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($topProducts->take(5) as $index => $item)
                        <div class="px-5 py-3 flex items-center gap-4">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-200 text-gray-600' : 'bg-gray-100 text-gray-500') }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-content truncate">{{ $item->product?->name ?? 'Product' }}</p>
                                <p class="text-xs text-content-muted">SKU: {{ $item->product?->sku ?? 'N/A' }}</p>
                            </div>
                            <span class="badge badge-success">{{ $item->total_qty }} sold</span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-content-muted">No sales yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Low Stock -->
            <div class="card">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-content">Low Stock ({{ $metrics['lowStock'] ?? 0 }})</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-primary hover:text-primary-800">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($lowStockItems->take(5) as $item)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-content truncate">{{ $item->name }}</p>
                                <p class="text-xs text-content-muted">SKU: {{ $item->sku }}</p>
                            </div>
                            <span class="badge {{ $item->stock < 5 ? 'badge-danger' : 'badge-warning' }}">{{ $item->stock }} left</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <div class="w-12 h-12 mx-auto rounded-full bg-success-light flex items-center justify-center">
                                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-content-muted">All products well stocked</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @endif
    </div>
</x-layouts.pos>
