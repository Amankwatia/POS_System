<x-app-layout>
    @php
        $isAdmin = auth()->user()?->hasRole(\App\Models\Role::ADMIN);
        $productStoreRoute = $isAdmin ? route('admin.products.store') : route('manager.products.store');
        $importRoute = $isAdmin ? route('admin.products.import.form') : route('manager.products.import.form');
        $routePrefix = $isAdmin ? 'admin' : 'manager';
        $totalProducts = $products->total();
        $avgPrice = max($products->avg('price') ?? 0, 0);
        $lowStockCount = $products->filter(fn($p) => $p->stock <= $p->reorder_level)->count();
        $newestProduct = $products->first();
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-indigo-600">Inventory Management</p>
                        <h2 class="font-bold text-2xl text-gray-900">Products & Stock</h2>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500 max-w-xl">Manage your product catalog, track inventory levels, and set reorder thresholds to keep your business running smoothly.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ $importRoute }}" class="inline-flex items-center gap-2 rounded-lg border border-primary bg-primary-50 px-4 py-2.5 text-sm font-medium text-primary shadow-sm transition hover:bg-primary-100 hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Bulk Import
                </a>
                <button type="submit" form="product-create-form" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold shadow-lg transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2" style="color: #ffffff; background-color: #111827;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Save Product
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Success/Error Messages --}}
            @if (session('status'))
                <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
                </div>
            @endif

            {{-- KPI Cards --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Total Products --}}
                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md hover:ring-gray-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-blue-50 to-indigo-50 opacity-60"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </span>
                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Catalog</span>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                        <p class="mt-1 text-sm text-gray-500">Total Products</p>
                    </div>
                </div>

                {{-- Average Price --}}
                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md hover:ring-gray-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-emerald-50 to-green-50 opacity-60"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Pricing</span>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-gray-900">₵{{ number_format($avgPrice, 2) }}</p>
                        <p class="mt-1 text-sm text-gray-500">Average Price</p>
                    </div>
                </div>

                {{-- Low Stock --}}
                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md hover:ring-gray-200 {{ $lowStockCount > 0 ? 'ring-amber-200 bg-amber-50/30' : '' }}">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-amber-50 to-orange-50 opacity-60"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-500' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </span>
                            @if($lowStockCount > 0)
                                <span class="text-xs font-medium text-amber-700 bg-amber-100 px-2 py-1 rounded-full animate-pulse">Action Needed</span>
                            @else
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">All Good</span>
                            @endif
                        </div>
                        <p class="mt-4 text-3xl font-bold {{ $lowStockCount > 0 ? 'text-amber-700' : 'text-gray-900' }}">{{ number_format($lowStockCount) }}</p>
                        <p class="mt-1 text-sm {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-gray-500' }}">Low Stock Items</p>
                    </div>
                </div>

                {{-- Newest Item --}}
                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:shadow-md hover:ring-gray-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-purple-50 to-pink-50 opacity-60"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-100 text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </span>
                            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Latest</span>
                        </div>
                        <p class="mt-4 text-lg font-bold text-gray-900 truncate" title="{{ $newestProduct?->name ?? '—' }}">{{ $newestProduct?->name ?? '—' }}</p>
                        <p class="mt-1 text-sm text-gray-500">{{ $newestProduct?->created_at?->format('M d, Y') ?? 'No products yet' }}</p>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid gap-8 lg:grid-cols-3">

                {{-- Add Product Form --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/20 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-base font-semibold text-white">Add New Product</h3>
                                    <p class="text-xs text-indigo-100">Fill in the details below</p>
                                </div>
                            </div>
                        </div>

                        <form id="product-create-form" method="POST" action="{{ $productStoreRoute }}" class="p-5 space-y-5">
                            @csrf

                            <div class="space-y-1.5">
                                <label class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Product Name
                                </label>
                                <input name="name" type="text" placeholder="e.g., Wireless Mouse" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20" required>
                                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    SKU Code
                                </label>
                                <input name="sku" type="text" placeholder="e.g., WM-001" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 font-mono" required>
                                @error('sku')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        Price
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                        <input name="price" type="number" step="0.01" min="0" placeholder="0.00" class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-7 pr-4 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20" required>
                                    </div>
                                    @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Stock
                                    </label>
                                    <input name="stock" type="number" min="0" placeholder="0" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20" required>
                                    @error('stock')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Reorder Level
                                </label>
                                <input name="reorder_level" type="number" min="0" placeholder="e.g., 10" class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20" required>
                                <p class="text-xs text-gray-500">Alert when stock falls to this level</p>
                                @error('reorder_level')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                    Description
                                    <span class="text-gray-400 font-normal">(optional)</span>
                                </label>
                                <textarea name="description" rows="3" placeholder="Brief product description..." class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 resize-none"></textarea>
                                @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold shadow-lg transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2" style="color: #ffffff; background-color: #111827;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Product Catalog Table --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Product Catalog</h3>
                                <p class="text-sm text-gray-500">Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</p>
                            </div>
                            <div class="flex items-center gap-3">
                                {{-- Search Input --}}
                                <form method="GET" action="" class="flex items-center gap-2">
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search..."
                                            class="w-40 rounded-lg border-gray-200 bg-gray-50 py-1.5 px-2.5 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <button type="submit" class="p-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </button>
                                    @if($search ?? false)
                                        <a href="{{ url()->current() }}" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition" title="Clear search">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </a>
                                    @endif
                                </form>

                                {{-- Export Dropdown --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Export
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg ring-1 ring-gray-100 py-1 z-10">
                                        <a href="{{ route($routePrefix . '.products.export', ['format' => 'csv', 'search' => $search ?? '']) }}" 
                                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            CSV
                                        </a>
                                        <a href="{{ route($routePrefix . '.products.export', ['format' => 'excel', 'search' => $search ?? '']) }}" 
                                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Excel
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-amber-400 ring-2 ring-amber-100"></span>
                                    Low stock
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50/80">
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Product</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">SKU</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Price</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Stock</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Added</th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 sticky right-0 bg-gray-50/80">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($products as $product)
                                        @php
                                            $isLow = $product->stock <= $product->reorder_level;
                                            $isOutOfStock = $product->stock == 0;
                                        @endphp
                                        <tr class="group transition hover:bg-gray-50 {{ $isLow ? 'bg-amber-50/50' : '' }}">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl {{ $isLow ? 'bg-amber-100 text-amber-600' : 'bg-indigo-50 text-indigo-600' }} text-sm font-semibold">
                                                        {{ strtoupper(substr($product->name, 0, 2)) }}
                                                    </span>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                                        @if($product->description)
                                                            <p class="text-xs text-gray-500 truncate max-w-[200px]" title="{{ $product->description }}">{{ $product->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <code class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-mono text-gray-600">{{ $product->sku }}</code>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="text-sm font-semibold text-gray-900">₵{{ number_format($product->price, 2) }}</span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium {{ $isOutOfStock ? 'text-red-600' : ($isLow ? 'text-amber-600' : 'text-gray-900') }}">{{ $product->stock }}</span>
                                                    <span class="text-xs text-gray-400">/ {{ $product->reorder_level }} min</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                @if($isOutOfStock)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                        Out of Stock
                                                    </span>
                                                @elseif($isLow)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                        Low Stock
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                        In Stock
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
                                                    <span class="text-xs text-gray-500">{{ $product->created_at->format('h:i A') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-right sticky right-0 bg-white group-hover:bg-gray-50 {{ $isLow ? 'group-hover:bg-amber-50/50' : '' }}">
                                                <div class="flex items-center justify-end gap-1">
                                                    <button 
                                                        type="button"
                                                        onclick="openEditModal({{ $product->id }}, '{{ route($routePrefix . '.products.edit', $product) }}')"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition"
                                                        title="Edit product"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <form 
                                                        action="{{ route($routePrefix . '.products.destroy', $product) }}" 
                                                        method="POST" 
                                                        class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this product?')"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button 
                                                            type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition"
                                                            title="Delete product"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-5 py-16 text-center">
                                                <div class="flex flex-col items-center">
                                                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 text-gray-400 mb-4">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </span>
                                                    <p class="text-sm font-medium text-gray-900">No products yet</p>
                                                    <p class="text-sm text-gray-500 mt-1">Add your first product using the form on the left.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($products->hasPages())
                            <div class="border-t border-gray-100 px-5 py-4">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Product Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/50 transition-opacity" onclick="closeEditModal()"></div>

            <div class="relative w-full max-w-xs transform rounded-xl bg-white shadow-xl ring-1 ring-gray-100 overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-white" id="modal-title">Edit Product</h3>
                    <button onclick="closeEditModal()" class="p-1 text-white/70 hover:text-white rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form id="editProductForm" method="POST" class="p-3 space-y-2">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit_name" class="block text-xs font-medium text-gray-600">Name</label>
                        <input type="text" name="name" id="edit_name" required class="mt-0.5 block w-full rounded border-gray-200 bg-gray-50 px-2 py-1.5 text-sm focus:border-amber-500 focus:bg-white focus:ring-1 focus:ring-amber-500">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="edit_sku" class="block text-xs font-medium text-gray-600">SKU</label>
                            <input type="text" name="sku" id="edit_sku" required class="mt-0.5 block w-full rounded border-gray-200 bg-gray-50 px-2 py-1.5 text-sm font-mono focus:border-amber-500 focus:bg-white focus:ring-1 focus:ring-amber-500">
                        </div>
                        <div>
                            <label for="edit_price" class="block text-xs font-medium text-gray-600">Price (₵)</label>
                            <input type="number" name="price" id="edit_price" step="0.01" min="0" required class="mt-0.5 block w-full rounded border-gray-200 bg-gray-50 px-2 py-1.5 text-sm focus:border-amber-500 focus:bg-white focus:ring-1 focus:ring-amber-500">
                        </div>
                        <div>
                            <label for="edit_stock" class="block text-xs font-medium text-gray-600">Stock</label>
                            <input type="number" name="stock" id="edit_stock" min="0" required class="mt-0.5 block w-full rounded border-gray-200 bg-gray-50 px-2 py-1.5 text-sm focus:border-amber-500 focus:bg-white focus:ring-1 focus:ring-amber-500">
                        </div>
                        <div>
                            <label for="edit_reorder_level" class="block text-xs font-medium text-gray-600">Reorder</label>
                            <input type="number" name="reorder_level" id="edit_reorder_level" min="0" required class="mt-0.5 block w-full rounded border-gray-200 bg-gray-50 px-2 py-1.5 text-sm focus:border-amber-500 focus:bg-white focus:ring-1 focus:ring-amber-500">
                        </div>
                    </div>

                    <div>
                        <label for="edit_description" class="block text-xs font-medium text-gray-600">Description</label>
                        <input type="text" name="description" id="edit_description" class="mt-0.5 block w-full rounded border-gray-200 bg-gray-50 px-2 py-1.5 text-sm focus:border-amber-500 focus:bg-white focus:ring-1 focus:ring-amber-500">
                    </div>

                    <label class="flex items-center gap-1.5 pt-1">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="h-3.5 w-3.5 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                        <span class="text-xs text-gray-600">Active</span>
                    </label>

                    <button type="submit" class="w-full rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800 transition">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript for Edit Modal --}}
    @push('scripts')
    <script>
        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editProductForm');
        const routePrefix = '{{ $routePrefix }}';

        function openEditModal(productId, editUrl) {
            // Show loading state
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Fetch product data
            fetch(editUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(product => {
                // Populate form fields
                document.getElementById('edit_name').value = product.name || '';
                document.getElementById('edit_sku').value = product.sku || '';
                document.getElementById('edit_price').value = product.price || '';
                document.getElementById('edit_stock').value = product.stock || '';
                document.getElementById('edit_reorder_level').value = product.reorder_level || '';
                document.getElementById('edit_description').value = product.description || '';
                document.getElementById('edit_is_active').checked = product.is_active;

                // Update form action URL
                editForm.action = `/${routePrefix}/products/${productId}`;
            })
            .catch(error => {
                console.error('Error fetching product:', error);
                closeEditModal();
                alert('Failed to load product data. Please try again.');
            });
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Reset form
            editForm.reset();
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !editModal.classList.contains('hidden')) {
                closeEditModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
