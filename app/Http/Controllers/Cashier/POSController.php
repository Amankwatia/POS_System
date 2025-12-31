<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class POSController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        return view('cashier.pos', [
            'products' => Product::available()
                ->select(['id', 'name', 'sku', 'price', 'stock']) // Only needed columns
                ->orderBy('name')
                ->get(),
            'todaySales' => Payment::completed()->today()->forUser($userId)->sum('amount'),
            'todayTransactions' => Order::completed()->today()->forUser($userId)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,mobile',
            'amount_tendered' => 'nullable|numeric|min:0',
        ]);

        try {
            $result = DB::transaction(function () use ($validated) {
                $itemsData = $this->prepareOrderItems($validated['items']);
                $subtotal = collect($itemsData)->sum('total');

                // Calculate 15% VAT
                $taxRate = 0.15;
                $tax = round($subtotal * $taxRate, 2);
                $total = $subtotal + $tax;

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'status' => Order::STATUS_COMPLETED,
                ]);

                $this->createOrderItems($order, $itemsData);

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $total,
                    'method' => $validated['payment_method'],
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                ]);

                return ['order' => $order, 'total' => $total];
            });

            $change = $this->calculateChange(
                $validated['payment_method'],
                $validated['amount_tendered'] ?? 0,
                $result['total']
            );

            return redirect()->route('cashier.pos')->with([
                'status' => 'Sale completed successfully!',
                'receipt' => [
                    'order_id' => $result['order']->id,
                    'total' => $result['total'],
                    'amount_tendered' => $validated['amount_tendered'] ?? 0,
                    'change' => $change,
                ],
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    public function transactions(): View
    {
        $userId = auth()->id();

        return view('cashier.transactions', [
            'transactions' => Order::with(['items.product:id,name,sku,price', 'payment'])
                ->withCount('items')
                ->forUser($userId)
                ->latest()
                ->paginate(20),
            'todaySales' => Payment::completed()->today()->forUser($userId)->sum('amount'),
        ]);
    }

    public function receipt(Order $order): View
    {
        $order->load([
            'items.product:id,name,sku,price',
            'payment',
            'user:id,name,email'
        ]);

        return view('cashier.receipt', compact('order'));
    }

    /**
     * Prepare order items with batch loading to prevent N+1 queries.
     * Previously: each item queried Product::findOrFail() in a loop.
     * Now: loads all products in one query and validates in memory.
     */
    private function prepareOrderItems(array $items): array
    {
        // Extract all unique product IDs
        $productIds = collect($items)->pluck('product_id')->unique()->values();

        // Batch load all products in one query
        $products = Product::whereIn('id', $productIds)
            ->select(['id', 'name', 'price', 'stock'])
            ->get()
            ->keyBy('id');

        $itemsData = [];

        foreach ($items as $item) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                throw new \Exception("Product not found");
            }

            if (!$product->hasStock($item['quantity'])) {
                throw new \Exception("Insufficient stock for {$product->name}");
            }

            $itemsData[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'total' => $product->price * $item['quantity'],
            ];
        }

        return $itemsData;
    }

    /**
     * Create order items and decrement stock.
     * Uses batch insert for order items, individual decrements for accurate stock tracking.
     */
    private function createOrderItems(Order $order, array $itemsData): void
    {
        $orderItems = [];
        $now = now();

        foreach ($itemsData as $data) {
            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $data['product']->id,
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_price'],
                'subtotal' => $data['total'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Decrement stock - must be individual for proper locking
            $data['product']->decrement('stock', $data['quantity']);
        }

        // Batch insert all order items
        OrderItem::insert($orderItems);
    }

    private function calculateChange(string $method, float $tendered, float $total): float
    {
        if ($method === Payment::METHOD_CASH && $tendered > 0) {
            return max(0, $tendered - $total);
        }
        return 0;
    }
}
