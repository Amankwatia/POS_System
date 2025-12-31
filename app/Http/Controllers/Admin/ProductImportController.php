<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProductImportController extends Controller
{
    /**
     * Show the import form.
     */
    public function index(): View
    {
        return view('admin.products.import');
    }

    /**
     * Download a sample CSV template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
        ];

        $columns = ['name', 'sku', 'description', 'price', 'stock', 'reorder_level'];
        
        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Write header row
            fputcsv($file, $columns);
            
            // Write sample data rows
            fputcsv($file, ['LED Bulb 9W', 'LED-BULB-9W', 'Energy efficient LED bulb', '15.99', '100', '10']);
            fputcsv($file, ['Extension Cable 5m', 'EXT-CABLE-5M', '5 meter extension cable with 4 sockets', '45.00', '50', '5']);
            fputcsv($file, ['Wall Socket Double', 'SOCK-DBL-01', 'Double wall socket with USB ports', '35.50', '75', '15']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process the uploaded CSV file.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // Max 5MB
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Read and parse CSV
        $rows = [];
        $errors = [];
        $successCount = 0;
        $skipCount = 0;
        $lineNumber = 0;

        if (($handle = fopen($path, 'r')) !== false) {
            // Get header row
            $header = fgetcsv($handle);
            
            if (!$header) {
                return back()->with('error', 'Could not read CSV file. Please check the file format.');
            }

            // Normalize headers (lowercase, trim)
            $header = array_map(fn($h) => strtolower(trim($h)), $header);

            // Validate required columns exist
            $requiredColumns = ['name', 'sku', 'price', 'stock'];
            $missingColumns = array_diff($requiredColumns, $header);
            
            if (!empty($missingColumns)) {
                fclose($handle);
                return back()->with('error', 'Missing required columns: ' . implode(', ', $missingColumns));
            }

            // Process each row
            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Map row to associative array
                $data = [];
                foreach ($header as $index => $column) {
                    $data[$column] = isset($row[$index]) ? trim($row[$index]) : null;
                }

                // Validate row data
                $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'sku' => ['required', 'string', 'max:100'],
                    'price' => ['required', 'numeric', 'min:0'],
                    'stock' => ['required', 'integer', 'min:0'],
                    'reorder_level' => ['nullable', 'integer', 'min:0'],
                    'description' => ['nullable', 'string'],
                ]);

                if ($validator->fails()) {
                    $errors[] = "Row {$lineNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Check for duplicate SKU
                if (Product::where('sku', $data['sku'])->exists()) {
                    $skipCount++;
                    $errors[] = "Row {$lineNumber}: SKU '{$data['sku']}' already exists (skipped)";
                    continue;
                }

                $rows[] = [
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'description' => $data['description'] ?? null,
                    'price' => (float) $data['price'],
                    'stock' => (int) $data['stock'],
                    'reorder_level' => isset($data['reorder_level']) && $data['reorder_level'] !== '' 
                        ? (int) $data['reorder_level'] 
                        : 5,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            fclose($handle);
        }

        // Insert valid rows in a transaction
        if (!empty($rows)) {
            try {
                DB::beginTransaction();
                
                // Insert in chunks to handle large files
                foreach (array_chunk($rows, 100) as $chunk) {
                    Product::insert($chunk);
                    $successCount += count($chunk);
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Product import failed: ' . $e->getMessage());
                return back()->with('error', 'Import failed: ' . $e->getMessage());
            }
        }

        // Build result message
        $message = "{$successCount} products imported successfully.";
        
        if ($skipCount > 0) {
            $message .= " {$skipCount} skipped (duplicate SKU).";
        }

        if (!empty($errors)) {
            // Store errors in session for display
            session()->flash('import_errors', array_slice($errors, 0, 10)); // Show first 10 errors
            
            if (count($errors) > 10) {
                $message .= " " . (count($errors) - 10) . " more errors not shown.";
            }
        }

        if ($successCount > 0) {
            return redirect()->route('admin.products.index')->with('status', $message);
        }

        return back()->with('error', $message)->with('import_errors', $errors);
    }
}
