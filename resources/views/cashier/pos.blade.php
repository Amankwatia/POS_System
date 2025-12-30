<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-emerald-600">Cashier Terminal</p>
                        <h2 class="font-bold text-2xl text-gray-900">Point of Sale</h2>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('cashier.transactions') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    My Transactions
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message with Receipt --}}
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <div class="flex-1">
                        <p class="font-medium text-emerald-800">{{ session('status') }}</p>
                        @if(session('receipt'))
                            <p class="text-sm text-emerald-700">
                                Order #{{ session('receipt')['order_id'] }} · 
                                Total: ${{ number_format(session('receipt')['total'], 2) }}
                                @if(session('receipt')['change'] > 0)
                                    · Change: ${{ number_format(session('receipt')['change'], 2) }}
                                @endif
                            </p>
                        @endif
                    </div>
                    @if(session('receipt'))
                        <a href="{{ route('cashier.receipt', session('receipt')['order_id']) }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">View Receipt →</a>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                    <div class="flex items-center gap-2 text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            {{-- Quick Stats --}}
            <div class="grid gap-4 sm:grid-cols-2 mb-6">
                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm text-gray-500">Today's Sales</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($todaySales, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm text-gray-500">Today's Transactions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $todayTransactions }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Product Selection --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                        <div class="border-b border-gray-100 px-5 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Select Products</h3>
                            <p class="text-sm text-gray-500">Click on a product to add it to the cart</p>
                        </div>
                        <div class="p-4">
                            <div class="mb-4">
                                <input type="text" id="product-search" placeholder="Search products..." class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                            <div id="product-grid" class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 max-h-[500px] overflow-y-auto">
                                @forelse($products as $product)
                                    <button type="button" 
                                            onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})"
                                            class="product-card flex flex-col items-start p-3 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition text-left"
                                            data-name="{{ strtolower($product->name) }}"
                                            data-sku="{{ strtolower($product->sku) }}">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-gray-600 text-sm font-semibold mb-2">
                                            {{ strtoupper(substr($product->name, 0, 2)) }}
                                        </div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                        <div class="flex items-center justify-between w-full mt-2">
                                            <span class="text-emerald-600 font-semibold">${{ number_format($product->price, 2) }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $product->stock <= $product->reorder_level ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $product->stock }} in stock
                                            </span>
                                        </div>
                                    </button>
                                @empty
                                    <div class="col-span-full py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <p class="text-gray-500">No products available</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cart & Checkout --}}
                <div class="lg:col-span-1">
                    <form action="{{ route('cashier.pos.store') }}" method="POST" id="checkout-form" class="sticky top-6">
                        @csrf
                        <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-2xl overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/20 text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="text-base font-semibold text-white">Current Sale</h3>
                                        <p class="text-xs text-emerald-100" id="cart-count">0 items</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                {{-- Cart Items --}}
                                <div id="cart-items" class="space-y-2 max-h-[250px] overflow-y-auto mb-4">
                                    <p id="empty-cart" class="text-center text-gray-400 py-8">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Cart is empty
                                    </p>
                                </div>

                                {{-- Totals --}}
                                <div class="border-t border-gray-100 pt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Subtotal</span>
                                        <span id="subtotal" class="font-medium text-gray-900">$0.00</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Total</span>
                                        <span id="total" class="text-emerald-600">$0.00</span>
                                    </div>
                                </div>

                                {{-- Payment Method --}}
                                <div class="mt-4 space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="payment-option relative cursor-pointer" data-method="cash">
                                            <input type="radio" name="payment_method" value="cash" class="sr-only" checked>
                                            {{-- Checkmark badge --}}
                                            <span class="checkmark absolute -top-2 -right-2 z-10 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center transition-all duration-200 shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            <div class="payment-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-emerald-500 bg-emerald-50 shadow-lg shadow-emerald-100 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                                <span class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-semibold text-gray-700">Cash</span>
                                            </div>
                                        </label>
                                        <label class="payment-option relative cursor-pointer" data-method="card">
                                            <input type="radio" name="payment_method" value="card" class="sr-only">
                                            {{-- Checkmark badge --}}
                                            <span class="checkmark absolute -top-2 -right-2 z-10 w-6 h-6 rounded-full bg-blue-500 text-white items-center justify-center transition-all duration-200 shadow-lg hidden">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            <div class="payment-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                                <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-semibold text-gray-700">Card</span>
                                            </div>
                                        </label>
                                        <label class="payment-option relative cursor-pointer" data-method="mobile">
                                            <input type="radio" name="payment_method" value="mobile" class="sr-only">
                                            {{-- Checkmark badge --}}
                                            <span class="checkmark absolute -top-2 -right-2 z-10 w-6 h-6 rounded-full bg-purple-500 text-white items-center justify-center transition-all duration-200 shadow-lg hidden">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            <div class="payment-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                                <span class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-semibold text-gray-700">Mobile</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Amount Tendered (for cash) --}}
                                <div id="cash-section" class="mt-4 space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Amount Tendered</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">$</span>
                                        <input type="number" name="amount_tendered" id="amount-tendered" step="0.01" min="0" placeholder="0.00" class="w-full rounded-xl border-gray-200 bg-gray-50 pl-7 pr-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Change</span>
                                        <span id="change" class="font-medium text-emerald-600">$0.00</span>
                                    </div>
                                </div>

                                {{-- Hidden inputs for cart items --}}
                                <div id="cart-inputs"></div>

                                {{-- Checkout Button --}}
                                <button type="submit" id="checkout-btn" disabled class="mt-4 w-full flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold shadow-lg transition disabled:bg-gray-300 disabled:cursor-not-allowed" style="background-color: #059669; color: #ffffff;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Complete Sale
                                </button>

                                <button type="button" onclick="clearCart()" class="mt-2 w-full flex items-center justify-center gap-2 rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = {};

        function addToCart(productId, name, price, stock) {
            if (cart[productId]) {
                if (cart[productId].quantity >= stock) {
                    alert('Cannot add more. Stock limit reached.');
                    return;
                }
                cart[productId].quantity++;
            } else {
                cart[productId] = {
                    id: productId,
                    name: name,
                    price: price,
                    quantity: 1,
                    stock: stock
                };
            }
            renderCart();
        }

        function updateQuantity(productId, change) {
            if (!cart[productId]) return;

            const newQty = cart[productId].quantity + change;
            if (newQty <= 0) {
                delete cart[productId];
            } else if (newQty > cart[productId].stock) {
                alert('Cannot add more. Stock limit reached.');
                return;
            } else {
                cart[productId].quantity = newQty;
            }
            renderCart();
        }

        function removeFromCart(productId) {
            delete cart[productId];
            renderCart();
        }

        function clearCart() {
            cart = {};
            renderCart();
        }

        function renderCart() {
            const cartItems = document.getElementById('cart-items');
            const cartInputs = document.getElementById('cart-inputs');
            const emptyCart = document.getElementById('empty-cart');
            const cartCount = document.getElementById('cart-count');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const checkoutBtn = document.getElementById('checkout-btn');

            const items = Object.values(cart);
            let subtotal = 0;
            let totalItems = 0;

            // Clear previous
            cartItems.innerHTML = '';
            cartInputs.innerHTML = '';

            if (items.length === 0) {
                cartItems.innerHTML = `
                    <p id="empty-cart" class="text-center text-gray-400 py-8">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Cart is empty
                    </p>
                `;
                checkoutBtn.disabled = true;
                checkoutBtn.style.backgroundColor = '#d1d5db';
            } else {
                items.forEach((item, index) => {
                    const lineTotal = item.price * item.quantity;
                    subtotal += lineTotal;
                    totalItems += item.quantity;

                    cartItems.innerHTML += `
                        <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${item.name}</p>
                                <p class="text-xs text-gray-500">$${item.price.toFixed(2)} × ${item.quantity}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900">$${lineTotal.toFixed(2)}</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 rounded bg-gray-200 hover:bg-gray-300 text-gray-600 text-xs">−</button>
                                    <span class="w-6 text-center text-sm">${item.quantity}</span>
                                    <button type="button" onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 rounded bg-gray-200 hover:bg-gray-300 text-gray-600 text-xs">+</button>
                                </div>
                                <button type="button" onclick="removeFromCart(${item.id})" class="w-6 h-6 rounded bg-red-100 hover:bg-red-200 text-red-600 text-xs">×</button>
                            </div>
                        </div>
                    `;

                    cartInputs.innerHTML += `
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    `;
                });

                checkoutBtn.disabled = false;
                checkoutBtn.style.backgroundColor = '#059669';
            }

            cartCount.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            totalEl.textContent = `$${subtotal.toFixed(2)}`;

            updateChange();
        }

        function updateChange() {
            const total = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tendered = parseFloat(document.getElementById('amount-tendered').value) || 0;
            const change = Math.max(0, tendered - total);
            document.getElementById('change').textContent = `$${change.toFixed(2)}`;
        }

        // Payment method toggle
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const cashSection = document.getElementById('cash-section');
                cashSection.style.display = this.value === 'cash' ? 'block' : 'none';
                
                // Update payment method styling
                updatePaymentMethodStyle(this.value);
            });
        });

        function updatePaymentMethodStyle(selectedMethod) {
            const methods = {
                'cash': { border: 'border-emerald-500', bg: 'bg-emerald-50', shadow: 'shadow-lg shadow-emerald-100' },
                'card': { border: 'border-blue-500', bg: 'bg-blue-50', shadow: 'shadow-lg shadow-blue-100' },
                'mobile': { border: 'border-purple-500', bg: 'bg-purple-50', shadow: 'shadow-lg shadow-purple-100' }
            };

            document.querySelectorAll('.payment-option').forEach(option => {
                const method = option.dataset.method;
                const card = option.querySelector('.payment-card');
                const checkmark = option.querySelector('.checkmark');
                const isSelected = method === selectedMethod;

                // Reset card styles
                card.classList.remove('border-emerald-500', 'border-blue-500', 'border-purple-500', 
                                     'bg-emerald-50', 'bg-blue-50', 'bg-purple-50',
                                     'shadow-lg', 'shadow-emerald-100', 'shadow-blue-100', 'shadow-purple-100');
                
                if (isSelected) {
                    // Add selected styles
                    card.classList.add(methods[method].border, methods[method].bg, 'shadow-lg');
                    card.classList.add(methods[method].shadow.split(' ')[1]);
                    // Show checkmark
                    checkmark.classList.remove('hidden');
                    checkmark.classList.add('flex');
                } else {
                    // Add default styles
                    card.classList.add('border-gray-200', 'bg-white');
                    // Hide checkmark
                    checkmark.classList.add('hidden');
                    checkmark.classList.remove('flex');
                }
            });
        }

        // Initialize payment method style on page load
        updatePaymentMethodStyle('cash');

        // Amount tendered change listener
        document.getElementById('amount-tendered').addEventListener('input', updateChange);

        // Product search
        document.getElementById('product-search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name;
                const sku = card.dataset.sku;
                card.style.display = (name.includes(query) || sku.includes(query)) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
