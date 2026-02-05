<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function complete(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selling_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer,credit',
        ]);

        $subtotal = 0;
        $items = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['id']);
            $quantity = $item['quantity'];
            $unitPrice = $item['selling_price'];
            $itemTotal = $unitPrice * $quantity;

            $subtotal += $itemTotal;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $itemTotal,
            ];

            // Update stock
            if ($product->track_stock) {
                $product->decrement('stock_quantity', $quantity);
            }
        }

        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $tax;

        $sale = Sale::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id() ?? 1,
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'status' => 'completed',
        ]);

        foreach ($items as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => 0,
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
