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
            'products' => Product::available()->orderBy('name')->get(),
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

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'subtotal' => $subtotal,
                    'tax' => 0,
                    'total' => $subtotal,
                    'status' => Order::STATUS_COMPLETED,
                ]);

                $this->createOrderItems($order, $itemsData);

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $subtotal,
                    'method' => $validated['payment_method'],
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                ]);

                return ['order' => $order, 'subtotal' => $subtotal];
            });

            $change = $this->calculateChange(
                $validated['payment_method'],
                $validated['amount_tendered'] ?? 0,
                $result['subtotal']
            );

            return redirect()->route('cashier.pos')->with([
                'status' => 'Sale completed successfully!',
                'receipt' => [
                    'order_id' => $result['order']->id,
                    'total' => $result['subtotal'],
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
            'transactions' => Order::with(['items.product', 'payment'])
                ->forUser($userId)
                ->latest()
                ->paginate(20),
            'todaySales' => Payment::completed()->today()->forUser($userId)->sum('amount'),
        ]);
    }

    public function receipt(Order $order): View
    {
        $order->load(['items.product', 'payment', 'user']);

        return view('cashier.receipt', compact('order'));
    }

    private function prepareOrderItems(array $items): array
    {
        $itemsData = [];

        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

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

    private function createOrderItems(Order $order, array $itemsData): void
    {
        foreach ($itemsData as $data) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $data['product']->id,
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_price'],
                'total' => $data['total'],
            ]);

            $data['product']->decrement('stock', $data['quantity']);
        }
    }

    private function calculateChange(string $method, float $tendered, float $total): float
    {
        if ($method === Payment::METHOD_CASH && $tendered > 0) {
            return max(0, $tendered - $total);
        }
        return 0;
    }
}
