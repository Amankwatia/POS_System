<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $products = Product::query()
            ->search($search)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Product::create($data);

        return back()->with('status', 'Product created');
    }

    /**
     * Get product data for editing (AJAX).
     */
    public function edit(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Handle checkbox for is_active
        $data['is_active'] = $request->has('is_active');

        $product->update($data);

        return back()->with('status', 'Product updated successfully');
    }

    /**
     * Delete the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Check if product has order items
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Cannot delete product with existing orders');
        }

        $product->delete();

        return back()->with('status', 'Product deleted successfully');
    }

    /**
     * Export products to CSV or Excel.
     * Uses cursor-based iteration for memory efficiency with large datasets.
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        $search = $request->input('search');

        $query = Product::query()
            ->forExport()
            ->search($search)
            ->orderBy('name');

        $filename = 'products_stock_' . date('Y-m-d_His');

        if ($format === 'excel') {
            return $this->exportExcel($query, $filename);
        }

        return $this->exportCsv($query, $filename);
    }

    /**
     * Export products as CSV using cursor for memory efficiency.
     */
    private function exportCsv($query, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'SKU',
                'Product Name',
                'Description',
                'Price',
                'Stock Quantity',
                'Reorder Level',
                'Status',
                'Stock Status',
                'Created At',
                'Updated At'
            ]);

            // Use cursor for memory-efficient iteration
            foreach ($query->cursor() as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->description ?? '',
                    number_format($product->price, 2),
                    $product->stock,
                    $product->reorder_level,
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->getStockStatus(),
                    $product->created_at->format('Y-m-d H:i:s'),
                    $product->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export products as Excel using cursor for memory efficiency.
     */
    private function exportExcel($query, $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($file, [
                'SKU',
                'Product Name',
                'Description',
                'Price',
                'Stock Quantity',
                'Reorder Level',
                'Status',
                'Stock Status',
                'Created At',
                'Updated At'
            ], "\t");

            // Use cursor for memory-efficient iteration
            foreach ($query->cursor() as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->description ?? '',
                    number_format($product->price, 2),
                    $product->stock,
                    $product->reorder_level,
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->getStockStatus(),
                    $product->created_at->format('Y-m-d H:i:s'),
                    $product->updated_at->format('Y-m-d H:i:s')
                ], "\t");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
