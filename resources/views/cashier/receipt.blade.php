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
            <div class="flex flex-wrap gap-3 print:hidden">
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

    @php
        // Use stored values from the order
        $subtotal = $order->subtotal;
        $taxAmount = $order->tax;
        $grandTotal = $order->total;
        $receiptNo = str_pad($order->id, 6, '0', STR_PAD_LEFT);
    @endphp

    <div class="py-8">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Screen Preview Card --}}
            <div class="bg-white shadow-lg ring-1 ring-gray-200 rounded-2xl overflow-hidden print:hidden mb-4">
                <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                    <p class="text-sm text-gray-600 text-center">Receipt Preview (80mm Thermal)</p>
                </div>
            </div>

            {{-- Thermal Receipt - Optimized for 80mm (42 chars width) --}}
            <div id="thermal-receipt" class="bg-white shadow-lg ring-1 ring-gray-200 rounded-lg overflow-hidden print:shadow-none print:ring-0 print:rounded-none">
                <div class="receipt-content p-4 font-mono text-sm leading-tight" style="width: 80mm; max-width: 100%;">

                    {{-- ========== HEADER ========== --}}
                    <div class="text-center mb-3">
                        <div class="text-lg font-bold tracking-wide">IDEAS ELECTRICALS</div>
                        <div class="text-xs mt-1">PMB 30, East Legon</div>
                        <div class="text-xs">Tel: +233244062967</div>
                        <div class="text-xs">VAT No: GHA 001</div>
                    </div>

                    <div class="border-t border-dashed border-gray-400 my-2"></div>

                    {{-- ========== TRANSACTION INFO ========== --}}
                    <div class="text-xs space-y-0.5 mb-2">
                        <div class="flex justify-between">
                            <span>Receipt No:</span>
                            <span class="font-semibold">#{{ $receiptNo }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date:</span>
                            <span>{{ $order->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Time:</span>
                            <span>{{ $order->created_at->format('H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Cashier:</span>
                            <span>{{ $order->user?->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-400 my-2"></div>

                    {{-- ========== ITEMS HEADER ========== --}}
                    <div class="text-xs font-bold flex justify-between mb-1">
                        <span class="flex-1">ITEM</span>
                        <span class="w-16 text-right">QTY</span>
                        <span class="w-20 text-right">AMOUNT</span>
                    </div>

                    <div class="border-t border-gray-300 my-1"></div>

                    {{-- ========== ITEMS LIST ========== --}}
                    <div class="text-xs space-y-1.5">
                        @foreach($order->items as $item)
                            <div>
                                <div class="truncate font-medium">{{ $item->product?->name ?? 'Item' }}</div>
                                <div class="flex justify-between text-gray-600">
                                    <span>@ GH₵{{ number_format($item->unit_price, 2) }}</span>
                                    <span class="w-16 text-right">x{{ $item->quantity }}</span>
                                    <span class="w-20 text-right font-medium text-black">{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-dashed border-gray-400 my-2"></div>

                    {{-- ========== TOTALS ========== --}}
                    <div class="text-xs space-y-1">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>GH₵{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>VAT (15%):</span>
                            <span>GH₵{{ number_format($taxAmount, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-300 my-1"></div>
                        <div class="flex justify-between font-bold text-sm">
                            <span>GRAND TOTAL:</span>
                            <span>GH₵{{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-400 my-2"></div>

                    {{-- ========== PAYMENT INFO ========== --}}
                    @if($order->payment)
                        <div class="text-xs space-y-0.5 mb-2">
                            <div class="flex justify-between">
                                <span>Payment Method:</span>
                                <span class="font-semibold uppercase">{{ $order->payment->method }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Amount Paid:</span>
                                <span>GH₵{{ number_format($order->payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Change:</span>
                                <span>GH₵0.00</span>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-400 my-2"></div>
                    @endif

                    {{-- ========== FOOTER ========== --}}
                    <div class="text-center text-xs space-y-1 mt-3">
                        <div class="font-semibold">Thank you for shopping with us!</div>
                        <div class="text-gray-600 text-[10px] leading-tight">
                            Goods once sold are not returnable.<br>
                            Exchange within 7 days with receipt.<br>
                            Electrical items warranty as per manufacturer.
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-400 my-3"></div>

                    {{-- ========== RECEIPT END ========== --}}
                    <div class="text-center text-[10px] text-gray-500">
                        <div>*** END OF RECEIPT ***</div>
                        <div class="mt-1">{{ $order->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Thermal Receipt Styling */
        .receipt-content {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        /* Print Styles for 80mm Thermal Printer */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            html, body {
                width: 80mm;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            /* Hide everything except receipt */
            nav,
            header,
            .print\\:hidden,
            [class*="print:hidden"] {
                display: none !important;
            }

            /* Receipt container */
            #thermal-receipt {
                width: 80mm !important;
                max-width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
            }

            .receipt-content {
                width: 80mm !important;
                max-width: 80mm !important;
                padding: 2mm 3mm !important;
                font-size: 11px !important;
            }

            /* Ensure monospace font */
            .receipt-content,
            .receipt-content * {
                font-family: 'Courier New', Courier, monospace !important;
            }

            /* Hide screen-only elements */
            .py-8 > div > div:first-child {
                display: none !important;
            }

            /* Adjust spacing */
            .receipt-content .border-t {
                border-color: #000 !important;
            }

            /* Ensure text is black */
            .receipt-content,
            .receipt-content * {
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Screen preview styling */
        @media screen {
            #thermal-receipt {
                margin: 0 auto;
                max-width: 320px;
            }

            .receipt-content {
                width: 100% !important;
                max-width: 320px;
            }
        }
    </style>
</x-app-layout>
