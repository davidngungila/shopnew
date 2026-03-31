<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(20);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Warehouse::create($validated);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($validated);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }

    public function transferStockPage()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = \App\Models\Product::with(['warehouse', 'category'])->where('is_active', true)->get();
        return view('warehouses.transfer-stock', compact('warehouses', 'products'));
    }

    public function transferStock(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        try {
            $product = \App\Models\Product::findOrFail($validated['product_id']);
            $fromWarehouse = Warehouse::findOrFail($validated['from_warehouse_id']);
            $toWarehouse = Warehouse::findOrFail($validated['to_warehouse_id']);

            // Check if product has enough stock
            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'error' => 'Insufficient stock available. Current stock: ' . $product->stock_quantity
                ], 400);
            }

            // Create stock out record from source warehouse
            \App\Models\StockMovement::create([
                'type' => 'out',
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'warehouse_id' => $validated['from_warehouse_id'],
                'notes' => ($validated['notes'] ?? '') . " - Transfer to {$toWarehouse->name}",
                'user_id' => auth()->id()
            ]);

            // Create stock in record to destination warehouse
            \App\Models\StockMovement::create([
                'type' => 'in',
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'warehouse_id' => $validated['to_warehouse_id'],
                'notes' => ($validated['notes'] ?? '') . " - Transfer from {$fromWarehouse->name}",
                'user_id' => auth()->id()
            ]);

            // Update product stock
            $product->decrement('stock_quantity', $validated['quantity']);

            return response()->json([
                'success' => true,
                'message' => "Successfully transferred {$validated['quantity']} units of {$product->name} from {$fromWarehouse->name} to {$toWarehouse->name}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function capacityReportPage()
    {
        $warehouses = Warehouse::with(['products' => function($query) {
            $query->where('is_active', true);
        }])->get();

        $warehouseStats = $warehouses->map(function($warehouse) {
            $totalProducts = $warehouse->products->count();
            $totalStock = $warehouse->products->sum('stock_quantity');
            $totalValue = $warehouse->products->sum(function($product) {
                return $product->stock_quantity * $product->selling_price;
            });
            $lowStockItems = $warehouse->products->filter(function($product) {
                return $product->stock_quantity <= $product->low_stock_alert;
            })->count();

            return [
                'warehouse' => $warehouse,
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
                'total_value' => $totalValue,
                'low_stock_items' => $lowStockItems,
                'capacity_utilization' => $warehouse->capacity ? ($totalStock / $warehouse->capacity) * 100 : 0
            ];
        });

        return view('warehouses.capacity-report', compact('warehouseStats'));
    }

    public function manageLocationsPage()
    {
        $warehouses = Warehouse::with('locations')->where('is_active', true)->get();
        return view('warehouses.manage-locations', compact('warehouses'));
    }

    public function manageLocations(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'action' => 'required|in:add_location,remove_location,update_capacity',
            'location_name' => 'required_if:action,add_location|string|max:255',
            'location_id' => 'required_if:action,remove_location|integer',
            'capacity' => 'required_if:action,update_capacity|integer|min:1'
        ]);

        try {
            $warehouse = Warehouse::findOrFail($validated['warehouse_id']);

            switch ($validated['action']) {
                case 'add_location':
                    $location = WarehouseLocation::create([
                        'warehouse_id' => $validated['warehouse_id'],
                        'name' => $validated['location_name'],
                        'location_type' => $request->input('location_type', 'aisle'),
                        'aisle' => $request->input('aisle'),
                        'rack' => $request->input('rack'),
                        'shelf' => $request->input('shelf'),
                        'bin' => $request->input('bin'),
                        'section' => $request->input('section'),
                        'zone' => $request->input('zone'),
                        'description' => $request->input('description'),
                        'is_active' => true,
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => "Location '{$validated['location_name']}' added to {$warehouse->name}"
                    ]);

                case 'remove_location':
                    $location = WarehouseLocation::findOrFail($validated['location_id']);
                    $location->delete();
                    
                    return response()->json([
                        'success' => true,
                        'message' => "Location '{$location->name}' removed from {$warehouse->name}"
                    ]);

                case 'update_capacity':
                    $warehouse->update(['capacity' => $validated['capacity']]);
                    return response()->json([
                        'success' => true,
                        'message' => "Capacity updated for {$warehouse->name}"
                    ]);

                default:
                    return response()->json(['success' => false, 'error' => 'Invalid action'], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Operation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
