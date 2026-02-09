<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Quotation;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'customers' => [],
                'sales' => [],
                'quotations' => [],
                'purchases' => [],
                'suppliers' => [],
                'categories' => [],
                'warehouses' => [],
            ]);
        }

        $results = [
            'products' => $this->searchProducts($query),
            'customers' => $this->searchCustomers($query),
            'sales' => $this->searchSales($query),
            'quotations' => $this->searchQuotations($query),
            'purchases' => $this->searchPurchases($query),
            'suppliers' => $this->searchSuppliers($query),
            'categories' => $this->searchCategories($query),
            'warehouses' => $this->searchWarehouses($query),
        ];

        return response()->json($results);
    }

    private function searchProducts($query)
    {
        return Product::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => number_format($product->selling_price, 2),
                    'url' => route('products.show', $product),
                    'type' => 'product',
                    'icon' => '📦',
                ];
            });
    }

    private function searchCustomers($query)
    {
        return Customer::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'url' => route('customers.show', $customer),
                    'type' => 'customer',
                    'icon' => '👤',
                ];
            });
    }

    private function searchSales($query)
    {
        return Sale::where('invoice_number', 'like', "%{$query}%")
            ->orWhere('id', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->with('customer')
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'customer' => $sale->customer->name ?? 'N/A',
                    'total' => number_format($sale->total, 2),
                    'url' => route('sales.show', $sale),
                    'type' => 'sale',
                    'icon' => '💰',
                ];
            });
    }

    private function searchQuotations($query)
    {
        return Quotation::where('quotation_number', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->with('customer')
            ->get()
            ->map(function ($quotation) {
                return [
                    'id' => $quotation->id,
                    'quotation_number' => $quotation->quotation_number,
                    'customer' => $quotation->customer->name ?? 'N/A',
                    'total' => number_format($quotation->total ?? 0, 2),
                    'url' => route('quotations.show', $quotation),
                    'type' => 'quotation',
                    'icon' => '📄',
                ];
            });
    }

    private function searchPurchases($query)
    {
        return Purchase::where('purchase_number', 'like', "%{$query}%")
            ->orWhereHas('supplier', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->with('supplier')
            ->get()
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->id,
                    'purchase_number' => $purchase->purchase_number,
                    'supplier' => $purchase->supplier->name ?? 'N/A',
                    'total' => number_format($purchase->total ?? 0, 2),
                    'url' => route('purchases.show', $purchase),
                    'type' => 'purchase',
                    'icon' => '🛒',
                ];
            });
    }

    private function searchSuppliers($query)
    {
        return Supplier::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'url' => route('suppliers.show', $supplier),
                    'type' => 'supplier',
                    'icon' => '🏢',
                ];
            });
    }

    private function searchCategories($query)
    {
        return Category::where('name', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(5)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'url' => route('categories.show', $category),
                    'type' => 'category',
                    'icon' => '🏷️',
                ];
            });
    }

    private function searchWarehouses($query)
    {
        return Warehouse::where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(5)
            ->get()
            ->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'address' => $warehouse->address,
                    'url' => route('warehouses.show', $warehouse),
                    'type' => 'warehouse',
                    'icon' => '🏭',
                ];
            });
    }
}





