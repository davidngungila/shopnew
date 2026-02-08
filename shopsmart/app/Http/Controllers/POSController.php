<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $taxRate = (float) Setting::get('tax_rate', 0.10); // Default 10%
        
        return view('pos.index', compact('categories', 'customers', 'taxRate'));
    }

    public function complete(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer,credit',
            'notes' => 'nullable|string|max:500',
        ]);

        $subtotal = 0;
        $items = [];
        $taxRate = (float) Setting::get('tax_rate', 0.10);

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['id']);
            $quantity = $item['quantity'];
            $unitPrice = $item['selling_price'];
            $itemDiscount = $item['discount'] ?? 0;
            $itemSubtotal = $unitPrice * $quantity;
            $itemTotal = $itemSubtotal - $itemDiscount;

            $subtotal += $itemTotal;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $itemDiscount,
                'total' => $itemTotal,
            ];

            // Check stock availability
            if ($product->track_stock && $product->stock_quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}",
                ], 400);
            }

            // Update stock
            if ($product->track_stock) {
                $product->decrement('stock_quantity', $quantity);
            }
        }

        $discount = $validated['discount'] ?? 0;
        $subtotalAfterDiscount = $subtotal - $discount;
        $tax = $subtotalAfterDiscount * $taxRate;
        $total = $subtotalAfterDiscount + $tax;

        $sale = Sale::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'customer_id' => $validated['customer_id'] ?? null,
            'user_id' => auth()->id() ?? 1,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'status' => 'completed',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($items as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'],
                'total' => $item['total'],
            ]);
        }

        $sale->load(['customer', 'user', 'items.product']);
        
        return response()->json([
            'success' => true,
            'sale' => $sale,
            'invoice_number' => $sale->invoice_number,
            'message' => 'Sale completed successfully',
        ]);
    }
}
