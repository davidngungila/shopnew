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

    public function addMovement(Request $request)
    {
        $validated = $request->validate([
            'movement_type' => 'required|in:in,out,transfer,adjustment',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'from_warehouse_id' => 'nullable|exists:warehouses,id',
            'to_warehouse_id' => 'nullable|exists:warehouses,id',
            'notes' => 'nullable|string',
            'reason' => 'nullable|string'
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);
            
            // Create stock movement record
            $movement = StockMovement::create([
                'movement_type' => $validated['movement_type'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'warehouse_id' => $validated['warehouse_id'] ?? $product->warehouse_id,
                'from_warehouse_id' => $validated['from_warehouse_id'] ?? null,
                'to_warehouse_id' => $validated['to_warehouse_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'reason' => $validated['reason'] ?? null,
                'user_id' => auth()->id(),
                'reference_type' => 'manual',
                'reference_id' => 0
            ]);

            // Update product stock
            if ($validated['movement_type'] === 'in') {
                $product->increment('stock_quantity', $validated['quantity']);
            } elseif ($validated['movement_type'] === 'out') {
                $product->decrement('stock_quantity', $validated['quantity']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Stock movement recorded successfully.',
                'movement' => $movement
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to record stock movement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export()
    {
        $movements = StockMovement::with(['product', 'warehouse', 'user'])->get();
        
        $filename = "stock_movements_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($movements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Product', 'Type', 'Quantity', 'Warehouse', 'Reference', 'Notes', 'User']);
            
            foreach ($movements as $movement) {
                fputcsv($file, [
                    $movement->created_at->format('Y-m-d H:i:s'),
                    $movement->product->name,
                    $movement->movement_type,
                    $movement->quantity,
                    $movement->warehouse->name ?? 'N/A',
                    ($movement->reference_type ?? 'manual') . ' #' . ($movement->reference_id ?? 0),
                    $movement->notes ?? '',
                    $movement->user->name ?? 'System'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
