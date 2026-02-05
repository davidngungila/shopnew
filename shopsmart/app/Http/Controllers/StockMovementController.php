<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with(['product', 'warehouse', 'user'])->latest()->paginate(20);
        return view('stock-movements.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('stock-movements.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'type' => 'required|in:in,out,return,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id() ?? 1;
        StockMovement::create($validated);

        // Update product stock
        $product = Product::findOrFail($validated['product_id']);
        if ($validated['type'] === 'in' || $validated['type'] === 'return') {
            $product->increment('stock_quantity', $validated['quantity']);
        } else {
            $product->decrement('stock_quantity', $validated['quantity']);
        }

        return redirect()->route('stock-movements.index')->with('success', 'Stock movement recorded successfully.');
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'warehouse', 'user']);
        return view('stock-movements.show', compact('stockMovement'));
    }

    public function edit(StockMovement $stockMovement)
    {
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('stock-movements.edit', compact('stockMovement', 'products', 'warehouses'));
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'type' => 'required|in:in,out,return,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Store old values for stock adjustment
        $oldType = $stockMovement->type;
        $oldQuantity = $stockMovement->quantity;
        $oldProductId = $stockMovement->product_id;

        // Update the movement
        $stockMovement->update($validated);

        // Revert old stock change
        $oldProduct = Product::find($oldProductId);
        if ($oldProduct) {
            if ($oldType === 'in' || $oldType === 'return') {
                $oldProduct->decrement('stock_quantity', $oldQuantity);
            } else {
                $oldProduct->increment('stock_quantity', $oldQuantity);
            }
        }

        // Apply new stock change
        $product = Product::findOrFail($validated['product_id']);
        if ($validated['type'] === 'in' || $validated['type'] === 'return') {
            $product->increment('stock_quantity', $validated['quantity']);
        } else {
            $product->decrement('stock_quantity', $validated['quantity']);
        }

        return redirect()->route('stock-movements.show', $stockMovement)->with('success', 'Stock movement updated successfully.');
    }

    public function destroy(StockMovement $stockMovement)
    {
        $stockMovement->delete();
        return redirect()->route('stock-movements.index')->with('success', 'Stock movement deleted successfully.');
    }
}
