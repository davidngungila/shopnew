<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('format') && $request->format === 'json') {
            $products = Product::where('is_active', true)
                ->with(['category', 'warehouse'])
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'category_id' => $product->category_id,
                        'selling_price' => (float) $product->selling_price,
                        'stock_quantity' => $product->stock_quantity,
                        'low_stock_alert' => $product->low_stock_alert,
                        'track_stock' => $product->track_stock,
                        'image' => $product->image,
                        'unit' => $product->unit,
                    ];
                });
            return response()->json(['products' => $products]);
        }
        
        $products = Product::with(['category', 'warehouse'])->latest()->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('products.create', compact('categories', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'unit' => 'required|string',
            'track_stock' => 'boolean',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'warehouse']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories', 'warehouses'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'unit' => 'required|string',
            'track_stock' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function lowStock()
    {
        $products = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
            ->where('is_active', true)
            ->with(['category', 'warehouse'])
            ->latest()
            ->paginate(20);
        return view('products.low-stock', compact('products'));
    }

    public function bulkOperationsPage()
    {
        $products = Product::with(['category', 'warehouse'])->latest()->paginate(50);
        return view('products.bulk-operations', compact('products'));
    }

    public function bulkOperations(Request $request)
    {
        $action = $request->input('action');
        $productIds = $request->input('products', []);

        if (empty($productIds)) {
            return response()->json(['error' => 'No products selected'], 400);
        }

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = 'Products activated successfully.';
                break;
            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = 'Products deactivated successfully.';
                break;
            case 'delete':
                Product::whereIn('id', $productIds)->delete();
                $message = 'Products deleted successfully.';
                break;
            case 'update_price':
                $priceType = $request->input('price_type');
                $priceValue = $request->input('price_value');
                
                if ($priceType === 'percentage') {
                    Product::whereIn('id', $productIds)->each(function($product) use ($priceValue) {
                        $product->selling_price = $product->selling_price * (1 + ($priceValue / 100));
                        $product->save();
                    });
                } else {
                    Product::whereIn('id', $productIds)->update(['selling_price' => $priceValue]);
                }
                $message = 'Product prices updated successfully.';
                break;
            case 'update_stock':
                $stockAction = $request->input('stock_action');
                $stockValue = $request->input('stock_value');
                
                Product::whereIn('id', $productIds)->each(function($product) use ($stockAction, $stockValue) {
                    if ($stockAction === 'add') {
                        $product->stock_quantity += $stockValue;
                    } elseif ($stockAction === 'subtract') {
                        $product->stock_quantity = max(0, $product->stock_quantity - $stockValue);
                    } else {
                        $product->stock_quantity = $stockValue;
                    }
                    $product->save();
                });
                $message = 'Product stock updated successfully.';
                break;
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function stockMovementsPage()
    {
        $stockMovements = \App\Models\StockMovement::with(['product', 'warehouse'])
            ->latest()
            ->paginate(50);
        return view('products.stock-movements', compact('stockMovements'));
    }

    public function lowStockPage()
    {
        $products = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
            ->where('is_active', true)
            ->with(['category', 'warehouse'])
            ->latest()
            ->paginate(20);
        return view('products.low-stock-page', compact('products'));
    }

    public function importPage()
    {
        return view('products.import');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
        ]);

        $file = $request->file('file');
        $imported = 0;
        $errors = [];

        try {
            if ($file->getClientOriginalExtension() === 'csv') {
                $handle = fopen($file->getPathname(), 'r');
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    try {
                        Product::create([
                            'name' => $row[0] ?? '',
                            'sku' => $row[1] ?? '',
                            'selling_price' => $row[2] ?? 0,
                            'stock_quantity' => $row[3] ?? 0,
                            'category_id' => $row[4] ?? null,
                            'is_active' => filter_var($row[5] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Error importing row: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }
            
            return response()->json([
                'success' => true, 
                'message' => "Successfully imported {$imported} products.",
                'imported' => $imported,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    public function downloadSampleExcel()
    {
        // Create sample data
        $sampleData = [
            ['Name', 'SKU', 'Selling Price', 'Stock Quantity', 'Category ID', 'Status'],
            ['Laptop Pro 15', 'LP-001', 1500000, 25, 1, 'true'],
            ['Wireless Mouse', 'WM-002', 45000, 100, 1, 'true'],
            ['USB Cable', 'UC-003', 5000, 200, 2, 'true'],
            ['Monitor 24"', 'MN-004', 850000, 15, 1, 'false']
        ];

        // Create CSV content
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        $filename = "sample_products_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response($csv, 200, $headers);
    }

    public function exportProducts()
    {
        $products = Product::with(['category', 'warehouse'])->get();
        
        $filename = "products_export_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'SKU', 'Selling Price', 'Cost Price', 'Stock Quantity', 'Low Stock Alert', 'Category', 'Warehouse', 'Unit', 'Status', 'Barcode', 'Description']);
            
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->sku ?? '',
                    $product->selling_price,
                    $product->cost_price ?? 0,
                    $product->stock_quantity,
                    $product->low_stock_alert,
                    $product->category->name ?? '',
                    $product->warehouse->name ?? '',
                    $product->unit ?? '',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->barcode ?? '',
                    $product->description ?? ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
