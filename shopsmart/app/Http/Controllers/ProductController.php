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
}
