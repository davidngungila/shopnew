<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'user', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply date range to filtered query for statistics
        $filteredQuery = \App\Models\Purchase::query();
        if ($request->filled('date_from')) {
            $filteredQuery->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $filteredQuery->whereDate('purchase_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $filteredQuery->where('status', $request->status);
        }
        if ($request->filled('supplier_id')) {
            $filteredQuery->where('supplier_id', $request->supplier_id);
        }

        // Statistics (All time)
        $totalPurchases = \App\Models\Purchase::count();
        $totalAmount = \App\Models\Purchase::sum('total');
        
        // Today's statistics
        $todayPurchases = \App\Models\Purchase::whereDate('purchase_date', today())->count();
        $todayAmount = \App\Models\Purchase::whereDate('purchase_date', today())->sum('total');
        
        // This month statistics
        $thisMonthPurchases = \App\Models\Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->count();
        $thisMonthAmount = \App\Models\Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');
        
        // Last month for comparison
        $lastMonthAmount = \App\Models\Purchase::whereMonth('purchase_date', now()->subMonth()->month)
            ->whereYear('purchase_date', now()->subMonth()->year)
            ->sum('total');
        $monthGrowth = $lastMonthAmount > 0 ? (($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100 : 0;

        // Filtered statistics
        $filteredPurchases = $filteredQuery->count();
        $filteredAmount = $filteredQuery->sum('total');
        $averagePurchase = $filteredPurchases > 0 ? $filteredAmount / $filteredPurchases : 0;

        // Status breakdown (filtered)
        $statusBreakdownQuery = \App\Models\Purchase::query();
        if ($request->filled('date_from')) {
            $statusBreakdownQuery->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statusBreakdownQuery->whereDate('purchase_date', '<=', $request->date_to);
        }
        $statusBreakdown = $statusBreakdownQuery
            ->selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Daily purchases trend (last 30 days)
        $dailyPurchases = \App\Models\Purchase::where('purchase_date', '>=', now()->subDays(30))
            ->selectRaw('DATE(purchase_date) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top suppliers (filtered)
        $topSuppliersQuery = \App\Models\Purchase::whereNotNull('supplier_id');
        if ($request->filled('date_from')) {
            $topSuppliersQuery->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $topSuppliersQuery->whereDate('purchase_date', '<=', $request->date_to);
        }
        $topSuppliers = $topSuppliersQuery
            ->selectRaw('supplier_id, COUNT(*) as purchase_count, SUM(total) as total_spent')
            ->groupBy('supplier_id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get()
            ->load('supplier');

        // Top products purchased
        $topProductsQuery = \App\Models\PurchaseItem::whereHas('purchase', function($q) {
                // Base query
            });
        if ($request->filled('date_from')) {
            $topProductsQuery->whereHas('purchase', function($q) use ($request) {
                $q->whereDate('purchase_date', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $topProductsQuery->whereHas('purchase', function($q) use ($request) {
                $q->whereDate('purchase_date', '<=', $request->date_to);
            });
        }
        $topProducts = $topProductsQuery
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total) as total_cost')
            ->groupBy('product_id')
            ->orderBy('total_cost', 'desc')
            ->limit(5)
            ->get()
            ->load('product');

        $purchases = $query->latest('purchase_date')->paginate(20);
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchases.index', compact(
            'purchases', 'totalPurchases', 'totalAmount', 
            'todayPurchases', 'todayAmount', 
            'thisMonthPurchases', 'thisMonthAmount', 'monthGrowth',
            'filteredPurchases', 'filteredAmount', 'averagePurchase',
            'statusBreakdown', 'suppliers', 'dailyPurchases', 
            'topSuppliers', 'topProducts', 'dateFrom', 'dateTo'
        ));
    }

    public function orders(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'user', 'warehouse', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'ordered', 'partial']);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        // Supplier filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Statistics
        $totalOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])->count();
        $totalValue = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])->sum('total');
        $pendingOrders = \App\Models\Purchase::where('status', 'pending')->count();
        $overdueOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->where('expected_delivery_date', '<', now())
            ->count();
        
        // This month orders
        $thisMonthOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->count();
        $thisMonthValue = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');

        // Status breakdown
        $statusBreakdown = \App\Models\Purchase::selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->whereIn('status', ['pending', 'ordered', 'partial', 'completed', 'cancelled'])
            ->groupBy('status')
            ->get();

        // Daily orders trend (last 30 days)
        $dailyOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->where('purchase_date', '>=', now()->subDays(30))
            ->selectRaw('DATE(purchase_date) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top suppliers by order value
        $topSuppliers = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->whereNotNull('supplier_id')
            ->selectRaw('supplier_id, COUNT(*) as order_count, SUM(total) as total_value')
            ->groupBy('supplier_id')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get()
            ->load('supplier');

        $purchases = $query->latest('purchase_date')->paginate(20);
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchases.orders', compact(
            'purchases', 'totalOrders', 'totalValue', 'pendingOrders', 'overdueOrders', 
            'thisMonthOrders', 'thisMonthValue', 'statusBreakdown', 'suppliers', 
            'dailyOrders', 'topSuppliers', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
