<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Quotation;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Set timezone to Tanzania
            $now = Carbon::now('Africa/Dar_es_Salaam');
            $today = $now->copy()->startOfDay();
            $yesterday = $now->copy()->subDay()->startOfDay();
            $thisMonth = $now->copy()->startOfMonth();
            $lastMonth = $now->copy()->subMonth()->startOfMonth();
            $thisYear = $now->copy()->startOfYear();
            $last7Days = $now->copy()->subDays(7);
            $last30Days = $now->copy()->subDays(30);

            // Sales Statistics
            $totalSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $last30Days)
                ->sum('total') ?? 0;
            
            $todaySales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $today)
                ->sum('total') ?? 0;
            
            $yesterdaySales = Sale::where('status', 'completed')
                ->whereBetween('created_at', [$yesterday, $today])
                ->sum('total') ?? 0;
            
            $thisMonthSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total') ?? 0;
            
            $lastMonthSales = Sale::where('status', 'completed')
                ->whereBetween('created_at', [$lastMonth, $thisMonth])
                ->sum('total') ?? 0;

            // Calculate growth rates
            $salesGrowth = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;
            
            // Order Statistics
            $todayOrders = Sale::where('status', 'completed')
                ->where('created_at', '>=', $today)
                ->count() ?? 0;
            
            $pendingOrders = Sale::where('status', 'pending')
                ->count() ?? 0;

            // Customer Statistics
            $totalCustomers = Customer::where('is_active', true)->count() ?? 0;
            $newCustomersToday = Customer::where('is_active', true)
                ->where('created_at', '>=', $today)
                ->count() ?? 0;
            $activeCustomers = Customer::where('is_active', true)
                ->whereHas('sales', function($query) use ($last30Days) {
                    $query->where('created_at', '>=', $last30Days);
                })
                ->count() ?? 0;

            // Product Statistics
            $totalProducts = Product::where('is_active', true)->count() ?? 0;
            $lowStockCount = Product::where('is_active', true)
                ->where('track_stock', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_alert')
                ->where('stock_quantity', '>', 0)
                ->count() ?? 0;
            $outOfStockCount = Product::where('is_active', true)
                ->where('track_stock', true)
                ->where('stock_quantity', '<=', 0)
                ->count() ?? 0;
            $activeProducts = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->count() ?? 0;

            // Financial Statistics
            $profit = $thisMonthSales * 0.3; // Assuming 30% profit margin
            $profitMargin = 30.0;
            $totalStockValue = Product::where('is_active', true)
                ->sum(DB::raw('stock_quantity * cost_price')) ?? 0;
            $avgOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;

            // Quotation Statistics
            $pendingQuotations = Quotation::where('status', 'pending')
                ->count() ?? 0;
            $quotationValue = Quotation::where('status', 'approved')
                ->sum('total') ?? 0;

            // Top Products
            $topProducts = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select('products.name', 'products.sku', 
                    DB::raw('SUM(sale_items.quantity) as total_quantity'),
                    DB::raw('SUM(sale_items.quantity * sale_items.price) as total_revenue'))
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->get();

            // Top Customers
            $topCustomers = DB::table('sales')
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select('customers.name', 'customers.phone',
                    DB::raw('COUNT(sales.id) as order_count'),
                    DB::raw('SUM(sales.total) as total_spent'))
                ->groupBy('customers.id', 'customers.name', 'customers.phone')
                ->orderBy('total_spent', 'desc')
                ->limit(5)
                ->get();

            // Recent Sales
            $recentSales = Sale::with('customer')
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Additional Advanced Metrics
            $conversionRate = 85.5; // Mock data
            $returnsCount = 12; // Mock data
            $totalExpenses = Expense::where('created_at', '>=', $thisMonth)->sum('amount') ?? 0;
            $netProfit = $thisMonthSales - $totalExpenses;

            // Stock Movement Statistics
            $totalMovements = StockMovement::count();
            
            // Last 30 days movements
            $last30Days = $now->copy()->subDays(30);
            
            // Stock In statistics
            $stockInCount = StockMovement::whereIn('type', ['in', 'return'])
                ->where('created_at', '>=', $last30Days)
                ->count();
            
            // Stock Out statistics  
            $stockOutCount = StockMovement::where('type', 'out')
                ->where('created_at', '>=', $last30Days)
                ->count();
            
            // Previous month for comparison
            $previousMonthStart = $now->copy()->subMonth()->startOfMonth();
            $previousMonthEnd = $now->copy()->subMonth()->endOfMonth();
            
            $previousStockIn = StockMovement::whereIn('type', ['in', 'return'])
                ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                ->count();
            
            $previousStockOut = StockMovement::where('type', 'out')
                ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                ->count();
            
            // Calculate percentages
            $stockInPercentage = $previousStockIn > 0 
                ? round((($stockInCount - $previousStockIn) / $previousStockIn) * 100, 1)
                : 0;
                
            $stockOutPercentage = $previousStockOut > 0 
                ? round((($stockOutCount - $previousStockOut) / $previousStockOut) * 100, 1)
                : 0;
            
            // Active products with movements
            $activeProductsMovements = StockMovement::select('product_id')
                ->where('created_at', '>=', $last30Days)
                ->distinct()
                ->count();

            // Calculate total notifications (low stock + out of stock)
            $totalNotifications = $lowStockCount + $outOfStockCount;

            return view('dashboard', compact(
                'now', 'todaySales', 'salesGrowth', 'todayOrders', 'pendingOrders',
                'totalCustomers', 'newCustomersToday', 'activeCustomers',
                'totalProducts', 'lowStockCount', 'outOfStockCount', 'activeProducts',
                'profit', 'profitMargin', 'totalStockValue', 'avgOrderValue',
                'pendingQuotations', 'quotationValue',
                'topProducts', 'topCustomers', 'recentSales',
                'conversionRate', 'returnsCount', 'totalExpenses', 'netProfit',
                'totalMovements', 'stockInCount', 'stockOutCount', 
                'stockInPercentage', 'stockOutPercentage', 'activeProductsMovements',
                'totalNotifications'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            // Return default values if there's an error
            return view('dashboard', [
                'now' => Carbon::now('Africa/Dar_es_Salaam'),
                'todaySales' => 0, 'salesGrowth' => 0, 'todayOrders' => 0, 'pendingOrders' => 0,
                'totalCustomers' => 0, 'newCustomersToday' => 0, 'activeCustomers' => 0,
                'totalProducts' => 0, 'lowStockCount' => 0, 'outOfStockCount' => 0, 'activeProducts' => 0,
                'profit' => 0, 'profitMargin' => 0, 'totalStockValue' => 0, 'avgOrderValue' => 0,
                'pendingQuotations' => 0, 'quotationValue' => 0,
                'topProducts' => [], 'topCustomers' => [], 'recentSales' => [],
                'conversionRate' => 0, 'returnsCount' => 0, 'totalExpenses' => 0, 'netProfit' => 0,
                'totalMovements' => 0, 'stockInCount' => 0, 'stockOutCount' => 0,
                'stockInPercentage' => 0, 'stockOutPercentage' => 0, 'activeProductsMovements' => 0,
                'totalNotifications' => 0
            ]);
        }
    }

    public function advanced()
    {
        // For the advanced dashboard, use the advanced view with enhanced features
        try {
            // Set timezone to Tanzania
            $now = Carbon::now('Africa/Dar_es_Salaam');
            $today = $now->copy()->startOfDay();
            $yesterday = $now->copy()->subDay()->startOfDay();
            $thisMonth = $now->copy()->startOfMonth();
            $lastMonth = $now->copy()->subMonth()->startOfMonth();
            $thisYear = $now->copy()->startOfYear();
            $last7Days = $now->copy()->subDays(7);
            $last30Days = $now->copy()->subDays(30);

            // Sales Statistics
            $totalSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $last30Days)
                ->sum('total') ?? 0;
            
            $todaySales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $today)
                ->sum('total') ?? 0;
            
            $yesterdaySales = Sale::where('status', 'completed')
                ->whereBetween('created_at', [$yesterday, $today])
                ->sum('total') ?? 0;
            
            $thisMonthSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total') ?? 0;
            
            $lastMonthSales = Sale::where('status', 'completed')
                ->whereBetween('created_at', [$lastMonth, $thisMonth])
                ->sum('total') ?? 0;

            // Calculate growth rates
            $salesGrowth = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;
            
            // Order Statistics
            $todayOrders = Sale::where('status', 'completed')
                ->where('created_at', '>=', $today)
                ->count() ?? 0;
            
            $pendingOrders = Sale::where('status', 'pending')
                ->count() ?? 0;

            // Customer Statistics
            $totalCustomers = Customer::where('is_active', true)->count() ?? 0;
            $newCustomersToday = Customer::where('is_active', true)
                ->where('created_at', '>=', $today)
                ->count() ?? 0;
            $activeCustomers = Customer::where('is_active', true)
                ->whereHas('sales', function($query) use ($last30Days) {
                    $query->where('created_at', '>=', $last30Days);
                })
                ->count() ?? 0;

            // Product Statistics
            $totalProducts = Product::where('is_active', true)->count() ?? 0;
            $lowStockCount = Product::where('is_active', true)
                ->where('track_stock', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_alert')
                ->where('stock_quantity', '>', 0)
                ->count() ?? 0;
            $outOfStockCount = Product::where('is_active', true)
                ->where('track_stock', true)
                ->where('stock_quantity', '<=', 0)
                ->count() ?? 0;
            $activeProducts = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->count() ?? 0;

            // Financial Statistics
            $profit = $thisMonthSales * 0.3; // Assuming 30% profit margin
            $profitMargin = 30.0;
            $totalStockValue = Product::where('is_active', true)
                ->sum(DB::raw('stock_quantity * cost_price')) ?? 0;
            $avgOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;

            // Quotation Statistics
            $pendingQuotations = Quotation::where('status', 'pending')
                ->count() ?? 0;
            $quotationValue = Quotation::where('status', 'approved')
                ->sum('total') ?? 0;

            // Top Products
            $topProducts = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select('products.name', 'products.sku', 
                    DB::raw('SUM(sale_items.quantity) as total_quantity'),
                    DB::raw('SUM(sale_items.quantity * sale_items.price) as total_revenue'))
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->get();

            // Top Customers
            $topCustomers = DB::table('sales')
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select('customers.name', 'customers.phone',
                    DB::raw('COUNT(sales.id) as order_count'),
                    DB::raw('SUM(sales.total) as total_spent'))
                ->groupBy('customers.id', 'customers.name', 'customers.phone')
                ->orderBy('total_spent', 'desc')
                ->limit(5)
                ->get();

            // Recent Sales
            $recentSales = Sale::with('customer')
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Additional Advanced Metrics
            $conversionRate = 85.5; // Mock data
            $returnsCount = 12; // Mock data
            $totalExpenses = Expense::where('created_at', '>=', $thisMonth)->sum('amount') ?? 0;
            $netProfit = $thisMonthSales - $totalExpenses;

            return view('dashboard_advanced', compact(
                'now', 'todaySales', 'salesGrowth', 'todayOrders', 'pendingOrders',
                'totalCustomers', 'newCustomersToday', 'activeCustomers',
                'totalProducts', 'lowStockCount', 'outOfStockCount', 'activeProducts',
                'profit', 'profitMargin', 'totalStockValue', 'avgOrderValue',
                'pendingQuotations', 'quotationValue',
                'topProducts', 'topCustomers', 'recentSales',
                'conversionRate', 'returnsCount', 'totalExpenses', 'netProfit'
            ));
        } catch (\Exception $e) {
            \Log::error('Advanced Dashboard error: ' . $e->getMessage());
            // Return default values if there's an error
            return view('dashboard_advanced', [
                'now' => Carbon::now('Africa/Dar_es_Salaam'),
                'todaySales' => 0, 'salesGrowth' => 0, 'todayOrders' => 0, 'pendingOrders' => 0,
                'totalCustomers' => 0, 'newCustomersToday' => 0, 'activeCustomers' => 0,
                'totalProducts' => 0, 'lowStockCount' => 0, 'outOfStockCount' => 0, 'activeProducts' => 0,
                'profit' => 0, 'profitMargin' => 0, 'totalStockValue' => 0, 'avgOrderValue' => 0,
                'pendingQuotations' => 0, 'quotationValue' => 0,
                'topProducts' => [], 'topCustomers' => [], 'recentSales' => [],
                'conversionRate' => 0, 'returnsCount' => 0, 'totalExpenses' => 0, 'netProfit' => 0
            ]);
        }
    }

    public function professional()
    {
        try {
            // Set timezone to Tanzania
            $now = Carbon::now('Africa/Dar_es_Salaam');
            $today = $now->copy()->startOfDay();
            $yesterday = $now->copy()->subDay()->startOfDay();
            $thisMonth = $now->copy()->startOfMonth();
            $lastMonth = $now->copy()->subMonth()->startOfMonth();
            $last30Days = $now->copy()->subDays(30);

            // Sales Statistics
            $todaySales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $today)
                ->sum('total') ?? 0;
            
            $yesterdaySales = Sale::where('status', 'completed')
                ->whereBetween('created_at', [$yesterday, $today])
                ->sum('total') ?? 0;
            
            $thisMonthSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total') ?? 0;
            
            $lastMonthSales = Sale::where('status', 'completed')
                ->whereBetween('created_at', [$lastMonth, $thisMonth])
                ->sum('total') ?? 0;

            // Calculate growth rates
            $salesGrowth = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;
            
            // Order Statistics
            $todayOrders = Sale::where('status', 'completed')
                ->where('created_at', '>=', $today)
                ->count() ?? 0;
            
            $pendingOrders = Sale::where('status', 'pending')
                ->count() ?? 0;

            // Customer Statistics
            $totalCustomers = Customer::where('is_active', true)->count() ?? 0;
            $newCustomersToday = Customer::where('is_active', true)
                ->where('created_at', '>=', $today)
                ->count() ?? 0;
            $activeCustomers = Customer::where('is_active', true)
                ->whereHas('sales', function($query) use ($last30Days) {
                    $query->where('created_at', '>=', $last30Days);
                })
                ->count() ?? 0;

            // Product Statistics
            $totalProducts = Product::where('is_active', true)->count() ?? 0;
            $lowStockCount = Product::where('is_active', true)
                ->where('track_stock', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_alert')
                ->where('stock_quantity', '>', 0)
                ->count() ?? 0;
            $outOfStockCount = Product::where('is_active', true)
                ->where('track_stock', true)
                ->where('stock_quantity', '<=', 0)
                ->count() ?? 0;
            $activeProducts = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->count() ?? 0;

            // Financial Statistics
            $profit = $thisMonthSales * 0.3; // Assuming 30% profit margin
            $profitMargin = 30.0;
            $totalStockValue = Product::where('is_active', true)
                ->sum(DB::raw('stock_quantity * cost_price')) ?? 0;
            $avgOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;

            // Additional Professional Dashboard Metrics
            $productsSoldToday = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $today)
                ->sum('sale_items.quantity') ?? 0;

            // Low Stock Products for alerts
            $lowStockProducts = Product::where('is_active', true)
                ->where('track_stock', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_alert')
                ->where('stock_quantity', '>', 0)
                ->orderBy('stock_quantity', 'asc')
                ->limit(9)
                ->get();

            // Top Products
            $topProducts = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select('products.name', 'products.sku', 
                    DB::raw('SUM(sale_items.quantity) as total_quantity'),
                    DB::raw('SUM(sale_items.quantity * sale_items.price) as total_revenue'))
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->get();

            // Top Customers
            $topCustomers = DB::table('sales')
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select('customers.name', 'customers.phone',
                    DB::raw('COUNT(sales.id) as order_count'),
                    DB::raw('SUM(sales.total) as total_spent'))
                ->groupBy('customers.id', 'customers.name', 'customers.phone')
                ->orderBy('total_spent', 'desc')
                ->limit(5)
                ->get();

            // Recent Sales with items count
            $recentSales = Sale::with('customer')
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($sale) {
                    $sale->items_count = DB::table('sale_items')
                        ->where('sale_id', $sale->id)
                        ->sum('quantity') ?? 1;
                    return $sale;
                });

            // Additional metrics
            $conversionRate = 85.5; // Mock data
            $returnsCount = 12; // Mock data
            $totalExpenses = Expense::where('created_at', '>=', $thisMonth)->sum('amount') ?? 0;
            $netProfit = $thisMonthSales - $totalExpenses;

            return view('dashboard_professional', compact(
                'now', 'todaySales', 'salesGrowth', 'todayOrders', 'pendingOrders',
                'totalCustomers', 'newCustomersToday', 'activeCustomers',
                'totalProducts', 'lowStockCount', 'outOfStockCount', 'activeProducts',
                'profit', 'profitMargin', 'totalStockValue', 'avgOrderValue',
                'productsSoldToday', 'lowStockProducts',
                'topProducts', 'topCustomers', 'recentSales',
                'conversionRate', 'returnsCount', 'totalExpenses', 'netProfit',
                'thisMonthSales', 'lastMonthSales'
            ));
        } catch (\Exception $e) {
            \Log::error('Professional Dashboard error: ' . $e->getMessage());
            // Return default values if there's an error
            return view('dashboard_professional', [
                'now' => Carbon::now('Africa/Dar_es_Salaam'),
                'todaySales' => 0, 'salesGrowth' => 0, 'todayOrders' => 0, 'pendingOrders' => 0,
                'totalCustomers' => 0, 'newCustomersToday' => 0, 'activeCustomers' => 0,
                'totalProducts' => 0, 'lowStockCount' => 0, 'outOfStockCount' => 0, 'activeProducts' => 0,
                'profit' => 0, 'profitMargin' => 0, 'totalStockValue' => 0, 'avgOrderValue' => 0,
                'productsSoldToday' => 0, 'lowStockProducts' => [],
                'topProducts' => [], 'topCustomers' => [], 'recentSales' => [],
                'conversionRate' => 0, 'returnsCount' => 0, 'totalExpenses' => 0, 'netProfit' => 0,
                'thisMonthSales' => 0, 'lastMonthSales' => 0
            ]);
        }
    }
}
