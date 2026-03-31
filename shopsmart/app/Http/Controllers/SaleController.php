<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items', 'items.product', 'payments']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Statistics (All time)
        $totalSales = Sale::count();
        $totalAmount = Sale::sum('total');
        
        // Today's statistics
        $todaySales = Sale::whereDate('created_at', today())->count();
        $todayAmount = Sale::whereDate('created_at', today())->sum('total');
        
        // This month statistics
        $thisMonthSales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $thisMonthAmount = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        // Status breakdown
        $statusBreakdown = Sale::selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Payment methods breakdown
        $paymentMethods = Sale::selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        $sales = $query->latest()->paginate(20);
        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('sales.index', compact(
            'sales', 'totalSales', 'totalAmount', 
            'todaySales', 'todayAmount', 
            'thisMonthSales', 'thisMonthAmount',
            'statusBreakdown', 'paymentMethods', 'customers', 
            'dateFrom', 'dateTo'
        ));
    }

    public function invoices(Request $request)
    {
        $query = Sale::where('status', 'completed')
            ->with(['customer', 'user', 'items', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Apply date range to filtered query for statistics
        $filteredQuery = Sale::where('status', 'completed');
        if ($request->filled('date_from')) {
            $filteredQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $filteredQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $filteredQuery->where('payment_method', $request->payment_method);
        }
        if ($request->filled('customer_id')) {
            $filteredQuery->where('customer_id', $request->customer_id);
        }

        // Statistics (All time)
        $totalInvoices = Sale::where('status', 'completed')->count();
        $totalAmount = Sale::where('status', 'completed')->sum('total');
        
        // Today's statistics
        $todayInvoices = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
        $todayAmount = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');
        
        // This month statistics
        $thisMonthInvoices = Sale::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $thisMonthAmount = Sale::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        // Last month for comparison
        $lastMonthAmount = Sale::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');
        $monthGrowth = $lastMonthAmount > 0 ? (($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100 : 0;

        // Filtered statistics
        $filteredInvoices = $filteredQuery->count();
        $filteredAmount = $filteredQuery->sum('total');
        $averageInvoice = $filteredInvoices > 0 ? $filteredAmount / $filteredInvoices : 0;

        // Payment methods breakdown (filtered)
        $paymentMethodsQuery = Sale::where('status', 'completed');
        if ($request->filled('date_from')) {
            $paymentMethodsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $paymentMethodsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        $paymentMethods = $paymentMethodsQuery
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        // Daily sales trend (last 30 days)
        $dailySales = Sale::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers (filtered)
        $topCustomersQuery = Sale::where('status', 'completed')->whereNotNull('customer_id');
        if ($request->filled('date_from')) {
            $topCustomersQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $topCustomersQuery->whereDate('created_at', '<=', $request->date_to);
        }
        $topCustomers = $topCustomersQuery
            ->selectRaw('customer_id, COUNT(*) as invoice_count, SUM(total) as total_spent')
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get()
            ->load('customer');

        // Top products sold
        $topProductsQuery = \App\Models\SaleItem::whereHas('sale', function($q) {
                $q->where('status', 'completed');
            });
        if ($request->filled('date_from')) {
            $topProductsQuery->whereHas('sale', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $topProductsQuery->whereHas('sale', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }
        $topProducts = $topProductsQuery
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total) as total_revenue')
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->load('product');

        $sales = $query->latest()->paginate(20);
        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('sales.invoices', compact(
            'sales', 'totalInvoices', 'totalAmount', 
            'todayInvoices', 'todayAmount', 
            'thisMonthInvoices', 'thisMonthAmount', 'monthGrowth',
            'filteredInvoices', 'filteredAmount', 'averageInvoice',
            'paymentMethods', 'customers', 'dailySales', 
            'topCustomers', 'topProducts', 'dateFrom', 'dateTo'
        ));
    }

    public function returns(Request $request)
    {
        // Get sales returns
        $salesQuery = Sale::where('status', 'refunded')
            ->with(['customer', 'user', 'items.product']);

        // Get stock movement returns
        $stockMovementsQuery = StockMovement::where('type', 'return')
            ->with(['product', 'warehouse', 'user']);

        // Apply filters to sales
        if ($request->filled('search')) {
            $search = $request->search;
            $salesQuery->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply filters to stock movements
        if ($request->filled('search')) {
            $search = $request->search;
            $stockMovementsQuery->where(function($q) use ($search) {
                $q->whereHas('product', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $salesQuery->whereDate('created_at', '>=', $request->date_from);
            $stockMovementsQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $salesQuery->whereDate('created_at', '<=', $request->date_to);
            $stockMovementsQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Get filtered results
        $sales = $salesQuery->latest()->get();
        $stockMovements = $stockMovementsQuery->latest()->get();

        // Combine and sort by date
        $allReturns = collect();
        
        // Add sales returns
        foreach ($sales as $sale) {
            $allReturns->push([
                'type' => 'sale',
                'id' => $sale->id,
                'reference' => $sale->invoice_number ?? '#' . $sale->id,
                'date' => $sale->created_at,
                'amount' => $sale->total,
                'customer' => $sale->customer,
                'items_count' => $sale->items->count(),
                'data' => $sale,
            ]);
        }
        
        // Add stock movement returns
        foreach ($stockMovements as $movement) {
            $estimatedValue = ($movement->product->selling_price ?? 0) * $movement->quantity;
            $allReturns->push([
                'type' => 'stock_movement',
                'id' => $movement->id,
                'reference' => 'SM-' . $movement->id,
                'date' => $movement->created_at,
                'amount' => $estimatedValue,
                'customer' => null,
                'items_count' => 1,
                'data' => $movement,
            ]);
        }

        // Sort by date descending and paginate
        $sortedReturns = $allReturns->sortByDesc('date');
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $paginatedReturns = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedReturns->forPage($currentPage, $perPage),
            $sortedReturns->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Statistics (include both types)
        $totalSalesReturns = Sale::where('status', 'refunded')->count();
        $totalStockReturns = StockMovement::where('type', 'return')->count();
        $totalReturns = $totalSalesReturns + $totalStockReturns;
        
        $totalSalesRefundAmount = Sale::where('status', 'refunded')->sum('total');
        $totalStockRefundAmount = StockMovement::where('type', 'return')
            ->with('product')
            ->get()
            ->sum(function($movement) {
                return ($movement->product->selling_price ?? 0) * $movement->quantity;
            });
        $totalRefundAmount = $totalSalesRefundAmount + $totalStockRefundAmount;
        
        $todaySalesReturns = Sale::where('status', 'refunded')
            ->whereDate('created_at', today())
            ->count();
        $todayStockReturns = StockMovement::where('type', 'return')
            ->whereDate('created_at', today())
            ->count();
        $todayReturns = $todaySalesReturns + $todayStockReturns;
        
        $todaySalesRefundAmount = Sale::where('status', 'refunded')
            ->whereDate('created_at', today())
            ->sum('total');
        $todayStockRefundAmount = StockMovement::where('type', 'return')
            ->whereDate('created_at', today())
            ->with('product')
            ->get()
            ->sum(function($movement) {
                return ($movement->product->selling_price ?? 0) * $movement->quantity;
            });
        $todayRefundAmount = $todaySalesRefundAmount + $todayStockRefundAmount;
        
        $thisMonthSalesRefundAmount = Sale::where('status', 'refunded')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $thisMonthStockRefundAmount = StockMovement::where('type', 'return')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('product')
            ->get()
            ->sum(function($movement) {
                return ($movement->product->selling_price ?? 0) * $movement->quantity;
            });
        $thisMonthRefundAmount = $thisMonthSalesRefundAmount + $thisMonthStockRefundAmount;

        // Monthly returns trend (combine both)
        $monthlySalesReturns = Sale::where('status', 'refunded')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total) as total')
            ->groupBy('month')
            ->get()
            ->keyBy('month');
            
        $monthlyStockReturns = StockMovement::where('type', 'return')
            ->where('created_at', '>=', now()->subMonths(6))
            ->with('product')
            ->get()
            ->groupBy(function($movement) {
                return $movement->created_at->format('Y-m');
            })
            ->map(function($group) {
                return [
                    'month' => $group->first()->created_at->format('Y-m'),
                    'count' => $group->count(),
                    'total' => $group->sum(function($movement) {
                        return ($movement->product->selling_price ?? 0) * $movement->quantity;
                    })
                ];
            });
            
        $monthlyReturns = collect();
        $allMonths = $monthlySalesReturns->keys()->merge($monthlyStockReturns->keys())->unique()->sort();
        foreach ($allMonths as $month) {
            $salesData = $monthlySalesReturns->get($month, (object)['count' => 0, 'total' => 0]);
            $stockData = $monthlyStockReturns->get($month, ['count' => 0, 'total' => 0]);
            $monthlyReturns->push((object)[
                'month' => $month,
                'count' => ($salesData->count ?? 0) + ($stockData['count'] ?? 0),
                'total' => ($salesData->total ?? 0) + ($stockData['total'] ?? 0),
            ]);
        }

        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('sales.returns', compact(
            'paginatedReturns', 
            'totalReturns', 
            'totalRefundAmount', 
            'todayReturns', 
            'todayRefundAmount', 
            'thisMonthRefundAmount', 
            'monthlyReturns', 
            'customers'
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
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'warehouse']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Print receipt (thermal printer format)
     */
    public function print(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'warehouse']);
        return view('sales.receipt-print', compact('sale'));
    }

    /**
     * Download receipt as PDF
     */
    public function pdf(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'warehouse']);
        
        // Get company settings
        $companyName = \App\Models\Setting::get('company_name', 'ShopSmart');
        $companyAddress = \App\Models\Setting::get('company_address', 'Dar es Salaam, Tanzania');
        $companyPhone = \App\Models\Setting::get('company_phone', '+255 XXX XXX XXX');
        $companyEmail = \App\Models\Setting::get('company_email', '');
        $companyLogo = \App\Models\Setting::get('company_logo', '');
        
        // Generate PDF using facade or service container
        try {
            $pdf = Pdf::loadView('sales.receipt-pdf', compact('sale', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail', 'companyLogo'));
        } catch (\Exception $e) {
            // Fallback: use service container directly
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('sales.receipt-pdf', compact('sale', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail', 'companyLogo'));
        }
        
        // Set PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        
        // Download PDF with filename
        $invoiceNumber = $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT);
        $filename = 'Receipt-' . $invoiceNumber . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
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
     * Record payment for a credit sale
     */
    public function recordPayment(Request $request, Sale $sale)
    {
        if ($sale->payment_method !== 'credit') {
            return response()->json([
                'success' => false,
                'message' => 'This sale is not a credit sale.',
            ], 400);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['amount'] > $sale->balance) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount cannot exceed the balance of TZS ' . number_format($sale->balance, 2),
            ], 400);
        }

        $payment = \App\Models\SalePayment::create([
            'sale_id' => $sale->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? null,
            'user_id' => auth()->id(),
        ]);

        $sale->load('payments');

        return response()->json([
            'success' => true,
            'payment' => $payment,
            'sale' => $sale,
            'total_paid' => $sale->total_paid,
            'balance' => $sale->balance,
            'is_fully_paid' => $sale->isFullyPaid(),
            'message' => 'Payment recorded successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createReturn()
    {
        $customers = \App\Models\Customer::orderBy('name')->get();
        $products = \App\Models\Product::where('is_active', true)->orderBy('name')->get();
        $sales = \App\Models\Sale::where('status', 'completed')->with(['customer', 'items.product'])->latest()->get();
        
        return view('sales.create-return', compact('customers', 'products', 'sales'));
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'return_type' => 'required|in:sale,stock_movement',
            'sale_id' => 'required_if:return_type,sale|exists:sales,id',
            'product_id' => 'required_if:return_type,stock_movement|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'refund_amount' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            if ($request->return_type === 'sale') {
                // Handle sale return
                $sale = \App\Models\Sale::findOrFail($request->sale_id);
                
                // Update sale status to refunded
                $sale->update([
                    'status' => 'refunded',
                    'notes' => ($sale->notes ?? '') . "\n\nReturn: " . $request->reason
                ]);

                // Create stock movement for returned items
                foreach ($sale->items as $item) {
                    \App\Models\StockMovement::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $sale->warehouse_id ?? 1,
                        'type' => 'return',
                        'quantity' => $item->quantity,
                        'reference' => 'Return from Sale #' . $sale->invoice_number,
                        'user_id' => auth()->id(),
                        'notes' => $request->reason
                    ]);

                    // Update product stock
                    $item->product->increment('stock_quantity', $item->quantity);
                }

            } else {
                // Handle stock movement return
                $product = \App\Models\Product::findOrFail($request->product_id);
                
                \App\Models\StockMovement::create([
                    'product_id' => $request->product_id,
                    'warehouse_id' => 1, // Default warehouse
                    'type' => 'return',
                    'quantity' => $request->quantity,
                    'reference' => 'Direct Return',
                    'user_id' => auth()->id(),
                    'notes' => $request->reason
                ]);

                // Update product stock
                $product->increment('stock_quantity', $request->quantity);
            }

            return response()->json([
                'success' => true,
                'message' => 'Return processed successfully!',
                'redirect_url' => route('sales.returns')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing return: ' . $e->getMessage()
            ], 500);
        }
    }
}
