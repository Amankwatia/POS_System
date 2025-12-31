<x-app-layout>
    @php
        $isAdmin = auth()->user()?->hasRole(\App\Models\Role::ADMIN);
        $isManager = auth()->user()?->hasRole(\App\Models\Role::STORE_MANAGER);
        $isCashier = auth()->user()?->hasRole(\App\Models\Role::CASHIER);
        $productRoute = $isAdmin ? route('admin.products.index') : route('manager.products.index');
        $roleLabel = $isAdmin ? 'Admin' : ($isManager ? 'Manager' : 'Cashier');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                @if($isManager)
                    <div class="hidden sm:flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-lg shadow-violet-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</p>
                    <h2 class="font-bold text-xl text-gray-900 leading-tight">{{ $roleLabel }} Dashboard</h2>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($isCashier)
                    <a href="{{ route('cashier.pos') }}"
                       class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:shadow-emerald-300 hover:scale-105" style="background: linear-gradient(135deg, #059669 0%, #0d9488 100%);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Sale
                    </a>
                @endif
                @if($isManager)
                    <a href="{{ route('manager.products.index') }}"
                       class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:shadow-violet-300 hover:scale-105" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Manage Products
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Cashier-specific dashboard --}}
            @if($isCashier)
                {{-- Cashier Stats --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-emerald-100 text-sm">Today's Sales</p>
                                <p class="mt-2 text-3xl font-bold">₵{{ number_format($metrics['todaySales'] ?? 0, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm text-gray-500">My Transactions Today</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $metrics['myTransactionsToday'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-100 text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm text-gray-500">Products Available</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $metrics['productsInStock'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm text-gray-500">Current Time</p>
                                <p class="text-2xl font-bold text-gray-900">{{ now()->format('h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions for Cashier --}}
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-4">
                            <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                            <p class="text-emerald-100 text-sm">Start selling or view your history</p>
                        </div>
                        <div class="p-5 grid gap-4 sm:grid-cols-2">
                            <a href="{{ route('cashier.pos') }}" class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-dashed border-emerald-200 bg-emerald-50 hover:border-emerald-400 hover:bg-emerald-100 transition">
                                <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-600 text-white shadow-lg">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold text-emerald-800">Point of Sale</span>
                                <span class="text-xs text-emerald-600">Create new sales</span>
                            </a>
                            <a href="{{ route('cashier.transactions') }}" class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-dashed border-blue-200 bg-blue-50 hover:border-blue-400 hover:bg-blue-100 transition">
                                <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-lg">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold text-blue-800">My Transactions</span>
                                <span class="text-xs text-blue-600">View sales history</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                        <div class="border-b border-gray-100 px-5 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Top Products Today</h3>
                            <p class="text-sm text-gray-500">Best-selling items</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($topProducts as $item)
                                <div class="px-5 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 text-xs font-semibold">
                                            {{ strtoupper(substr($item->product?->name ?? 'P', 0, 2)) }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item->product?->name ?? 'Product' }}</p>
                                            <p class="text-xs text-gray-500">₵{{ number_format($item->product?->price ?? 0, 2) }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">{{ $item->total_qty }} sold</span>
                                </div>
                            @empty
                                <p class="px-5 py-8 text-center text-sm text-gray-500">No sales yet today. Start selling!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            @elseif($isManager)
                {{-- Manager-specific Dashboard --}}
                
                {{-- KPI Stats Cards --}}
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Today's Sales --}}
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-6 text-white shadow-xl shadow-violet-200">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <p class="text-violet-100 text-sm font-medium">Today's Sales</p>
                                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-3 text-3xl font-bold">₵{{ number_format($metrics['todaySales'] ?? 0, 2) }}</p>
                            <p class="mt-1 text-xs text-violet-200">Revenue collected today</p>
                        </div>
                    </div>

                    {{-- Total Sales --}}
                    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                All time
                            </span>
                        </div>
                        <p class="mt-4 text-2xl font-bold text-gray-900">₵{{ number_format($metrics['totalSales'] ?? 0, 2) }}</p>
                        <p class="mt-1 text-sm text-gray-500">Total Revenue</p>
                    </div>

                    {{-- Open Orders --}}
                    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-100 text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            @if(($metrics['openOrders'] ?? 0) > 0)
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                    Needs attention
                                </span>
                            @endif
                        </div>
                        <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($metrics['openOrders'] ?? 0) }}</p>
                        <p class="mt-1 text-sm text-gray-500">Pending Orders</p>
                    </div>

                    {{-- Low Stock Alert --}}
                    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl {{ ($metrics['lowStock'] ?? 0) > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            @if(($metrics['lowStock'] ?? 0) > 0)
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Alert
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                    All good
                                </span>
                            @endif
                        </div>
                        <p class="mt-4 text-2xl font-bold text-gray-900">{{ number_format($metrics['lowStock'] ?? 0) }}</p>
                        <p class="mt-1 text-sm text-gray-500">Low Stock Items</p>
                    </div>
                </div>

                {{-- Quick Actions Grid --}}
                <div class="grid gap-6 lg:grid-cols-3">
                    {{-- Quick Actions Card --}}
                    <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-5">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                                    <p class="text-violet-200 text-sm">Manage your store</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 space-y-3">
                            <a href="{{ route('manager.products.index') }}" class="flex items-center gap-4 p-4 rounded-xl border-2 border-dashed border-violet-200 bg-violet-50/50 hover:border-violet-400 hover:bg-violet-100 transition group">
                                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-violet-600 text-white shadow-lg shadow-violet-200 group-hover:scale-110 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <p class="font-semibold text-violet-900">Product Management</p>
                                    <p class="text-sm text-violet-600">Add, edit & manage inventory</p>
                                </div>
                                <svg class="w-5 h-5 text-violet-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50/50 hover:border-gray-300 hover:bg-gray-100 transition group">
                                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-600 text-white shadow-lg shadow-gray-200 group-hover:scale-110 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">Account Settings</p>
                                    <p class="text-sm text-gray-500">Profile & security</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Top Products --}}
                    <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Top Products</h3>
                                    <p class="text-sm text-gray-500">Best sellers</p>
                                </div>
                            </div>
                            <a href="{{ route('manager.products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($topProducts as $index => $item)
                                <div class="px-6 py-4 flex items-center gap-4">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-gray-200 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500')) }} text-sm font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->product?->name ?? 'Product' }}</p>
                                        <p class="text-xs text-gray-500">₵{{ number_format($item->product?->price ?? 0, 2) }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ $item->total_qty }} sold
                                    </span>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">No sales data yet</p>
                                    <a href="{{ route('manager.products.index') }}" class="mt-2 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-500">Add products →</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Low Stock Alerts --}}
                    <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-10 h-10 rounded-xl {{ ($metrics['lowStock'] ?? 0) > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Stock Alerts</h3>
                                    <p class="text-sm text-gray-500">{{ ($metrics['lowStock'] ?? 0) }} items low</p>
                                </div>
                            </div>
                            <a href="{{ route('manager.products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Manage</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($lowStockItems as $item)
                                <div class="px-6 py-4 flex items-center gap-4">
                                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->name }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ $item->sku }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $item->stock }} left
                                    </span>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-gray-900">All stocked up!</p>
                                    <p class="mt-1 text-sm text-gray-500">No low stock items</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Store Overview --}}
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="font-semibold text-gray-900">Store Overview</h3>
                                <p class="text-sm text-gray-500">Key metrics at a glance</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="text-center p-4 rounded-xl bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-xl bg-violet-100 text-violet-600 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-violet-700">₵{{ number_format($metrics['todaySales'] ?? 0, 2) }}</p>
                                <p class="text-sm text-violet-600 mt-1">Today's Revenue</p>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-xl bg-emerald-100 text-emerald-600 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-emerald-700">₵{{ number_format($metrics['totalSales'] ?? 0, 2) }}</p>
                                <p class="text-sm text-emerald-600 mt-1">Total Revenue</p>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-xl bg-amber-100 text-amber-600 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-amber-700">{{ number_format($metrics['openOrders'] ?? 0) }}</p>
                                <p class="text-sm text-amber-600 mt-1">Pending Orders</p>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-xl bg-blue-100 text-blue-600 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-blue-700">{{ number_format($metrics['totalUsers'] ?? 0) }}</p>
                                <p class="text-sm text-blue-600 mt-1">Total Users</p>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($metrics['totalUsers']) }}</p>
                        <p class="mt-1 text-xs text-gray-500">Across all roles</p>
                    </div>
                    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                        <p class="text-sm text-gray-500">Total Sales</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">₵{{ number_format($metrics['totalSales'], 2) }}</p>
                        <p class="mt-1 text-xs text-gray-500">All completed payments</p>
                    </div>
                    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                        <p class="text-sm text-gray-500">Open Orders</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">{{ number_format($metrics['openOrders']) }}</p>
                        <p class="mt-1 text-xs text-gray-500">Pending status</p>
                    </div>
                    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                        <p class="text-sm text-gray-500">Today's Sales</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">₵{{ number_format($metrics['todaySales'], 2) }}</p>
                        <p class="mt-1 text-xs text-gray-500">Completed payments</p>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2 space-y-6">
                        @if ($isAdmin)
                            <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                                    <h3 class="text-sm font-semibold text-gray-900">Recent Users</h3>
                                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Manage</a>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    @forelse ($recentUsers as $user)
                                        <div class="px-4 py-3 flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                            </div>
                                            <p class="text-xs text-gray-400">Joined {{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                    @empty
                                        <p class="px-4 py-3 text-sm text-gray-500">No users yet.</p>
                                    @endforelse
                                </div>
                            </section>
                        @endif

                        <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">Top Products</h3>
                                <a href="{{ $productRoute }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">View products</a>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse ($topProducts as $item)
                                    <div class="px-4 py-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $item->product?->name ?? 'Product' }}</p>
                                            <p class="text-xs text-gray-500">SKU {{ $item->product?->sku ?? 'N/A' }}</p>
                                        </div>
                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $item->total_qty }} sold</span>
                                    </div>
                                @empty
                                    <p class="px-4 py-3 text-sm text-gray-500">No sales yet.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    <div class="space-y-6">
                        <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">Low Stock ({{ $metrics['lowStock'] }})</h3>
                                <a href="{{ $productRoute }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">Manage</a>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse ($lowStockItems as $item)
                                    <div class="px-4 py-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $item->name }}</p>
                                            <p class="text-xs text-gray-500">SKU {{ $item->sku }}</p>
                                        </div>
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">{{ $item->stock }} left</span>
                                    </div>
                                @empty
                                    <p class="px-4 py-3 text-sm text-gray-500">All products are well stocked.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $roleLabel }} Shortcuts</h3>
                            </div>
                            <div class="p-4 space-y-3 text-sm text-gray-800">
                                <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50" href="{{ route('profile.edit') }}">
                                    <span>Profile & Security</span>
                                    <span class="text-gray-400">→</span>
                                </a>
                                @if($isAdmin)
                                    <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50" href="{{ route('admin.users.index') }}">
                                        <span>User Management</span>
                                        <span class="text-gray-400">→</span>
                                    </a>
                                    <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50" href="{{ route('admin.roles.index') }}">
                                        <span>Roles</span>
                                        <span class="text-gray-400">→</span>
                                    </a>
                                @endif
                                <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50" href="{{ $productRoute }}">
                                    <span>Products</span>
                                    <span class="text-gray-400">→</span>
                                </a>
                                @if($isAdmin)
                                    <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50" href="{{ route('admin.orders.index') }}">
                                        <span>Orders</span>
                                        <span class="text-gray-400">→</span>
                                    </a>
                                @endif
                            </div>
                        </section>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
