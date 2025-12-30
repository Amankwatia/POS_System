<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-gray-700 to-gray-900 text-white shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-600">Receipt</p>
                        <h2 class="font-bold text-2xl text-gray-900">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <a href="{{ route('cashier.transactions') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Transactions
                </a>
                <a href="{{ route('cashier.pos') }}" class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:opacity-90" style="background-color: #059669; color: #ffffff;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Sale
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg ring-1 ring-gray-200 rounded-2xl overflow-hidden print:shadow-none print:ring-0">
                {{-- Receipt Header --}}
                <div class="bg-gray-900 text-white px-6 py-8 text-center print:bg-white print:text-black">
                    <h1 class="text-2xl font-bold">POS System</h1>
                    <p class="text-gray-400 mt-1 print:text-gray-600">Sales Receipt</p>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Order Info --}}
                    <div class="flex justify-between text-sm border-b border-dashed border-gray-200 pb-4">
                        <div>
                            <p class="text-gray-500">Order Number</p>
                            <p class="font-mono font-semibold text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500">Date & Time</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                            <p class="text-gray-600">{{ $order->created_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    {{-- Cashier Info --}}
                    <div class="text-sm border-b border-dashed border-gray-200 pb-4">
                        <p class="text-gray-500">Cashier</p>
                        <p class="font-semibold text-gray-900">{{ $order->user?->name ?? 'Unknown' }}</p>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900 text-sm uppercase tracking-wider">Items</h3>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $item->product?->name ?? 'Product' }}</p>
                                        <p class="text-gray-500">${{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-900">${{ number_format($item->total, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="border-t border-dashed border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->tax > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tax</span>
                                <span class="text-gray-900">${{ number_format($order->tax, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Total</span>
                            <span class="text-emerald-600">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Payment Info --}}
                    @if($order->payment)
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                            <h3 class="font-semibold text-gray-900 text-sm uppercase tracking-wider">Payment Details</h3>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Method</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($order->payment->method) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Status</span>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Amount Paid</span>
                                <span class="font-semibold text-gray-900">${{ number_format($order->payment->amount, 2) }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="text-center pt-4 border-t border-dashed border-gray-200">
                        <p class="text-gray-500 text-sm">Thank you for your purchase!</p>
                        <p class="text-gray-400 text-xs mt-1">{{ now()->format('Y') }} POS System</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            nav, header > div > div:last-child, .print\\:hidden {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</x-app-layout>
