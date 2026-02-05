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
            
            $thisYearSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $thisYear)
                ->sum('total') ?? 0;

            // Sales Count
            $todayOrders = Sale::where('created_at', '>=', $today)->count();
            $yesterdayOrders = Sale::whereBetween('created_at', [$yesterday, $today])->count();
            $thisMonthOrders = Sale::where('created_at', '>=', $thisMonth)->count();
            $pendingOrders = Sale::where('status', 'pending')->count();
            $completedOrders = Sale::where('status', 'completed')->count();

            // Products Statistics
            $totalProducts = Product::count();
            $activeProducts = Product::where('is_active', true)->count();
            $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
                ->where('is_active', true)
                ->count();
            $outOfStockCount = Product::where('stock_quantity', '<=', 0)
                ->where('is_active', true)
                ->count();
            $totalStockValue = Product::where('is_active', true)
                ->sum(DB::raw('stock_quantity * cost_price')) ?? 0;

            // Customers Statistics
            $totalCustomers = Customer::count();
            $newCustomersToday = Customer::where('created_at', '>=', $today)->count();
            $newCustomersThisMonth = Customer::where('created_at', '>=', $thisMonth)->count();
            $activeCustomers = Customer::whereHas('sales')->count();

            // Purchases Statistics
            $totalPurchases = Purchase::where('created_at', '>=', $last30Days)->sum('total') ?? 0;
            $thisMonthPurchases = Purchase::where('created_at', '>=', $thisMonth)->sum('total') ?? 0;
            $totalSuppliers = Supplier::count();

            // Quotations Statistics
            $totalQuotations = Quotation::count();
            $pendingQuotations = Quotation::where('status', 'pending')->count();
            $approvedQuotations = Quotation::where('status', 'approved')->count();
            $quotationValue = Quotation::where('status', 'approved')->sum('total') ?? 0;

            // Financial Statistics
            $totalExpenses = Expense::where('created_at', '>=', $last30Days)->sum('amount') ?? 0;
            $thisMonthExpenses = Expense::where('created_at', '>=', $thisMonth)->sum('amount') ?? 0;
            $profit = $totalSales - $totalExpenses;
            $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;

            // Daily Sales for Last 7 Days (for chart)
            $dailySales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $last7Days)
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Monthly Sales for Last 6 Months
            $monthlySales = Sale::where('status', 'completed')
                ->where('created_at', '>=', $now->copy()->subMonths(6))
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // Top Selling Products
            $topProducts = DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.status', 'completed')
                ->where('sales.created_at', '>=', $last30Days)
                ->select(
                    'products.name',
                    'products.sku',
                    DB::raw('SUM(sale_items.quantity) as total_quantity'),
                    DB::raw('SUM(sale_items.total) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->get();

            // Top Customers
            $topCustomers = Customer::withCount(['sales' => function($query) use ($last30Days) {
                $query->where('status', 'completed')
                      ->where('created_at', '>=', $last30Days);
            }])
            ->withSum(['sales' => function($query) use ($last30Days) {
                $query->where('status', 'completed')
                      ->where('created_at', '>=', $last30Days);
            }], 'total')
            ->orderBy('sales_sum_total', 'desc')
            ->limit(5)
            ->get();

            // Recent Sales
            $recentSales = Sale::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Payment Methods Breakdown
            $paymentMethods = Sale::where('status', 'completed')
                ->where('created_at', '>=', $last30Days)
                ->select(
                    'payment_method',
                    DB::raw('SUM(total) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('payment_method')
                ->get();

            // Sales Growth Percentage
            $salesGrowth = $yesterdaySales > 0 
                ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 
                : 0;

            // Month-over-Month Growth
            $monthGrowth = $lastMonthSales > 0 
                ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
                : 0;

        } catch (\Exception $e) {
            // If tables don't exist yet or there's an error, return defaults
            $totalSales = 0;
            $todaySales = 0;
            $yesterdaySales = 0;
            $thisMonthSales = 0;
            $lastMonthSales = 0;
            $thisYearSales = 0;
            $todayOrders = 0;
            $yesterdayOrders = 0;
            $thisMonthOrders = 0;
            $pendingOrders = 0;
            $completedOrders = 0;
            $totalProducts = 0;
            $activeProducts = 0;
            $lowStockCount = 0;
            $outOfStockCount = 0;
            $totalStockValue = 0;
            $totalCustomers = 0;
            $newCustomersToday = 0;
            $newCustomersThisMonth = 0;
            $activeCustomers = 0;
            $totalPurchases = 0;
            $thisMonthPurchases = 0;
            $totalSuppliers = 0;
            $totalQuotations = 0;
            $pendingQuotations = 0;
            $approvedQuotations = 0;
            $quotationValue = 0;
            $totalExpenses = 0;
            $thisMonthExpenses = 0;
            $profit = 0;
            $profitMargin = 0;
            $dailySales = collect();
            $monthlySales = collect();
            $topProducts = collect();
            $topCustomers = collect();
            $recentSales = collect();
            $paymentMethods = collect();
            $salesGrowth = 0;
            $monthGrowth = 0;
        }

        return view('dashboard', compact(
            'totalSales', 'todaySales', 'yesterdaySales', 'thisMonthSales', 'lastMonthSales', 'thisYearSales',
            'todayOrders', 'yesterdayOrders', 'thisMonthOrders', 'pendingOrders', 'completedOrders',
            'totalProducts', 'activeProducts', 'lowStockCount', 'outOfStockCount', 'totalStockValue',
            'totalCustomers', 'newCustomersToday', 'newCustomersThisMonth', 'activeCustomers',
            'totalPurchases', 'thisMonthPurchases', 'totalSuppliers',
            'totalQuotations', 'pendingQuotations', 'approvedQuotations', 'quotationValue',
            'totalExpenses', 'thisMonthExpenses', 'profit', 'profitMargin',
            'dailySales', 'monthlySales', 'topProducts', 'topCustomers', 'recentSales',
            'paymentMethods', 'salesGrowth', 'monthGrowth', 'now'
        ));
    }
}
