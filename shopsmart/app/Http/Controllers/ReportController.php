<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $sales = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->with('customer')
            ->latest()
            ->get();

        $totalSales = $sales->sum('total');
        $totalOrders = $sales->count();
        $averageOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Sales by payment method
        $salesByPayment = Sale::select('payment_method', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        // Daily sales
        $dailySales = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.sales', compact('sales', 'totalSales', 'totalOrders', 'averageOrder', 'salesByPayment', 'dailySales', 'dateFrom', 'dateTo'));
    }

    public function purchases(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $purchases = Purchase::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->with('supplier')
            ->latest()
            ->get();

        $totalPurchases = $purchases->sum('total');
        $totalOrders = $purchases->count();
        $averageOrder = $totalOrders > 0 ? $totalPurchases / $totalOrders : 0;

        // Purchases by supplier
        $purchasesBySupplier = Purchase::select('supplier_id', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->with('supplier')
            ->groupBy('supplier_id')
            ->get();

        return view('reports.purchases', compact('purchases', 'totalPurchases', 'totalOrders', 'averageOrder', 'purchasesBySupplier', 'dateFrom', 'dateTo'));
    }

    public function inventory(Request $request)
    {
        $products = Product::with('category')->get();

        $totalProducts = $products->count();
        $totalStockValue = $products->sum(function($product) {
            return $product->stock_quantity * $product->purchase_price;
        });
        $lowStockCount = $products->where('stock_quantity', '<=', DB::raw('low_stock_alert'))->count();
        $outOfStockCount = $products->where('stock_quantity', '<=', 0)->count();

        // Products by category
        $productsByCategory = Product::select('category_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(stock_quantity * purchase_price) as value'))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        return view('reports.inventory', compact('products', 'totalProducts', 'totalStockValue', 'lowStockCount', 'outOfStockCount', 'productsByCategory'));
    }

    public function customerStatement(Request $request, Customer $customer)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $sales = Sale::where('customer_id', $customer->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('items.product')
            ->latest()
            ->get();

        $totalSales = $sales->sum('total');
        $totalPaid = $sales->sum('paid_amount');
        $totalDue = $sales->sum('due_amount');

        return view('reports.customer-statement', compact('customer', 'sales', 'totalSales', 'totalPaid', 'totalDue', 'dateFrom', 'dateTo'));
    }

    public function supplierStatement(Request $request, Supplier $supplier)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $purchases = Purchase::where('supplier_id', $supplier->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('items.product')
            ->latest()
            ->get();

        $totalPurchases = $purchases->sum('total');
        $totalPaid = $purchases->sum('paid_amount');
        $totalDue = $purchases->sum('due_amount');

        return view('reports.supplier-statement', compact('supplier', 'purchases', 'totalPurchases', 'totalPaid', 'totalDue', 'dateFrom', 'dateTo'));
    }
}
